<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">

        <div class="flex min-h-screen">

            <!-- Sidebar -->
            <aside class="w-64 bg-gray-900 text-white flex flex-col">

                <div class="p-6 text-2xl font-bold border-b border-gray-700">
                    RoomFlow
                </div>

                <nav class="flex-1 p-4 space-y-2">

                    <nav class="flex-1 p-4 space-y-2">

                    <a href="/{{ auth()->user()->role }}/dashboard"
                        class="block px-6 py-3 rounded-xl transition
                        {{ request()->is('admin/dashboard') || request()->is('teacher/dashboard')
                            ? 'bg-white/10 text-white font-semibold'
                            : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                        Dashboard
                    </a>

                    @if(auth()->user()->role === 'admin')

                        <a href="/{{ auth()->user()->role }}/rooms"
                        class="block px-6 py-3 rounded-xl transition
                        {{ request()->is('admin/rooms') ? 'bg-white/10 text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                            Rooms
                        </a>

                        <a href="/{{ auth()->user()->role }}/users"
                        class="block px-6 py-3 rounded-xl transition
                        {{ request()->is('admin/users') ? 'bg-white/10 text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                            Users
                        </a>

                        <a href="/{{ auth()->user()->role }}/reservations"
                        class="block px-6 py-3 rounded-xl transition
                        {{ request()->is('admin/reservations') ? 'bg-white/10 text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                            Reservations
                        </a>

                    @endif


                    @if(auth()->user()->role === 'teacher')

                        <a href="/teacher/reservations"
                        class="block px-6 py-3 rounded-xl transition
                        {{ request()->is('teacher/reservations') ? 'bg-white/10 text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                            My Reservations
                        </a>

                        <a href="/teacher/reserve-room"
                            class="block px-6 py-3 rounded-xl transition
                            {{ request()->is('teacher/reserve-room') ? 'bg-white/10 text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                                Reserve Room
                        </a>

                        <a href="/teacher/schedule"
                            class="block px-6 py-3 rounded-xl transition
                            {{ request()->is('teacher/schedule') ? 'bg-white/10 text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                                Schedule
                        </a>
                        <a href="/profile"
                        class="block px-6 py-3 rounded-xl transition
                        {{ request()->is('profile') ? 'bg-white/10 text-white font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}">
                            Profile
                        </a>

                    @endif

                </nav>

                </nav>

                <div class="p-4 border-t border-gray-700">

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                                class="w-full bg-red-500 hover:bg-red-600 transition px-4 py-2 rounded-lg">
                            Logout
                        </button>
                    </form>

                </div>

            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col">

                <!-- Header -->
                <header class="bg-white shadow px-8 py-4 flex justify-between items-center">

                    <h1 class="text-2xl font-bold text-gray-800">
                        {{ $header ?? 'Dashboard' }}
                    </h1>

                    <div class="text-gray-600">
                        {{ auth()->user()->name }}
                    </div>

                </header>

                <!-- Page Content -->
                <main class="flex-1 p-8">
                    {{ $slot }}
                </main>

            </div>

        </div>

    </body>
</html>
