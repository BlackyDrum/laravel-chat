<?php

namespace App\Http\Controllers;

use App\Events\RoomCreatedOrSwitched;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:32',
            'count' => 'required|integer|min:1|max:10',
            'description' => 'nullable|string|max:1024'
        ]);

        Room::query()->create([
            'name' => $request->input('name'),
            'count' => $request->input('count'),
            'description' => $request->input('description'),
            'creator_id' => Auth::id(),
        ]);
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:rooms,id'
        ]);

        $room = Room::query()->find($request->input('id'));
        if (Auth::user()->admin)
            $room->delete();
        else if (Auth::id() == $room->creator_id)
            $room->delete();
        else
            abort(403, "Forbidden");
    }
}
