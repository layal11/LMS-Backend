<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceTypeRequest;
use App\Models\AttendanceType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttendanceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        try{
            $attType = AttendanceType::orderBy('name')->get();

                return response()->json([
                    "Message" => "Attendance type fetched successfully",
                    "Data"=> $attType
                ],200);
        }
        catch(\Exception $e){
            return response()->json([
                'Message'=>'internal error'
            ],500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(AttendanceTypeRequest $request)
    {
        $attType = new AttendanceType();
        $attType->fill($request->all());//because we used fillable

        $attType->save();
        return response()->json([
            "Message" => "Attendance type created successfully",
            "Data" => $attType
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $classes = AttendanceType::where('id', $id)->first();
        if($classes) {
            return Response()->json([
                "Message" => "Attendance type ID fetched successfully",
                "Data" => $classes
            ],200);
        }
        else {
            return Response()->json([
                "Message" => "Attendance type ID not found",
            ],200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
           $attType = AttendanceType::where('id', $id)->delete();
            if($attType) {
                return response()->json([
                    "Message" => "Attendance type deleted successfully",
                ], 200);
            } else {
                return response()->json([
                    "Message" => "Attendance type doesn't exist",
                ], 200);
            }
    }
}
