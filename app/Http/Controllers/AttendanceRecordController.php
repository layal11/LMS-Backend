<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceRecord;
use App\Models\AttendanceType;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceRecordController extends Controller
{

    public function countRecordOfStudent(Request $request){

        $attendances = Attendance::where('section_id', $request['section_id'])->get()->pluck('id');

        $records = AttendanceRecord::where('student_id', $request['student_id'])
            ->whereIn('attendance_id', $attendances)->get();

        $getRecordsArray = [
            'present' => 0,
            'late' => 0,
            'absent' => 0,
        ];

        $count = 0;
        foreach ($records as $record){
            if($record->attendance_type_id == 1){
                $getRecordsArray['present']++;
            } else if ($record->attendance_type_id == 2){
                $getRecordsArray['late']++;
            } else if ($record->attendance_type_id == 3){
                $getRecordsArray['absent']++;
            }
            $count++;
        }
        $getRecordsArray['present'] = (int)(($getRecordsArray['present']/$count) * 100);

        $getRecordsArray['late'] = (int)(($getRecordsArray['late']/$count) * 100);
        $getRecordsArray['absent'] = (int)(($getRecordsArray['absent']/$count) * 100);

        return response()->json([
            'section_id' => $request->section_id,
//            'data' => $getRecordsArray
            'present' =>  $getRecordsArray['present'],
            'late'=>$getRecordsArray['late'],
            'absent'=>$getRecordsArray['absent'],
        ]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {try {
        if (isset($request['section_id']) && $request['section_id'] != '')  {  // ?section_id= in the link
            return Attendance::where('section_id', $request['section_id'])
                ->with('attendanceRecord', 'attendanceRecord.student.profile', 'attendanceRecord.attendanceType')->get();

        }
    }
    catch(\Exception $e){
        return response()->json([
            'Message'=>'Internal error'
        ],500);
    }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $inputs = $request->all();

        $attendanceExists = Attendance::where([['section_id', '=', $inputs['section_id']],['date', '=',  $inputs['date']]])->first();
        if(!$attendanceExists){
            $attendance = new  Attendance();
            $attendance->fill($request->all());
            if($attendance->save()){
                //get all students in section
                $students = Student::where('section_id' , '=', $inputs['section_id'])->get();

                foreach($students as $student){
                    $data['attendance_id'] = $attendance->id;
                    $data['attendance_type_id'] = 1;
                    $data['student_id'] = $student->id;
                    AttendanceRecord::create($data);
                }
                return response()->json([
                    "Message" => "Attendance record created successfully",
                    "Data" => Attendance::where('id', $attendance->id)
                        ->with('attendanceRecord.student.profile')->get(),
                ], 200);
            }else {
                return response()->json([
                    "Message" => "Attendance record could not be created",
                ],200);
            }
        }
        return response()->json([
            "Message" => "Attendance record fetched successfully",
            "Data" => Attendance::where('id', $attendanceExists->id)
                ->with('attendanceRecord.student.profile')->get(),
        ], 200);
//        $requestData = $request->all();
//
//        $attendance = new  Attendance();
//        $attendance->fill($requestData);
////        return $attendance;
//        $attendance->Save();
//        if($attendance && $attendance->id){
//            foreach ($requestData["students_attendance"] as $key => $data) {
//                $data['attendance_id'] = $attendance->id;
//                AttendanceRecord::create($data);
//            }
//            return response()->json([
//                "Message" => "Attendance record created successfully",
//                "Data" => $requestData["students_attendance"],
//            ],200);
//        } else {
//            return response()->json([
//                "Message" => "Attendance record could not be created",
//            ],200);
//        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request

     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,$id)
    {
        $inputs = $request->all();

        $type = AttendanceRecord::where([['id', '=', $id],['student_id', '=',  $inputs['student_id']]])->first();
//        return $type;
        $type->update($request->all());
        if ($type->save()){
            return response()->json([
                "Message" => "Attendance record updated successfully",
                "Data" => $type,
            ], 200);
        } else {
            return response()->json([
                "Message" => "Attendance record CRASH",
            ], 200);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
