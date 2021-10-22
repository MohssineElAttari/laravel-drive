<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function redirectToGoogleProvider()
    {
        $parameters = [
            'access_type' => 'offline',
            'approval_prompt' => 'force'
        ];
        return Socialite::driver('google')->scopes(["https://www.googleapis.com/auth/drive"])->with($parameters)->redirect();
    }

    public function handleProviderGoogleCallback()
    {

        $auth_user = Socialite::driver('google')->user();
        $data = [
            'token' => $auth_user->token,
            'expires_in' => $auth_user->expiresIn,
            'name' => $auth_user->name
        ];
        if($auth_user->refreshToken){
            $data['refresh_token'] = $auth_user->refreshToken;
        }
 
        $user = User::updateOrCreate(
            [
                'email' => $auth_user->email
            ],
            $data
        );
 
        Auth::login($user, true);
        return redirect()->to('/'); // Redirect to a secure page
    }
}
