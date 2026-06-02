<x-app-layout>

    <div class="grid grid-cols-4 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-gray-500 text-sm">Free Rooms</h2>
            <p class="text-3xl font-bold text-green-500 mt-2">
                {{ $freeRooms }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-gray-500 text-sm">Occupied Rooms</h2>
            <p class="text-3xl font-bold text-red-500 mt-2">
                {{ $occupiedRooms }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-gray-500 text-sm">Active Classes</h2>
            <p class="text-3xl font-bold text-blue-500 mt-2">
                {{ $activeClasses }}
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-gray-500 text-sm">Reservations Today</h2>
            <p class="text-3xl font-bold text-yellow-500 mt-2">
                {{ $reservationsToday }}
            </p>
        </div>

    </div>

    <div>

        <h2 class="text-2xl font-bold mb-6">
            Live Room Status
        </h2>

        @if(
            $activeSchedules->isEmpty()
            &&
            $activeReservations->isEmpty()
        )

            <div class="bg-white rounded-3xl border border-slate-200 p-16 text-center">

                <h3 class="text-3xl font-bold mb-4">
                    No Rooms Occupied
                </h3>

                <p class="text-slate-500 text-lg">
                    All rooms are currently available.
                </p>

            </div>

        @else

            <div class="grid grid-cols-3 gap-6">

                {{-- ACTIVE CLASSES --}}

                @foreach($activeSchedules as $schedule)

                    <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-blue-500">

                        <h3 class="text-3xl font-bold mb-4">
                            {{ $schedule->room->number }}
                        </h3>

                        <p class="text-blue-500 font-semibold mb-4">
                            Class In Progress
                        </p>

                        <div class="space-y-2">

                            <p>
                                <strong>Subject:</strong>
                                {{ $schedule->subject }}
                            </p>

                            <p>
                                <strong>Teacher:</strong>
                                {{ $schedule->user->name }}
                            </p>

                            <p>
                                <strong>Time:</strong>
                                {{ substr($schedule->start_time,0,5) }}
                                -
                                {{ substr($schedule->end_time,0,5) }}
                            </p>

                        </div>

                    </div>

                @endforeach

                {{-- ACTIVE RESERVATIONS --}}

                @foreach($activeReservations as $reservation)

                    <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-red-500">

                        <h3 class="text-3xl font-bold mb-4">
                            {{ $reservation->room->number }}
                        </h3>

                        <p class="text-red-500 font-semibold mb-4">
                            Reserved
                        </p>

                        <div class="space-y-2">

                            <p>
                                <strong>Teacher:</strong>
                                {{ $reservation->user->name }}
                            </p>

                            <p>
                                <strong>Time:</strong>
                                {{ substr($reservation->start_time,0,5) }}
                                -
                                {{ substr($reservation->end_time,0,5) }}
                            </p>

                        </div>

                    </div>

                @endforeach

            </div>

        @endif

    </div>

</x-app-layout>