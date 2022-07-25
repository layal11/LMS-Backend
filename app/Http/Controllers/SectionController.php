<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSectionRequest;
use App\Http\Requests\SectionRequest;
use App\Models\Classes;
use App\Models\Student;
use http\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Section;
use Symfony\Component\Console\Input\Input;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        // return Section::all();
        try{
            $sections = Section::orderBy('name', 'ASC')->with('class:id,name')->get();
            if($sections){
                return response()->json([
                    "Message" => "Sections fetched successfully",
                    "Data"=> $sections
                ],200);
            }
            return response()->json([
                "Message" => "There are no classes to show",
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
     * @param SectionRequest $request
     * @return JsonResponse
     */
    public function store(CreateSectionRequest $request): JsonResponse
    {
        $inputs = $request->validated();
        $section = new Section();
        $sectionExists = Section::where([['name', '=', $inputs['name']],['class_id', '=', $inputs['class_id']]])->exists();
        //check if record exists
        $section->fill($request->all()); // $fillable in Model
        $section->student()->get();
//        return response()->json([
//            "student:"=> $section,
//            "Message" => $sectionExists,
//        ],200);
        if($sectionExists) {
            return response()->json([
                "Message" => "Section already exists in this class",
            ],200);
        } elseif ($section->class()->exists()){ //section shouldn't be added if class doesn't exist
            if($section->save()) //returns a boolean
            {
                return response()->json([
                    "Message" => "Section created successfully",
                    "Data" => $section
                ],200);
            }else{
                return response()->json([
                    "Message" => "Section could not be created",
                ],500);
            }
        }
        else {
            return response()->json([
                "Message" => "Section could not be created. Class doesn't exist",
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        // NOTE:
        // return Classes::where('id', $id)->first(); //returns an object
        // return Classes::where('id', $id)->get();  //returns an array
        $sections = Section::where('id', $id)->with('class:id,name')->first();
        if($sections) {
            return Response()->json([
                "Message" => "Section ID fetched successfully",
                "Data" => $sections
            ],200);
        } else {
            return Response()->json([
                "Message" => "Section ID not found",
                "Data" => []
            ],200);
        }
    }





    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(SectionRequest $request, $id): JsonResponse
    {
        $inputs = $request->validated();
//dd($request->all());
        $section = Section::where('id', $id)->first();
        if ($section) {
            $section->fill($inputs);
            $section->save();

            return response()->json([
                "success" => true,
                "message" => "Section has been updated!",
                "data" => $section
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Section id does not exist!"
            ]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $students = Student::where('section_id', $id)->first();

        if($students)
        {
            return response()->json([
                "Message" => "Sections that have students can't be deleted ",
            ], 200);

        } else {
            Section::where('id', $id)->delete();
            return response()->json([
                "Message" => "Section deleted successfully",
            ], 200);
        }
    }
    public function countSections(Request $request){
        $Section = Section::all();
        $count = count($Section);
        return response()->json([
            'Message'=>"Sections count",
            "Data"=>$count
        ],200);
    }
}
