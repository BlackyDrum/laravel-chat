<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:512',
        ]);

        Message::query()->create([
            'message' => $request->input('message'),
            'creator_id' => Auth::id()
        ]);

        event(new MessageSent());
    }
}
