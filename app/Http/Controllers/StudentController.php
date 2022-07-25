<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Blog;
use App\Models\Classes;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Section;

class StudentController extends Controller
{

    public function index(){
        return Students::all();
    }

    public function store(Request $request){

     }

     public function registerStudents($id){
        $inputs = Section::where('id',$id)->first();
         $students = new Student();
         $classExists = Classes::where( 'id', '=' , $inputs['class_id'] )->exists();
         $students['section_id'] = $inputs['section_id'];
         $students['class_id'] = $inputs['class_id'];
         if($classExists)
         {
             $students->save();
             return response()->json(['Added Succ']);
         }
         else{
          return  response()->json(['Error']);
            }
     }

    public function show(){
        return Students::where('class_id', $class_id) -> get(); // ->first() : only one entry
        // -> get() : multiple entries
    }


}
