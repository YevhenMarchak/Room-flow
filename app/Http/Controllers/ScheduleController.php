<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'room_id' => 'required|exists:rooms,id',
            'subject' => 'required|string|max:255',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
        ]);

        Schedule::create([
            'user_id' => $request->user_id,
            'room_id' => $request->room_id,
            'subject' => $request->subject,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back()->with('success', 'Class added.');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $schedule->update([
            'room_id' => $request->room_id,
            'subject' => $request->subject,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return back();
    }

    public function destroy($id)
    {
        Schedule::findOrFail($id)->delete();

        return back();
    }
}