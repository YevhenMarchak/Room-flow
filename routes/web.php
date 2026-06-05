<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Models\Reservation;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\UserController;
use App\Models\Schedule;
use App\Http\Controllers\ScheduleController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/{role}/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

});

Route::middleware(['auth', 'role:teacher'])->group(function () {

    Route::get(
        '/teacher/reservations',
        [ReservationController::class, 'teacherIndex']
    );

    Route::get(
        '/teacher/reserve-room',
        [ReservationController::class, 'searchForm']
    );

    Route::post('/teacher/reserve-room', function (\Illuminate\Http\Request $request) {

        Reservation::create([

            'room_id' => $request->room_id,
            'user_id' => auth()->id(),

            'date' => $request->date,

            'start_time' => $request->start_time,
            'end_time' => $request->end_time,

            'status' => 'active',
        ]);

        return redirect()->back();

    });

    Route::get('/teacher/schedule', function () {
        return view('teacher.schedule');
    })->middleware(['auth', 'role:teacher']);

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

});

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::middleware(['auth', 'role:admin'])->group(function () {

        Route::get('/admin/rooms', [RoomController::class, 'index']);

        Route::post('/rooms', [RoomController::class, 'store']);

        Route::put('/rooms/{id}', [RoomController::class, 'update']);

        Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

    });

    Route::middleware(['auth', 'role:admin'])->group(function () {

        Route::get('/admin/users', [UserController::class, 'index']);

        Route::post('/users', [UserController::class, 'store']);

        Route::put('/users/{id}', [UserController::class, 'update']);

        Route::delete('/users/{id}', [UserController::class, 'destroy']);

    });

    Route::get(
        '/admin/reservations',
        [ReservationController::class, 'adminIndex']
    );

    Route::post(
        '/admin/reservations/{id}/reject',
        [ReservationController::class, 'reject']
    );

    Route::post('/admin/schedules', [ScheduleController::class, 'store']);
    Route::put('/admin/schedules/{schedule}', [ScheduleController::class, 'update']);
    Route::delete('/admin/schedules/{schedule}', [ScheduleController::class, 'destroy']);

});

require __DIR__.'/auth.php';
