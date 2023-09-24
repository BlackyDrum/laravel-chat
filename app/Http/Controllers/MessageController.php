<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class MessageController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:512',
        ]);

        if ($request->input('message')[0] == '/' && Auth::user()->admin)
        {
            $parts = explode(" ", $request->input('message'));

            if (count($parts) != 2) {
                abort(422, "Invalid command format");
            }

            $command = $parts[0];
            $parameter = $parts[1];

            switch ($command)
            {
                case '/ban':
                    try
                    {
                        User::query()->findOrFail((int)$parameter)->update([
                            'banned' => true,
                        ]);
                    }
                    catch (ModelNotFoundException $exception)
                    {
                        abort(404, "User not found");
                    }
                    break;
                case '/unban':
                    try
                    {
                        User::query()->findOrFail((int)$parameter)->update([
                            'banned' => false,
                        ]);
                    }
                    catch (ModelNotFoundException $exception)
                    {
                        abort(404, "User not found");
                    }
                    break;
                default:
                    abort(404, "Unknown command");
            }

            return to_route('home');
        }
        else if ($request->input('message')[0] == '/' && !Auth::user()->admin)
        {
            abort(403, "Forbidden");
        }

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

        return to_route('home');
    }
}
