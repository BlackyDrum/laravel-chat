<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function show()
    {
        $messages = Message::query()
            ->join('users', 'users.id', '=', 'messages.creator_id')
            ->where('users.banned', '=', 'false')
            ->select([
                'messages.message',
                'messages.created_at',
                'users.name',
                'users.id AS user_id',
                'users.avatar AS user_avatar',
            ])
            ->orderByDesc('messages.created_at')
            ->limit(50)
            ->get();

        return Inertia::render('Home',[
            'messages' => Auth::check() ? array_reverse($messages->toArray()) : [],
        ]);
    }
}
