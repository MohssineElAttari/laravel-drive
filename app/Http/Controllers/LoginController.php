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
        $user = User::updateOrCreate(['email' => $auth_user->email], ['refresh_token' => $auth_user->token, 'name' => $auth_user->name]);
        Auth::login($user, true);
        return redirect()->to('/'); // Redirect to a secure page
    }
}
