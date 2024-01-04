<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index() 
    {
        if (Auth::user()->role_id == 1) {
            // List All User
            $users = User::all();
            return UserResource::collection($users);
        }
        else {
            abort(403);
        }
    }

    public function show($id) 
    {
        if (Auth::user()->role_id == 1 || Auth::user()->id == $id) {
            // Show Detail User
            $user = User::findOrFail($id);
            return new UserResource($user);
        }
        else {
            abort(403);
        }
    }

    public function store(Request $request) 
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'username' => 'required|unique:users,username',
            'password' => 'required',
            'fullname' => 'required',
            'avatar' => 'nullable|mimes:jpg,jpeg,bmp,png',
        ]);

        if (Auth::user()->role_id == 1) {
            // Preprocess
            $user_data = $request->all();
            if ($request->avatar) {
                $filename = $this->generateRandomString();
                $extension = $request->avatar->extension();
                
                Storage::putFileAs('avatar', $request->avatar, $filename.'.'.$extension);
                $user_data['avatar'] = $filename.'.'.$extension;
            }
            $user_data['password'] = Hash::make($request->password);

            // Create User
            User::create($user_data);
            return response()->json(['message' => 'User Created']);
        }
        else {
            abort(403);
        }
    }

    public function update(Request $request, $id) 
    {
        $request->validate([
            'email' => 'nullable|email|unique:users,email,'.$id,
            'username' => 'nullable|unique:users,username,'.$id,
        ]);

        if (Auth::user()->role_id == 1 || Auth::user()->id == $id) {
            // Preprocess
            $user_data = $request->all();
            if ($request->avatar) {
                $filename = $this->generateRandomString();
                $extension = $request->avatar->extension();
                
                Storage::putFileAs('avatar', $request->avatar, $filename.'.'.$extension);
                $user_data['avatar'] = $filename.'.'.$extension;
            }
            if ($request->password) {
                $user_data['password'] = Hash::make($request->password);
            }

            // Update User
            $user = User::findOrFail($id);
            $user->update(array_filter($user_data));
            return response()->json(['message' => 'User Updated']);
        }
        else {
            abort(403);
        }
    }

    public function destroy($id) 
    {
        if (Auth::user()->role_id == 1) {
            // Delete User
            $user = User::findOrFail($id);
            Storage::delete('avatar/'.$user->avatar);
            $user->delete();
            return response()->json(['message' => 'User Deleted']);
        }
        else {
            abort(403);
        }
    }

    private function generateRandomString($length = 30) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
