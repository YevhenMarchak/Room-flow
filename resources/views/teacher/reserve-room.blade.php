<x-app-layout>
    @php

    $timeOptions = [];

    for ($hour = 7; $hour <= 20; $hour++) {

        foreach (['00', '15', '30', '45'] as $minute) {

            if ($hour == 20 && $minute != '00') {
                continue;
            }

            $timeOptions[] =
                sprintf('%02d:%s', $hour, $minute);
        }
    }

    @endphp

    @if(session('error'))

    <div class="bg-red-100 border border-red-300 text-red-700 p-4 rounded-xl mb-6">

        {{ session('error') }}

    </div>

    @endif

    @if ($errors->any())

    <div class="bg-red-100 border border-red-300 text-red-700 p-4 rounded-xl mb-6">

        <ul>

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

    @endif


    <x-slot name="header">
        Reserve Room
    </x-slot>

    <form method="GET" class="bg-white rounded-2xl shadow p-6 mb-8">

        <div class="grid grid-cols-4 gap-4">

            <div>
                <label class="block mb-2 font-semibold">
                    Date
                </label>

                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="w-full h-16 border border-slate-400 rounded-2xl px-4 text-lg"
                    required
                >
            </div>

            <div>
                <label class="block mb-2 font-semibold">
                    Start Time
                </label>
                <select
                    name="start_time"
                    class="w-full h-16 border border-slate-400 rounded-2xl px-4 text-lg bg-white"
                    required
                >

                    <option value="">
                        Select Start Time
                    </option>

                    @foreach($timeOptions as $time)

                        <option value="{{ $time }}"
                        @selected(request('start_time') == $time)>
                            {{ $time }}
                        </option>

                    @endforeach

                </select>

            </div>

            <div>
                <label class="block mb-2 font-semibold">
                    End Time
                </label>

                <select
                    name="end_time"
                    class="w-full h-16 border border-slate-400 rounded-2xl px-4 text-lg bg-white"
                    required
                >
                    <option value="">
                        Select End Time
                    </option>

                    @foreach($timeOptions as $time)

                        <option
                            value="{{ $time }}"
                            @selected(request('end_time') == $time)
                        >
                            {{ $time }}
                        </option>

                    @endforeach

                </select>
                
            </div>

            <div>
                <label class="block mb-2 font-semibold">
                    Capacity
                </label>

                <input
                    type="number"
                    name="capacity"
                    value="{{ request('capacity') }}"
                    placeholder="Minimum capacity"
                    class="w-full h-16 border border-slate-400 rounded-2xl px-4 text-lg"
                >
            </div>

        </div>

        <div class="mt-6">
            <button
                type="submit"
                class="bg-blue-600 text-white px-6 py-3 rounded-xl hover:bg-blue-700 transition"
            >
                Find Rooms
            </button>
        </div>

    </form>

    @if(!request()->date || !request()->start_time || !request()->end_time)

        <div class="bg-white rounded-2xl shadow p-12 text-center">

            <h2 class="text-3xl font-bold mb-4">
                Search Available Rooms
            </h2>

            <p class="text-slate-500">
                Select date and time and click
                <strong>Find Rooms</strong>
                to search available rooms.
            </p>

        </div>

    @elseif($rooms->count())

        <h2 class="text-2xl font-bold mb-6">
            Available Rooms
        </h2>

        <div class="grid grid-cols-3 gap-6">

            @foreach($rooms as $room)

                <div
                    onclick="openModal('{{ $room->id }}','{{ $room->number }}')"
                    class="bg-white rounded-2xl shadow p-6 cursor-pointer hover:scale-105 transition"
                >

                    <div class="flex justify-between items-start mb-4">

                        <div>

                            <h3 class="text-2xl font-bold">
                                {{ $room->number }}
                            </h3>

                            <p class="text-green-600 font-semibold">
                                Available
                            </p>

                        </div>

                        <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                            Free
                        </div>

                    </div>

                    <div class="space-y-2">

                        <p>
                            <strong>Capacity:</strong>
                            {{ $room->capacity }}
                        </p>

                        <p>
                            <strong>Equipment:</strong>
                            {{ $room->equipment }}
                        </p>

                        <p>
                            <strong>Type:</strong>
                            {{ $room->type }}
                        </p>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="bg-white rounded-2xl shadow p-12 text-center">

            <h2 class="text-3xl font-bold mb-4 text-red-500">
                No Rooms Available
            </h2>

            <p class="text-slate-500">
                No rooms match the selected date and time.
            </p>

        </div>

    @endif

    <div
        id="reservationModal"
        class="fixed inset-0 bg-black/50 hidden items-center justify-center"
    >

        <div class="bg-white rounded-2xl p-8 w-[500px]">

            <div class="flex justify-between items-center mb-6">

                <h2 class="text-2xl font-bold">
                    Reserve Room
                </h2>

                <button
                    type="button"
                    onclick="closeModal()"
                    class="text-2xl"
                >
                    ×
                </button>

            </div>

            <form
                method="POST"
                action="/teacher/reserve-room"
            >

                @csrf

                <input
                    type="hidden"
                    name="room_id"
                    id="roomIdInput"
                >

                <input
                    type="hidden"
                    name="date"
                    value="{{ request('date') }}"
                >

                <input
                    type="hidden"
                    name="start_time"
                    value="{{ request('start_time') }}"
                >

                <input
                    type="hidden"
                    name="end_time"
                    value="{{ request('end_time') }}"
                >

                <div class="space-y-4 mb-6">

                    <p>
                        <strong>Room:</strong>
                        <span id="modalRoom"></span>
                    </p>

                    <p>
                        <strong>Date:</strong>
                        {{ request('date') }}
                    </p>

                    <p>
                        <strong>Time:</strong>
                        {{ request('start_time') }}
                        -
                        {{ request('end_time') }}
                    </p>

                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition"
                >
                    Reserve Room
                </button>

            </form>

        </div>

    </div>

    <script>

        function openModal(roomId, roomNumber)
        {
            document
                .getElementById('reservationModal')
                .classList.remove('hidden');

            document
                .getElementById('reservationModal')
                .classList.add('flex');

            document
                .getElementById('roomIdInput')
                .value = roomId;

            document
                .getElementById('modalRoom')
                .innerText = roomNumber;
        }

        function closeModal()
        {
            document
                .getElementById('reservationModal')
                .classList.add('hidden');

            document
                .getElementById('reservationModal')
                .classList.remove('flex');
        }

    </script>

</x-app-layout>