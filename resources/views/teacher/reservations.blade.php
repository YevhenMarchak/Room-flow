<x-app-layout>
    <x-slot name="header">
        Reservations
    </x-slot>

    <div class="mb-6">

        <form method="GET" class="flex gap-4 items-center">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search room..."
                class="border rounded-xl px-4 py-2 w-72"
            >

            <select
                name="status"
                class="border rounded-xl px-4 py-2 min-w-[180px]"
            >

                <option value="">
                    All statuses
                </option>

                <option
                    value="active"
                    @selected(request('status') == 'active')
                >
                    Active
                </option>

                <option
                    value="finished"
                    @selected(request('status') == 'finished')
                >
                    Finished
                </option>

                <option
                    value="rejected"
                    @selected(request('status') == 'rejected')
                >
                    Rejected
                </option>

            </select>

            <button
                class="bg-blue-600 text-white px-5 py-2 rounded-xl"
            >
                Search
            </button>

        </form>

    </div>

    <div class="space-y-6">

        @foreach($reservations as $reservation)

            <div class="bg-white rounded-2xl shadow p-6">

                <div class="flex justify-between items-start">

                    <div>

                        <h2 class="text-2xl font-bold mb-2">
                            Room {{ $reservation->room->number }}
                        </h2>

                        <p>
                            <strong>Date:</strong>
                            {{ $reservation->date }}
                        </p>

                        <p>
                            <strong>Time:</strong>
                            {{ $reservation->start_time }}
                            -
                            {{ $reservation->end_time }}
                        </p>

                    </div>

                    <div>

                        @if($reservation->status === 'active')

                            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-semibold">
                                Active
                            </span>

                        @elseif($reservation->status === 'rejected')

                            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-semibold">
                                Rejected
                            </span>

                        @elseif($reservation->status === 'finished')

                            <span class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm font-semibold">
                                Finished
                            </span>

                        @endif

                    </div>

                </div>

                @if($reservation->status === 'rejected')

                    <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-4">

                        <p class="font-semibold text-red-700 mb-1">
                            Rejection reason:
                        </p>

                        <p class="text-red-600">
                            {{ $reservation->rejection_reason }}
                        </p>

                    </div>

                @endif

            </div>

        @endforeach

    </div>

</x-app-layout>