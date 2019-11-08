<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserControllerAPI extends Controller
{
    //
    public function store(RegisterUserRequest $request) {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->local = $request->local;
        $user->avatar = ""; //todo change

        /*if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $user->photo_url = str_random(16) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('profiles', $user->photo_url, 'public');
        }
        */

        if ($user->save()){
            return response()->json(["message" => "User created successfully!"], 200);
        }

        return response()->json(["message" => "User not created!"], 500);
    }

    public function me(){
        return new UserResource(Auth::user());
    }
}
