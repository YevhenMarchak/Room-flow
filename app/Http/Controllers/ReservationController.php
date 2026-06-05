<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Support\Facades\Validator;
use App\Models\Schedule;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('room', 'user')->get();
        return view('reservations.index', ['reservations' => $reservations]);
    }

    public function create()
    {
        $rooms = Room::all();
        return view('reservations.create', ['rooms' => $rooms]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'room_id' => 'required|exists:rooms,id',

            'date' => 'required|date',

            'start_time' => 'required',

            'end_time' => 'required|after:start_time',

        ]);

        if ($validator->fails()) {

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $reservationStart = Carbon::parse(
            $request->date . ' ' . $request->start_time
        );

        if ($reservationStart->isPast()) {

            return redirect()->back()
                ->with(
                    'error',
                    'Cannot create reservation in the past.'
                )
                ->withInput();
        }

        $start = Carbon::parse($request->start_time);
        $end = Carbon::parse($request->end_time);

        if ($start->diffInMinutes($end) < 15) {

            return redirect()->back()
                ->with(
                    'error',
                    'Minimum reservation length is 15 minutes.'
                )
                ->withInput();
        }

        $conflict = Reservation::where(
                'room_id',
                $request->room_id
            )
            ->where(
                'date',
                $request->date
            )
            ->where(function ($query) use ($request) {

                $query->where(
                    'start_time',
                    '<',
                    $request->end_time
                )
                ->where(
                    'end_time',
                    '>',
                    $request->start_time
                );

            })
            ->exists();

        $dayOfWeek = Carbon::parse(
            $request->date
        )->format('l');

        $scheduleConflict = Schedule::where(
                'room_id',
                $request->room_id
            )
            ->where(
                'day_of_week',
                $dayOfWeek
            )
            ->get()
            ->contains(function ($schedule) use ($request) {

                $scheduleStart =
                    Carbon::parse($schedule->start_time);

                $scheduleEnd =
                    Carbon::parse($schedule->end_time)
                        ->addMinutes(15);

                $requestStart =
                    Carbon::parse($request->start_time);

                $requestEnd =
                    Carbon::parse($request->end_time);

                return
                    $scheduleStart < $requestEnd
                    &&
                    $scheduleEnd > $requestStart;
            });

        if ($conflict || $scheduleConflict) {

            return redirect()->back()
                ->with(
                    'error',
                    'Room is already occupied during this time.'
                )
                ->withInput();
        }

        Reservation::create([

            'room_id' => $request->room_id,

            'user_id' => auth()->id(),

            'date' => $request->date,

            'start_time' => $request->start_time,

            'end_time' => $request->end_time,

            'status' => 'active',

        ]);

        return redirect('/teacher/reservations')
            ->with(
                'success',
                'Reservation created successfully.'
            );
    }

    public function reject(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if (
            $reservation->status !== 'active'
        ) {
            return back();
        }

        if (
            \Carbon\Carbon::parse(
                $reservation->date.' '.$reservation->start_time
            )->isPast()
        ) {
            return back();
        }

        $reservation->status = 'rejected';

        $reservation->rejection_reason =
            $request->rejection_reason;

        $reservation->save();

        return redirect('/admin/reservations');
    }

    public function adminIndex(Request $request)
    {
        $query = Reservation::with([
            'room',
            'user'
        ]);

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('user', function ($userQuery) use ($search) {

                    $userQuery->where(
                        'name',
                        'like',
                        "%{$search}%"
                    );

                })

                ->orWhereHas('room', function ($roomQuery) use ($search) {

                    $roomQuery->where(
                        'number',
                        'like',
                        "%{$search}%"
                    );

                });

            });

        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }

        if ($request->filled('from_date')) {

            $query->whereDate(
                'date',
                '>=',
                $request->from_date
            );

        }

        if ($request->filled('to_date')) {

            $query->whereDate(
                'date',
                '<=',
                $request->to_date
            );

        }

        $reservations = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.reservations',
            compact('reservations')
        );
    }

    public function teacherIndex(Request $request)
    {
        $query = auth()
            ->user()
            ->reservations()
            ->with('room');

        if ($request->filled('search')) {

            $query->whereHas('room', function ($q) use ($request) {

                $q->where(
                    'number',
                    'like',
                    '%' . $request->search . '%'
                );

            });

        }

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );

        }

        $reservations = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();
        return view(
            'teacher.reservations',
            compact('reservations')
        );
    }

    public function searchForm(Request $request)
    {
        $rooms = collect();

        if (
            $request->filled('date')
            &&
            $request->filled('start_time')
            &&
            $request->filled('end_time')
        ) {
            $request->validate([
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'capacity' => 'nullable|integer|min:1',
            ]);

            $dayOfWeek = Carbon::parse(
                $request->date
            )->format('l');

            $query = Room::query();

            if ($request->filled('capacity')) {

                $query->where(
                    'capacity',
                    '>=',
                    $request->capacity
                );
            }
            
            $searchDateTime = Carbon::parse(
                $request->date . ' ' . $request->start_time
            );

            if ($searchDateTime->isPast()) {

                return back()
                    ->with(
                        'error',
                        'Cannot search rooms in the past.'
                    )
                    ->withInput();
            }

            $start = Carbon::parse($request->start_time);
            $end = Carbon::parse($request->end_time);

            $rooms = $query->get()->filter(function ($room) use ($request, $dayOfWeek) {

                $reservationConflict = Reservation::where(
                        'room_id',
                        $room->id
                    )
                    ->where(
                        'date',
                        $request->date
                    )
                    ->whereIn('status', [
                        'active'
                    ])
                    ->where(function ($query) use ($request) {

                        $query->where(
                            'start_time',
                            '<',
                            $request->end_time
                        )
                        ->where(
                            'end_time',
                            '>',
                            $request->start_time
                        );

                    })
                    ->exists();

                $scheduleConflict = Schedule::where(
                        'room_id',
                        $room->id
                    )
                    ->where(
                        'day_of_week',
                        $dayOfWeek
                    )
                    ->whereDate(
                        'start_date',
                        '<=',
                        $request->date
                    )
                    ->whereDate(
                        'end_date',
                        '>=',
                        $request->date
                    )
                    ->get()
                    ->contains(function ($schedule) use ($request) {

                        $scheduleStart =
                            Carbon::parse($schedule->start_time);

                        $scheduleEnd =
                            Carbon::parse($schedule->end_time)
                                ->addMinutes(15);

                        $requestStart =
                            Carbon::parse($request->start_time);

                        $requestEnd =
                            Carbon::parse($request->end_time);

                        return
                            $scheduleStart < $requestEnd
                            &&
                            $scheduleEnd > $requestStart;
                    });

                return
                    !$reservationConflict
                    &&
                    !$scheduleConflict;

            });

        }

        return view(
            'teacher.reserve-room',
            compact('rooms')
        );
    }
}
