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
        
        $validatation = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        if($validatation->fails()) {
            $response = [
                'success' => false,
                'message' => $validatation->messages()
            ];
            return response()->json($response, 503);
        }

        try {
            $user = User::where('email', $request->email)->first();
            $password = Hash::check($request->password, $user->password ?? '');
            if(!$user || !$password) {
                $response = [
                    'success' => false,
                    'message' => 'Login Invalid'
                ];
                return response()->json($response, 200);
            }

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
            $response = [
                'success' => false,
                'message' => 'Something went wrong'
            ];
            return response()->json($response, 503);
        }
        
    }

    public function register(Request $request) {
        $validatation = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        if($validatation->fails()) {
            $response = [
                'success' => false,
                'message' => $validatation->messages()
            ];
            return response()->json($response, 503);
        }

        try {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'followed_persons' => '[]',
                'followed_pages' => '[]'
            ]);

            $response = [
                'success' => true,
                'data' => [
                    $user,
                    'token' => $user->createToken($request->email)->plainTextToken
                ],
                'message' => 'Register Success'
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Something went wrong'
            ];
            return response()->json($response, 503);
        }
    }
}
