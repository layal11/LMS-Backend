<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class ImageUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
    */

    public function imageUpload(){
        return response()->json('imageUpload');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function imageUploadPost(Request $request)  {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $date = Carbon::now();

        $date->toDateString();

        dd($request->image);
//        $profiles = Profile::where('id', $id)->with('user:id')->first();
        $image = $request->image->hashName() ;
       // Store Image in Public Folder
       $request->image->move(public_path('images'), $image);
       // public/images/file.png

        /* Store $imageName name in DATABASE from HERE */
            // Store Image in Storage Folder
            // $request->image->storeAs('images', $imageName);
            // storage/app/images/file.png
    return response()->json('You have successfully upload image.');
//        return back()
//            ->with('success','You have successfully upload image.')
//            ->with('image',$image);
    }
}
