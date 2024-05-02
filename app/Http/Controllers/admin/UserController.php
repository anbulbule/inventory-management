<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email'=>'required',
            'password'=>'required'
        ]);        
        $user = User::where('email',$request->email)->first();
        $user->tokens()->where('name', $request->email)->delete();
        if($user && Hash::check($request->password,$user->password)){
            $token = $user->createToken($user->email)->plainTextToken;
            return response([
                'status'=>true,
                'message'=>'Logged in Successfully',
                'data'=>$user,
                'token'=>$token
            ]);
        }else{
            return response([
                'status'=>false,
                'message'=>'Invalid Credentials',
                'data'=>[]
            ]);
        }
    }

    public function register(Request $request){
        
    }

}
