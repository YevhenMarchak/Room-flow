<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Room;

class UserController extends Controller
{
    public function index()
        {
            $users = User::with([
        'reservations',
        'schedules.room'
        ])
        ->where('role', 'teacher')
        ->get();

        $rooms = Room::all();

        return view('admin.users', compact(
            'users',
            'rooms'
        ));

    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect('/admin/users');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (
            $user->id === auth()->id() &&
            $request->role !== 'admin'
        ) {
            return redirect('/admin/users')
                ->with('error', 'You cannot remove your own admin role.');
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/admin/users')
            ->with('success', 'User updated.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect('/admin/users')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect('/admin/users')
            ->with('success', 'User deleted.');
    }
    
}