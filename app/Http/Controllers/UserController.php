<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Requests\AdminValidator;

class UserController extends Controller
{
    public function create() {
        return view('registration-form');
    }


    public function store(AdminValidator $request) {



        $dataArray      =       array(
            "name"          =>          $request->name,
            "last_name"          =>          $request->last_name,
            "email"         =>          $request->email,
            "password"      =>          $request->password
        );

        $user           =       Admin::create($dataArray);
        if(!is_null($user)) {
            return back()->with("success", "Success! Registration completed");
        }

        else {
            return back()->with("failed", "Alert! Failed to register");
        }
    }

}
