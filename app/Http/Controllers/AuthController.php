<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     * Required by the front end for the registration of the user using
     * the following fields
     * email (must be unique)
     * firstname  (must be >= 3 characters
     * surname  (must be >= 3 characters
     * password (must be >= 8 characters)
     * dob=> date of birth required
     * gender=> male|female|custom => required
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|min:3|max:240',
            'surname' => 'required|min:3|max:240',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8',
            'dob' => 'required',
            'gender' => 'required'

        ]);

        if($validator -> fails()){
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);
        }else{
            $user = User::create([
                'firstname' => $request->firstname,
                'surname' => $request->surname,
                'email' => $request->email,
                'password'=> Hash::make($request->password),
                'dob' => $request->dob,
                'gender' => $request->gender,
            ]);
            $token = $user->createToken($user->email. '_Token')->plainTextToken;
            return response()->json([
                'status'=>200,
                'username' => $user->firstname,
                'token' => $token,
                'message' => 'Registration is Successful'
            ]);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        if($validator -> fails()){
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);
        }else{
            // $user = DB::table("users")->where("email", $request->email)->first();
            $user = User::where( 'email', $request->email)->first();
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status'=>401,
                    'message' => 'Invalid Credentials'
                ]);
            }else{
                // return $user->createToken($request->device_name)->plainTextToken;
                $token = $user->createToken($user->email. '_Token')->plainTextToken;
                //$token = $user->createToken($user->email.'_Token')->accessToken;
                return response()->json([
                    'status'=>200,
                    'user' => $user,
                    'token' => $token,
                    'message' => 'Logged in Successful'
                ]);
            }
        }
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Logged out!'
        ]);
    }

    public function existingUser(){
        return response()->json([
            'status' => 200,
            'user' => auth()->user()
        ]);
    }
}
