<x-app-layout>

    <div
        x-data="{
            showDeleteModal: false,
            roomNumber: '',
            deleteAction: ''
        }"
    >

    <x-slot name="header">
        Rooms Management
    </x-slot>

    <div class="mb-6">

        <form method="GET" class="flex gap-4 items-center">

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search room number..."
                class="border rounded-xl px-4 py-2 w-72"
            >

            <select
                name="type"
                class="border rounded-xl px-4 py-2 min-w-[180px]"
            >

                <option value="">
                    All types
                </option>

                <option
                    value="Lecture"
                    @selected(request('type') == 'Lecture')
                >
                    Lecture
                </option>

                <option
                    value="Exercise"
                    @selected(request('type') == 'Exercise')
                >
                    Exercise
                </option>

                <option
                    value="Laboratory"
                    @selected(request('type') == 'Laboratory')
                >
                    Laboratory
                </option>

            </select>

            <input
                type="number"
                name="capacity"
                value="{{ request('capacity') }}"
                placeholder="Min capacity"
                class="border rounded-xl px-4 py-2 w-40"
            >

            <button
                class="bg-blue-600 text-white px-5 py-2 rounded-xl"
            >
                Search
            </button>

        </form>

    </div>

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Rooms
        </h2>

        <button
            onclick="document.getElementById('createModal').classList.remove('hidden')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold"
        >
            + Add Room
        </button>

    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <table class="w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="p-4 text-left">Number</th>
                    <th class="p-4 text-left">Type</th>
                    <th class="p-4 text-left">Capacity</th>
                    <th class="p-4 text-left">Workstations</th>
                    <th class="p-4 text-left">Equipment</th>
                    <th class="p-4 text-left">Actions</th>

                </tr>

            </thead>

            <tbody>

                @foreach($rooms as $room)

                    <tr class="border-t">

                        <td class="p-4">
                            {{ $room->number }}
                        </td>

                        <td class="p-4">
                            {{ $room->type }}
                        </td>

                        <td class="p-4">
                            {{ $room->capacity }}
                        </td>

                        <td class="p-4">
                            {{ $room->workstations ?? '-' }}
                        </td>

                        <td class="p-4">
                            {{ $room->equipment ?? '-' }}
                        </td>

                        <td class="p-4 flex gap-2">

                            <button
                                onclick="document.getElementById('editModal{{ $room->id }}').classList.remove('hidden')"
                                class="bg-yellow-500 text-white px-4 py-2 rounded-lg"
                            >
                                Edit
                            </button>

                            <form
                                action="/admin/rooms/{{ $room->id }}"
                                method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Delete room {{ $room->number }}?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="button"
                                    @click="
                                        showDeleteModal = true;
                                        roomNumber = '{{ $room->number }}';
                                        deleteAction = '/rooms/{{ $room->id }}';
                                    "
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700"
                                >
                                    Delete
                                </button>
                            </form>

                        </td>

                    </tr>

                    <div
                        id="editModal{{ $room->id }}"
                        class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50"
                    >

                        <div class="bg-white rounded-2xl p-8 w-[600px]">

                            <h2 class="text-2xl font-bold mb-6">
                                Edit Room
                            </h2>

                            <form action="/rooms/{{ $room->id }}" method="POST">

                                @csrf
                                @method('PUT')

                                <div class="grid grid-cols-2 gap-4">

                                    <input
                                        type="text"
                                        name="number"
                                        value="{{ $room->number }}"
                                        class="border rounded-xl p-3"
                                        required
                                    >

                                    <select
                                        name="type"
                                        class="border rounded-xl p-3"
                                        required
                                    >
                                        <option {{ $room->type == 'Lecture' ? 'selected' : '' }}>
                                            Lecture
                                        </option>

                                        <option {{ $room->type == 'Laboratory' ? 'selected' : '' }}>
                                            Laboratory
                                        </option>

                                        <option {{ $room->type == 'Exercise' ? 'selected' : '' }}>
                                            Exercise
                                        </option>
                                    </select>

                                    <input
                                        type="number"
                                        name="capacity"
                                        value="{{ $room->capacity }}"
                                        class="border rounded-xl p-3"
                                        required
                                    >

                                    <input
                                        type="number"
                                        name="workstations"
                                        value="{{ $room->workstations }}"
                                        class="border rounded-xl p-3"
                                    >

                                </div>

                                <textarea
                                    name="equipment"
                                    class="border rounded-xl p-3 w-full mt-4"
                                >{{ $room->equipment }}</textarea>

                                <div class="flex justify-end gap-3 mt-6">

                                    <button
                                        type="button"
                                        onclick="document.getElementById('editModal{{ $room->id }}').classList.add('hidden')"
                                        class="px-4 py-2 rounded-xl bg-gray-200"
                                    >
                                        Cancel
                                    </button>

                                    <button
                                        class="bg-blue-600 text-white px-5 py-2 rounded-xl"
                                    >
                                        Save
                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                @endforeach

            </tbody>

        </table>

    </div>


    {{-- CREATE ROOM MODAL --}}

    <div
        id="createModal"
        class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50"
    >

        <div class="bg-white rounded-2xl p-8 w-[600px]">

            <h2 class="text-2xl font-bold mb-6">
                Add Room
            </h2>

            <form action="/rooms" method="POST">

                @csrf

                <div class="grid grid-cols-2 gap-4">

                    <input
                        type="text"
                        name="number"
                        placeholder="Room Number"
                        class="border rounded-xl p-3"
                        required
                    >

                    <select
                        name="type"
                        class="border rounded-xl px-4 py-3 w-full bg-white text-slate-600 appearance-none"
                        required
                    >

                        <option value="">
                            Room type
                        </option>

                        <option value="Lecture">
                            Lecture
                        </option>

                        <option value="Laboratory">
                            Laboratory
                        </option>

                        <option value="Exercise">
                            Exercise
                        </option>

                    </select>

                    <input
                        type="number"
                        name="capacity"
                        placeholder="Capacity"
                        class="border rounded-xl p-3"
                        required
                    >

                    <input
                        type="number"
                        name="workstations"
                        placeholder="Workstations"
                        class="border rounded-xl p-3"
                    >

                </div>

                <textarea
                    name="equipment"
                    placeholder="Equipment"
                    class="border rounded-xl p-3 w-full mt-4"
                ></textarea>

                <div class="flex justify-end gap-3 mt-6">

                    <button
                        type="button"
                        onclick="document.getElementById('createModal').classList.add('hidden')"
                        class="px-4 py-2 rounded-xl bg-gray-200"
                    >
                        Cancel
                    </button>

                    <button
                        class="bg-blue-600 text-white px-5 py-2 rounded-xl"
                    >
                        Save
                    </button>

                </div>

            </form>

        </div>

    </div>

    <div
        x-show="showDeleteModal"
        x-transition
        @click.self="showDeleteModal = false"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        x-cloak
    >

        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6"
        >

            <h2 class="text-2xl font-bold text-gray-900 mb-3">
                Delete Room
            </h2>

            <p class="text-gray-600 mb-6">
                Are you sure you want to permanently delete room
                <span
                    class="font-semibold text-gray-900"
                    x-text="roomNumber"
                ></span>?
            </p>

            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    @click="showDeleteModal = false"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition"
                >
                    Cancel
                </button>

                <form :action="deleteAction" method="POST">

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>

    </div>
</x-app-layout>