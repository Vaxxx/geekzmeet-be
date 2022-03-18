<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * Get user details from the database
     */
    public function getUserDetails($email): \Illuminate\Http\JsonResponse
    {

            //get user details
            $user = User::where( 'email', $email)->first();

            if (! $user) {
                return response()->json([
                    'status'=>401,
                    'message' => 'No User with such details'
                ]);
            }else{
                return response()->json([
                    'status'=>200,
                    'firstname' => $user->firstname,
                    'surname' =>$user->surname,
                    'email' => $user->email,
                    'dob' => $user->dob,
                    'gender' => $user->gender,
                    'message' => 'Search was Successful'
                ]);
            }
        }

        public function reactjs(){
          return "I am alive o";
        }

}
