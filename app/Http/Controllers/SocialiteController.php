<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function github()
    {
        $githubUser = Socialite::driver('github')->stateless()->user();
        $user = User::query()->updateOrCreate([
            'email' => $githubUser->email,
        ],[
            'name' => $githubUser->nickname ?: $githubUser->name,
            'password' => Hash::make(Str::random()),
        ]);

        Auth::login($user);

        return to_route('home');
    }

    public function google()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::query()->updateOrCreate([
            'email' => $googleUser->email,
        ],[
            'name' => $googleUser->nickname ?: $googleUser->name,
            'password' => Hash::make(Str::random()),
        ]);

        Auth::login($user);

        return to_route('home');
    }
}
