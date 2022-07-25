<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use App\Models\Section;
use App\Models\Student;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class ProfileController extends Controller
{
    public function filterStudents(Request $request, string $name){
        $filter = Profile::whereRaw("CONCAT(name,' ',last_name) like ?", ['%'. $name . '%'])->get();
        return $filter;
    }

    public function countStudents(Request $request){
        $student = Profile::all();
        $count = count($student);
        return response()->json([
            'Message'=>"students count",
            "Data"=>$count
            ],200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try{
            if(isset($request['class_id']) && $request['class_id'] != ''){
                $students = Student::where('class_id', $request['class_id'])
                ->with('profile', 'section', 'classes')->get();
            }
            elseif(isset($request['section_id']) && $request['section_id'] != '')
            {
                $students = Student::where('section_id', $request['section_id'])
                    ->with('profile', 'section', 'classes')->get();
            }
            else {
                $students= Student::with('profile', 'section', 'classes')->get();
            }
            if($students){
                return response()->json(
                    $students
                ,200);
            }
            return response()->json([
                "Message" => "There are no profiles to show",
                "Data" => []
            ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'Message'=>'Internal error'
            ],500);
        }
//        try{
//            $profiles = Profile::orderBy('id', 'ASC')->with('student:id,class_id,section_id','student.classes','student.section')->get();
//            if($profiles){
//                return response()->json(
//                  $profiles
//                ,200);
//            }
//            return response()->json([
//                "Message" => "There are no profiles to show",
//                "Data" => []
//            ],200);
//        }
//        catch(\Exception $e){
//            return response()->json([
//                'Message'=>'Internal error'
//            ],500);
//        }
//
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProfileRequest $request
     * @return JsonResponse
     */
    public function store(ProfileRequest $request)
    {
        $inputs = $request->only(['email', 'phone', 'section_id', 'class_id','last_name']); //returns data in an object

        $sectionExists = Section::where('id', $inputs['section_id'])->first();
        if($sectionExists && $sectionExists->class_id == $inputs['class_id']){
            $student = new Student();
            $student->fill($request->all()); // $fillable in Model

            if($student->save()){
                $profile = new Profile();
                $profile->fill($request->all()); // $fillable in Model
                $profile->image = $request->image;
                $profile->image= $request->image->hashName();
                $profile->student_id = $student->id;

                //dd($image);
                // Store Image in Public Folder
                $request->image->move(public_path('images'), $profile->image);
                if($profile->save()){
                    return response()->json([
                        'success' => true,
                        "message" => "Profile created successfully",
                        "Data" => $profile
                    ], 200);
                }else {
                    return response()->json([
                        'success' => false,
                        "message" => "Error while creating profile",
                    ], 500);
                }
            } else{
                return response()->json([
                    'success' => false,
                    "message" => "Error while creating student",
                ], 500);
            }
        } else{
            return response()->json([
                'success' => false,
                "message" => "Class or section doesn't exists",
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $students = Student::where('id', $id)
            ->with('profile', 'section', 'classes')->first();
        if($students) {
            return Response()->json([
                "Message" => "Profile ID fetched successfully",
                "Data" => $students
            ],200);
        } else {
            return Response()->json([
                "Message" => "Profile ID not found",
                "Data" => []
            ],200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id)
    {
        //get by student id and profile id
        $inputs = $request->only(['name', 'last_name', 'phone', 'email', 'section_id',
            'class_id', 'student_id']); //returns an array kalb
//        $student_id = $inputs['student_id'];
//        , ['student_id', '=', $student_id]]
        $profile = Profile::where('id', $id)->first();

        if ($profile) {
//            $sectionId = $inputs['section_id'];
//            $classId = $inputs['class_id'];
//            $sectionClass = Section::where([['id', '=', $sectionId], ['class_id', '=', $classId]])->first();

//            if ($sectionClass) {
                $profile->update($inputs);
//                $student = Student::where('id',$student_id)->first();
//                if($student) {
//                    $student->update($inputs);
//                }
//                return response()->json([
//                    "Data" => $request->hasFile('image')
//                ], 200);
                if ($request->hasFile('image')) {
                    try {
                        unlink("images/" . $profile->image);
                    } catch (\Exception $exception) {
                        echo 'hello';
                    }
                    try{
                        $profile->image = $request->image;
                        $profile->image = $request->image->hashName();
                        $request->image->move(public_path('images'), $profile->image);
                    }catch(\Exception $exception) {}
                }
//                $sectionClass->update($request->only('section_id', 'class_id'));
//            && $sectionClass->save()
                if ($profile->save() ) {
                    return response()->json([
                        "Message" => "Profile updated successfully",
                        "Data" => $profile
                    ], 200);
                } else {
                    return response()->json([
                        "Message" => "Couldn't update",
                    ], 200);
                }
//            } else {
//                return response()->json([
//                    "Message" => "No changes occurred",
//                ], 200);
//            }
        } else {
            return response()->json([
                "Message" => "Profile or student Id doesn't exist",
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy(int $id)

    {
          $deleteProfile = Profile::where('id', $id)->first();

        if($deleteProfile){
            try{
                unlink("images/". $deleteProfile->image);
            }
          catch (\Exception $exception){

          }
            $deleteProfile->delete();
            $student = Student::where('id', $deleteProfile->student_id)->first();
            if($student) {
                $student->delete();
            }
            return response()->json([
                "Message" => "Student profile deleted successfully",
            ], 200);
        } else {
            return response()->json([
                "Message" => "Student doesn't exist",
            ], 200);
        }
//
    }
}
