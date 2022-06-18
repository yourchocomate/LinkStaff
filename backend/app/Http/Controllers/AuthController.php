<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        // Validate requests with given rules
        $validatation = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        // return response if validation fails
        if($validatation->fails()) {
            $response = [
                'success' => false,
                'message' => $validatation->messages()
            ];
            return response()->json($response, 503);
        }

        // Try login if validation succeed
        try {

            // Get the user by email from users
            $user = User::where('email', $request->email)->first();
            // Compare the request password with matched email from users also have an alt data if user not found to prevent error
            $password = Hash::check($request->password, $user->password ?? '');

            // Check if user or not matched
            if(!$user || !$password) {
                $response = [
                    'success' => false,
                    'message' => 'Login Invalid'
                ];
                return response()->json($response, 200);
            }

            // Return the response with user object and tokens if all validation passess
            $response = [
                'success' => true,
                'data' => [
                    'user' => $user,
                    'token' => $user->createToken($request->email)->plainTextToken
                ],
                'message' => 'Login Success'
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {
            // Catch the exception to handle error
            $response = [
                'success' => false,
                'message' => 'Something went wrong'
            ];
            return response()->json($response, 503);
        }
        
    }

    public function register(Request $request) {

        // Validate requests with given rules
        $validatation = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        // return response if validation fails
        if($validatation->fails()) {
            $response = [
                'success' => false,
                'message' => $validatation->messages()
            ];
            return response()->json($response, 503);
        }

        // Try login if validation succeed
        try {

            // Insert request data to users table
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'followed_persons' => '[]',
                'followed_pages' => '[]'
            ]);

            // Return the response with user object and tokens if insert success
            $response = [
                'success' => true,
                'data' => [
                    'user' => $user,
                    'token' => $user->createToken($request->email)->plainTextToken
                ],
                'message' => 'Register Success'
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {
            // Catch the exception to handle error
            $response = [
                'success' => false,
                'message' => 'Something went wrong'
            ];
            return response()->json($response, 503);
        }
    }
}
