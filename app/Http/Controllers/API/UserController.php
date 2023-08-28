<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Get all users
        $users = User::with('roles')->get();

        return response()->json([
            "success" => true,
            "data" => $users
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->name = $request->get("name");
        $user->email = $request->get("email");
        $user->password = bcrypt($request->get("password"));
        $user->assignRole($request->get("role"));
        $user->save();

        $user->roles;

        return response()->json([
            "success" => true,
            "data" => $user
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::with('roles')->find($id);
        if ($user) {
            return response()->json([
                "success" => true,
                "data" => $user
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "User not found"
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
