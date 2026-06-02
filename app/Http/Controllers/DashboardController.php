<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Reservation;
use App\Models\Schedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $today = Carbon::today();

        $currentTime = $now->format('H:i:s');
        $currentDay = $now->format('l');

        /*
        |--------------------------------------------------------------------------
        | Active reservations
        |--------------------------------------------------------------------------
        */

        $activeReservations = Reservation::with([
            'room',
            'user'
        ])
        ->whereDate('date', $today)
        ->where('status', 'active')
        ->where('start_time', '<=', $currentTime)
        ->where('end_time', '>=', $currentTime)
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Active classes (schedule)
        |--------------------------------------------------------------------------
        */

        $activeSchedules = Schedule::with([
            'room',
            'user'
        ])
        ->where('day_of_week', $currentDay)
        ->where('start_time', '<=', $currentTime)
        ->where('end_time', '>=', $currentTime)
        ->get();

        /*
        |--------------------------------------------------------------------------
        | Occupied rooms
        |--------------------------------------------------------------------------
        */

        $occupiedRoomIds = collect()

            ->merge(
                $activeReservations->pluck('room_id')
            )

            ->merge(
                $activeSchedules->pluck('room_id')
            )

            ->unique();

        $occupiedRooms = $occupiedRoomIds->count();

        /*
        |--------------------------------------------------------------------------
        | Free rooms
        |--------------------------------------------------------------------------
        */

        $freeRooms = Room::count() - $occupiedRooms;

        /*
        |--------------------------------------------------------------------------
        | Active classes count
        |--------------------------------------------------------------------------
        */

        $activeClasses = $activeSchedules->count();

        /*
        |--------------------------------------------------------------------------
        | Reservations today
        |--------------------------------------------------------------------------
        */

        $reservationsToday = Reservation::whereDate(
            'date',
            $today
        )->count();

        return view('dashboard', [
            'freeRooms' => $freeRooms,
            'occupiedRooms' => $occupiedRooms,
            'activeClasses' => $activeClasses,
            'reservationsToday' => $reservationsToday,
            'activeReservations' => $activeReservations,
            'activeSchedules' => $activeSchedules,
        ]);
    }
}