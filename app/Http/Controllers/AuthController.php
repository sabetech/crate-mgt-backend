<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email'=> 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name'=> $fields['name'],
            'email'=> $fields['email'],
            'password'=> bcrypt($fields['password'])
        ]);

        $token = $user->createToken('opktoken')->plainTextToken;
        $response = [
            'user'=> $user,
            'token'=> $token
        ];

        return response($response, 201);
    }

    public function logout() {
        
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $user->tokens()->delete();

        return [
            'message'=> 'Logged out'
        ];
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email'=> 'required|string',
            'password' => 'required|string'
        ]);

        //check emaail .. 
        $user = User::where('email', $fields['email'])->first();
        
        //check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message'=> 'Incorrect Credentials'
            ], 401);
        }

        $token = $user->createToken('opktoken')->plainTextToken;
        $response = [
            'user'=> $user,
            'token'=> $token,
            'expires_at'=> 60 * 6,
            'token_type'=> 'Bearer'
        ];

        return response($response, 201);
    }
}
