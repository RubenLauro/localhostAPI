<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
    use App\User;
    use Response;
   
//local
/*
define('YOUR_SERVER_URL', 'http://localhost.me');
// Check "oauth_clients" table for next 2 values:
define('CLIENT_ID', '2');
define('CLIENT_SECRET', 'dEkJVQsTdJHqB4lZGSmOwb5zhNfXiK47s5b07kFi');
*/

//server
define('YOUR_SERVER_URL', 'http://178.62.5.112');
// Check "oauth_clients" table for next 2 values:
define('CLIENT_ID', '4');
define('CLIENT_SECRET', 'SdnIlMsu2ilSbShgSglT1JJBXMvf4LGDkCYo1CCf');

class LoginControllerAPI extends Controller
{
    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
            $response = $http->post(YOUR_SERVER_URL.'/oauth/token', [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => CLIENT_ID,
                    'client_secret' => CLIENT_SECRET,
                    'username' => $request->email,
                    'password' => $request->password,
                    'scope' => ''
                ],
                'exceptions' => false,
            ]);
        $errorCode= $response->getStatusCode();
        if ($errorCode=='200') {
            $user = User::where('email', '=', $request->email)->firstOrFail();
            $user = collect($user);
            $user ->put('token',json_decode( $response->getBody(), true)['access_token']);
            return Response::json(array($user));
            //return json_decode((string) $response->getBody(), true);
        } else {
            return response()->json(['msg'=>'User credentials are invalid'], $errorCode);
        }
    }

    public function logout()
    {
        \Auth::guard('api')->user()->token()->revoke();
        \Auth::guard('api')->user()->token()->delete();
        return response()->json(['msg'=>'Logged out'], 200);
    }
}
