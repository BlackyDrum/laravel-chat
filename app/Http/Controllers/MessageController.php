<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use const http\Client\Curl\AUTH_ANY;

class MessageController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:512',
        ]);

        $execute = RateLimiter::attempt(
            'send-message'.Auth::id(),
            10,
            function() use ($request) {
                Message::query()->create([
                    'message' => $request->input('message'),
                    'creator_id' => Auth::id()
                ]);

                event(new MessageSent());
            },
            10
        );

        if (!$execute)
        {
            abort(429, "Too many messages sent. Try again in ". RateLimiter::availableIn('send-message'.Auth::id())." seconds");
        }
    }
}
