<?php

namespace App\Http\Controllers;
use App\Models\Section;

use App\Models\Classes;

use App\Models\Student;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ClassesRequest;


class ClassController extends Controller
{
    public function countAll(){
        $class = Classes::all();
        $classCount = count($class);
        $section = Section::all();
        $sectionCount = count($section);
        $student = Student::all();
        $studentCount = count($student);

        return response()->json([
            'Message'=>"Count",
            "Data"=>[
                "Class"=> $classCount,
                "Section"=> $sectionCount,
                "Student"=> $studentCount,
            ]
        ],200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
       // return Classes::all();
        try{
            $classes = Classes::orderBy('name', 'ASC')->get();
            if($classes){
                return response()->json([
                    "Message" => "Classes fetched successfully",
                    "Data"=> $classes
                ],200);
            }
            return response()->json([
                "Message" => "There are no sections to show",
                "Data" => []
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
     * @param ClassesRequest $request
     * @return JsonResponse
     */
    public function store(ClassesRequest $request)
    {
        $class = new Classes();
        $class->fill($request->all());//because we used fillable

        if ($class->save()){ //returns a boolean
            return response()->json([
                "Message" => "Class created successfully",
                "Data" => $class
            ],200);
        } else {
            return response()->json([
                "Message" => "Class could not be created"
            ],500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        // NOTE:
        // return Classes::where('id', $id)->first(); //returns an object
        // return Classes::where('id', $id)->get();  //returns an array
        $classes = Classes::where('id', $id)->first();
        if($classes) {
            return Response()->json([
                "Message" => "Class ID fetched successfully",
                "Data" => $classes
            ],200);
        } else {
            return Response()->json([
                "Message" => "Class ID not found",
                "Data" => []
            ],200);
        }
    }

    public function getSectionByClassID(int $id): JsonResponse
    {
        // NOTE:
        // return Classes::where('id', $id)->first(); //returns an object
        // return Classes::where('id', $id)->get();  //returns an array
        $sections = Section::where('class_id', $id)->get();
        if($sections) {
            return Response()->json([
                "Message" => "Section name fetched by Class ID",
                "Data" => $sections
            ],200);
        } else {
            return Response()->json([
                "Message" => "Section not found in Class",
                "Data" => []
            ],200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request,int $id)
    {
        $classes = Classes::find($id);
        if($classes) {
            $classes->update($request->all());
            if ($classes->save()) {
                return response()->json([
                    "Message" => "Class updated successfully",
                    "Data" => $classes
                ], 200);
            } else {
                return response()->json([
                    "Message" => "Class couldn't be updated",
                    "Data" => $request->all()
                ], 500);
            }
        } else {
            return response()->json(["Data" => "Class couldn't be found"
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $sections = Section::where('class_id', $id)->first(); //->exists()

        if($sections)
        {
            return response()->json([
            "Message" => "Classes that have sections can't be deleted ",
        ], 200);

        } else {
            Classes::where('id', $id)->delete();
            return response()->json([
                "Message" => "Class deleted successfully",
            ], 200);
        }
    }
    public function countClasses(Request $request){
        $Class = Classes::all();
        $count = count($Class);
        return response()->json([
            'Message'=>"Classes count",
            "Data"=>$count
        ],200);
    }
}
