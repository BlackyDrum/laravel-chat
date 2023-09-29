<?php

namespace App\Http\Controllers;

use App\Events\RoomCreatedOrSwitched;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use App\Models\UserInRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use const http\Client\Curl\AUTH_ANY;

class HomeController extends Controller
{
    public function show(Request $request)
    {
        $messages = Message::query()
            ->join('users', 'users.id', '=', 'messages.creator_id')
            ->where('users.banned', '=', 'false')
            ->where('messages.room_id', '=', $request->input('id'))
            ->select([
                'messages.id',
                'messages.message',
                'messages.created_at',
                'messages.room_id',
                'users.name',
                'users.muted',
                'users.id AS user_id',
                'users.avatar AS user_avatar',
            ])
            ->orderByDesc('messages.created_at')
            ->limit(50);

        if (!empty($request->input('id')) && Auth::check())
        {
            $room = Room::query()->findOrFail($request->input('id'));
            $userInRoom = User::query()->where('room_id', '=', $request->input('id'))->count();

            if ($room->creator_id != Auth::id() && !Auth::user()->admin &&
                ($room->has_password && empty($request->input('password'))
                    || !empty($request->input('password')) && !Hash::check($request->input('password'),$room->password)))
            {
                return back()->withErrors(['message' => "Invalid Password"]);
            }

            if ($room->count > $userInRoom || Auth::user()->admin)
            {
                User::query()->find(Auth::id())->update([
                    'room_id' => $request->input('id')
                ]);
            }
            else if ($room->id != Auth::user()->room_id)
            {
                return back()->withErrors(['message' => "Too many users in this room"]);
            }
        }

        $countUsersInRoom = [];
        foreach (User::all() as $user)
        {
            if (!array_key_exists($user->room_id, $countUsersInRoom))
            {
                $countUsersInRoom[$user->room_id] = 1;
            }
            else
            {
                $countUsersInRoom[$user->room_id]++;
            }
        }

        return Inertia::render('Home',[
            'messages' => Auth::check() ? array_reverse($messages->get()->toArray()) : [],
            'rooms' => Auth::check() ? Room::all() : [],
            'userInRooms' => Auth::check() ? $countUsersInRoom : [],
        ]);
    }
}
