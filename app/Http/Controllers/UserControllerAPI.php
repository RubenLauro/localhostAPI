<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetLinkRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\UpdateUserPasswordRequest;
use App\Http\Requests\UploadAvatarRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceWithoutToken;
use App\Notifications\CustomResetPasswordNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

        if ($user->save()){
            return response()->json(["message" => "User created successfully!"], 200);
        }

        return response()->json(["message" => "User not created!"], 500);
    }

    public function me(){
        return new UserResource(Auth::user());
    }

    public function uploadAvatar(UploadAvatarRequest $request){
        $user = Auth::user();
        $file = $request->file('avatar');
        $new_avatar_url = str_random(16) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('profiles', $new_avatar_url, 'public');
        if ($user->avatar != null && $user->avatar != "") {
            Storage::disk('public')->delete('/profiles/' . $user->avatar);
        }
        $user->avatar = $new_avatar_url;

        if ($user->save()) {
            return new UserResourceWithoutToken($user);
        } else {
            return response()->json(["message" => "User could not be updated"], 500);
        }
    }

    public function update(Request $request){
        $user = Auth::user();
        if ($request->has('first_name')){
            $user->first_name = $request->first_name;
        }

        if ($request->has('last_name')) {
            $user->last_name = $request->last_name;
        }

        if ($request->has('local')) {
            $user->local = $request->local;
        }

        if ($user->save()) {
            return new UserResourceWithoutToken(Auth::user());
        } else {
            return response()->json(["message" => "User could not be updated"], 500);
        }
    }

    public function updatePassword(UpdateUserPasswordRequest $request){
        $user = User::where('email', $request->email)->first();

        $user->password = Hash::make($request->password);

        $tokenDeleted = DB::table('password_resets')->where('token', $request->token)->delete();

        if ($user->save() && $tokenDeleted){
            return redirect('/success')->with('message', 'Password updated successfully');
        }

        return redirect('/success')->with('fail', 'Couldn\'t update Password');
    }

    public function resetPasswordSendLink(PasswordResetLinkRequest $request){
        $token = str_random(60);
        $passwordLink = DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email], ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );
        if ($passwordLink){
            $user = User::where('email', $request->email)->first();
            $user->notify(new CustomResetPasswordNotification($token));
            return response()->json(["message" => "Email sent"], 200);
        }
        return response()->json(["message" => "Couldn't send Email"], 500);
    }

    public function getResetPage($token){
        $email = DB::table('password_resets')->where('token', $token)->value('email');
        if ($email == null){
            abort(404);
        }
        return view('auth.passwords.reset', ['token' => $token, 'email' => $email]);
    }

    public function getFavorites(){
        return Auth::user()->favorites()->get();
    }
}
