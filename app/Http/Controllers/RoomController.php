<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
{
    $rooms = Room::all();
    return view('admin.rooms', ['rooms' => $rooms]);
}

    public function create()
{
    return view('rooms.create');
}

    public function store(Request $request)
{
    $room = new Room();
    $room->number = $request->number;
    $room->type = $request->type;
    $room->capacity = $request->capacity;
    $room->workstations = $request->workstations;
    $room->equipment = $request->equipment;
    $room->save();

    return redirect('/admin/rooms');
}

    public function edit($id)
{
    $room = Room::find($id);
    return view('rooms.edit', ['room' => $room]);
}

    public function update(Request $request, $id)
{
    $room = Room::find($id);

    $room->number = $request->number;
    $room->type = $request->type;
    $room->capacity = $request->capacity;
    $room->workstations = $request->workstations;
    $room->equipment = $request->equipment;

    $room->save();

    return redirect('/admin/rooms');
}

    public function destroy($id)
{
    $room = Room::find($id);
    $room->delete();

    return redirect('/admin/rooms');

}

}