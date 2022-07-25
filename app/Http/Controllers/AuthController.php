<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminValidator;
use App\Models\Admin;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateAdminValidator;



class AuthController extends Controller
{

    public function register(AdminValidator $request) {
        $inputs = $request->validated();
        $admin = new Admin();
        $admin->fill($inputs);
        $admin->save();
    $token = auth('api')->login($admin);

    return $this->respondWithTokenRegister($token);


}
    public function index()
    {
        //

        $admin = Admin::all();
        return response()->json($admin);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function login(): \Illuminate\Http\JsonResponse
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithTokenLogin($token);


    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithTokenRegister($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory('api')->getTTL() * 60,
            'message' => 'Successfully Registered',

        ]);
    }
    protected function respondWithTokenLogin($token)
    {
        $email = request(['email']);
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory('api')->getTTL() * 60,
            'message' => 'Successfully Logged In',
            'email' => $email,



        ]);
    }
    public function show($email)
    {
        return Admin::where('email',$email)->first();
    }
    public function getAdminId($id)
    {
        return Admin::where('id',$id)->first();
    }
    public function destroy($id)
    {     $email = request(['email']);
         Admin::where('id',$id)->delete();
        return response()->json([
            'message' => 'Succesfully Deleted',
            'email'=> $email
        ]);
    }
    public function update(UpdateAdminValidator $request, $id){

        $inputs = $request->validated();
        $admin =  Admin::where('id',$id)->first();
        $admin->update($inputs);
        return response()->json([
            'message' => 'Succesfully Updated',
            'email'=> $admin

            ]);

    }

}
