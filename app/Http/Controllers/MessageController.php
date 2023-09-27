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
        if (Auth::user()->muted)
            abort(403, "Muted");

        $request->validate([
            'message' => 'required|string|max:512',
            'room_id' => 'required|integer|exists:rooms,id'
        ],[
            'room_id.*' => "Please join or create a room"
        ]);

        if ($request->input('message')[0] == '/' && Auth::user()->admin)
        {
            $parts = explode(" ", $request->input('message'));

            if (count($parts) != 2) {
                abort(422, "Invalid command format");
            }

            $command = $parts[0];
            $parameter = $parts[1];

            $message = "";

            switch ($command)
            {
                case '/ban':
                    try
                    {
                        User::query()->findOrFail((int)$parameter)->update([
                            'banned' => true,
                        ]);
                        $message = "User with ID {$parameter} successfully banned";
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
                        $message = "User with ID {$parameter} successfully unbanned";
                    }
                    catch (ModelNotFoundException $exception)
                    {
                        abort(404, "User not found");
                    }
                    break;
                case '/mute':
                    try
                    {
                        User::query()->findOrFail((int)$parameter)->update([
                            'muted' => true,
                        ]);
                        $message = "User with ID {$parameter} successfully muted";
                    }
                    catch (ModelNotFoundException $exception)
                    {
                        abort(404, "User not found");
                    }
                    break;
                case '/unmute':
                    try
                    {
                        User::query()->findOrFail((int)$parameter)->update([
                            'muted' => false,
                        ]);
                        $message = "User with ID {$parameter} successfully unmuted";
                    }
                    catch (ModelNotFoundException $exception)
                    {
                        abort(404, "User not found");
                    }
                    break;
                default:
                    abort(404, "Unknown command");
            }

            return response()->json(['message' => $message]);
        }

        $execute = RateLimiter::attempt(
            'send-message'.Auth::id(),
            10,
            function() use ($request) {
                Message::query()->create([
                    'message' => $request->input('message'),
                    'creator_id' => Auth::id(),
                    'room_id' => $request->input('room_id'),
                ]);

                event(new MessageSent($request->input('room_id')));
            },
            10
        );

        if (!$execute)
        {
            abort(429, "Too many messages sent. Try again in ". RateLimiter::availableIn('send-message'.Auth::id())." seconds");
        }

        return to_route('home');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'bail|required|integer|exists:messages,id'
        ]);

        Message::query()->find($request->input('id'))->delete();
    }
}
