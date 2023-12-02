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
        $users = User::with(['roles' => function($query) {
            $query->with('permissions');
        }])->get();
        foreach ($users as $user) {
            $user->role = $user->roles->pluck('name')->first();
        }

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
        try{
            $user->save();
            $user->roles;

            return response()->json([
                "success" => true,
                "data" => $user
            ]);
        }catch(\Exception $e){
            return response()->json([
                "success" => false,
                "message" => "User not added"
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $user = User::with(['roles' => function($query) {
            $query->with('permissions');
        }])->find($id);

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
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                "success" => false,
                "message" => "User not found"
            ]);
        }
        $user->name = $request->get("name");
        $user->email = $request->get("email");
        $user->password = bcrypt($request->get("password"));
        $user->syncRoles([]);
        $user->assignRole($request->get("role"));
        $user->save();

        return response()->json([
            "success" => true,
            "data" => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::find($id);
        if (!$user) {
           
            return response()->json([
                "success" => false,
                "message" => "User not found"
            ]);
        
        }
        $user->delete();

        return response()->json([
            "success" => true,
            "message" => "User has been deleted!"
        ]);
    }

    public function getRoles() {
        $roles = \Spatie\Permission\Models\Role::all();
        return response()->json([
            "success" => true,
            "data" => $roles
        ]);
    }
}
