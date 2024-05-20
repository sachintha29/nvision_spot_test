<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\AUth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;


class AuthenticationController extends Controller
{

    public function register(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8'
            ]
            );

            if($validateUser->fails()){
                return response()->json([
                    'status'=> false,
                    'message'=>'validation error',
                    'errors'=> $validateUser->errors()
                ],422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'status'=> true,
                'message'=>'User created sucessfully',
                'token' => $user->createToken('API TOKEN')->plainTextToken,
            ],200);

        } catch (\Throwable $th) {
            return response()->json([
                'status'=> false,
                'message'=> $th->getMessage(),
            ],500);
        }


    }


    public function login(Request $request){
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required',
                'password' => 'required'
            ]
            );

            if($validateUser->fails()){
                return response()->json([
                    'status'=> false,
                    'message'=>'validation error',
                    'errors'=> $validateUser->errors()
                ],422);
            }

           if(!Auth::attempt($request->only(['email','password']))){
                return response()->json([
                    'status'=> false,
                    'message'=>'Email and password is not match with our records',
                ],401);
           }

           $user = User::where('email',$request->email)->first();

           return response()->json([
            'status'=> true,
            'message'=>'User login  sucessfully',
            'token' => $user->createToken('API TOKEN')->plainTextToken,
            ],200);

        } catch (\Throwable $th) {
            return response()->json([
                'status'=> false,
                'message'=> $th->getMessage(),
            ],500);
        }
    }

    public function profile(){
        $userData = auth()->user();

        return response()->json([
            'status'=> true,
            'message'=>'Profile information',
            'data'=> $userData,
            'id' => auth()->user()->id,
            ],200);

    }

    public function logout(){
        auth()->user()->tokens()->delete();

        return response()->json([
            'status'=> true,
            'message'=>'User logged out',
            'data'=> [],
        ],200);

    }

}
