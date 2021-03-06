<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class LoginControllerAPI extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
            $response = $http->post(env('YOUR_SERVER_URL', '').'/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => env('CLIENT_ID', ''),
                    'client_secret' => env('CLIENT_SECRET', ''),
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => ''
                ],
                'exceptions' => false,
            ]);
        $errorCode= $response->getStatusCode();
        if ($errorCode=='200') {
            $token = json_decode((string) $response->getBody(), true)['access_token'];
            $user = User::where('email', '=', $request->email)->firstOrFail();
            $user->messaging_token = $request->messaging_token;
            $user->save();
            $user->token = $token;
            return new UserResource($user);
        } else {
            return response()->json(['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function fbLogin(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(env('YOUR_SERVER_URL', '').'/oauth/token', [
            'form_params' => [
                'grant_type' => 'social',
                'client_id' => env('CLIENT_ID', ''),
                'client_secret' => env('CLIENT_SECRET', ''),
                'provider' => 'facebook',
                'access_token' => $request->access_token,
            ],
            'exceptions' => false,
        ]);
        $errorCode= $response->getStatusCode();
        if ($errorCode=='200') {
            $token = json_decode((string) $response->getBody(), true)['access_token'];
            $user = User::where('email', '=', $request->email)->firstOrFail();
            $user->local = $request->local;
            $user->messaging_token = $request->messaging_token;
            $user->save();
            $user->token = $token;
            return new UserResource($user);
        } else {
            return response()->json(['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function logout()
    {
        Auth::user()->messaging_token = "";
        Auth::user()->save();
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Logged out'], 200);
    }
}
