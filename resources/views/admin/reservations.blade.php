<x-app-layout>

    <x-slot name="header">
        Reservations Management
    </x-slot>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="text-left p-4">
                        Teacher
                    </th>

                    <th class="text-left p-4">
                        Room
                    </th>

                    <th class="text-left p-4">
                        Date
                    </th>

                    <th class="text-left p-4">
                        Time
                    </th>

                    <th class="text-left p-4">
                        Status
                    </th>

                    <th class="text-left p-4">
                        Actions
                    </th>

                </tr>

            </thead>

            <tbody>

                @foreach($reservations as $reservation)

                <tr class="border-t">

                    <td class="p-4">
                        {{ $reservation->user->name }}
                    </td>

                    <td class="p-4">
                        {{ $reservation->room->number }}
                    </td>

                    <td class="p-4">
                        {{ $reservation->date }}
                    </td>

                    <td class="p-4">
                        {{ $reservation->start_time }}
                        -
                        {{ $reservation->end_time }}
                    </td>

                    <td class="p-4">

                        @if($reservation->status === 'active')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                Active
                            </span>

                        @elseif($reservation->status === 'finished')

                            <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full">
                                Finished
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                Rejected
                            </span>

                        @endif

                    </td>

                    <td class="p-4">

                        @if(
                            $reservation->status === 'active'
                            &&
                            \Carbon\Carbon::parse(
                                $reservation->date.' '.$reservation->start_time
                            )->isFuture()
                        )

                            <button
                                onclick="document.getElementById('rejectModal{{ $reservation->id }}').classList.remove('hidden')"
                                class="bg-red-500 text-white px-4 py-2 rounded-lg"
                            >
                                Reject
                            </button>

                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    @foreach($reservations as $reservation)

    <div
        id="rejectModal{{ $reservation->id }}"
        class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50"
    >

        <div class="bg-white rounded-2xl p-8 w-[600px]">

            <h2 class="text-2xl font-bold mb-6">
                Reject Reservation
            </h2>

            <form
                action="/admin/reservations/{{ $reservation->id }}/reject"
                method="POST"
            >

                @csrf

                <textarea
                    name="rejection_reason"
                    rows="5"
                    placeholder="Reason for rejection..."
                    class="border rounded-xl p-3 w-full"
                    required
                ></textarea>

                <div class="flex justify-end gap-3 mt-6">

                    <button
                        type="button"
                        onclick="document.getElementById('rejectModal{{ $reservation->id }}').classList.add('hidden')"
                        class="bg-gray-200 px-5 py-2 rounded-xl"
                    >
                        Cancel
                    </button>

                    <button
                        class="bg-red-600 text-white px-5 py-2 rounded-xl"
                    >
                        Reject Reservation
                    </button>

                </div>

            </form>

        </div>

    </div>

    @endforeach

</x-app-layout>