<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Passport\Client;

class LoginController extends Controller
{

    public function __invoke(Request $request)
    {
        $credentials = $this->validate($request, [
            'password' => ['required'],
            'email' => ['required', 'email'],
        ]);
        if (auth()->attempt($credentials)) {
            $tokenRequest = Request::create(
                'oauth/token',
                'POST'
            );
            $client = Client::query()->where('password_client', 1)->first();
            $tokenRequest->request->add([
                'grant_type' => 'password',
                "username" => $credentials['email'],
                "password" => $credentials['password'],
                "client_id" => $client->id,
                "client_secret" => $client->secret,
                'scope' => '',
            ]);
            $response = app()->handle($tokenRequest);
            $jwt = json_decode($response->getContent());

            $user = User::query()
                ->where('email', $credentials['email'])
                ->first();

            return response()->json([
                'token_type' => $jwt->token_type,
                'expires_in' => $jwt->expires_in,
                'access_token' => $jwt->access_token,
                'refresh_token' => $jwt->refresh_token,
                'user' => new UserResource($user)
            ]);
        } else {
            return response()->json(['errors' => [
                'password' => ['Podane hasło lub adres email są nieprawidłowe.']
            ]], 422);
        }
    }
}
