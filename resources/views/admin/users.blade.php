<x-app-layout>
    @if(session('success'))

        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">
            {{ session('success') }}
        </div>

    @endif

    @if(session('error'))

        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>

    @endif

    @if($errors->any())

        <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif
    
    <div
        x-data="{
            showDeleteUserModal: false,
            deleteUserAction: '',
            userName: '',
            showDeleteScheduleModal: false,
            deleteScheduleAction: '',
            subjectName: '',
        }"
    >

    <x-slot name="header">
        Users Management
    </x-slot>

    <div class="flex justify-between items-center mb-8">

        <h2 class="text-3xl font-bold">
            Users
        </h2>

        <button
            onclick="document.getElementById('createUserModal').classList.remove('hidden')"
            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold"
        >
            + Add User
        </button>

    </div>

    <div class="grid grid-cols-3 gap-6">

        @foreach($users as $user)

            <div class="bg-white rounded-2xl shadow p-6">

                <div class="flex flex-col items-center">

                    <div class="w-20 h-20 rounded-full bg-slate-900 flex items-center justify-center shadow-md mb-4">
                        <span class="text-white text-4xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>

                    <h2 class="text-2xl font-bold">
                        {{ $user->name }}
                    </h2>

                    <p class="text-gray-500 mb-4">
                        {{ $user->email }}
                    </p>

                </div>

                <div class="space-y-3 mt-4">

                    <div class="bg-gray-100 rounded-xl p-3">
                        <strong>Role:</strong>
                        {{ ucfirst($user->role) }}
                    </div>

                    <div class="bg-gray-100 rounded-xl p-3">
                        <strong>Reservations:</strong>
                        {{ $user->reservations->count() }}
                    </div>

                    <div class="bg-gray-100 rounded-xl p-3">
                        <strong>Created:</strong>
                        {{ $user->created_at->format('d M Y') }}
                    </div>

                </div>

                <div class="flex gap-2 mt-6">

                    <button
                        onclick="document.getElementById('editUserModal{{ $user->id }}').classList.remove('hidden')"
                        class="flex-1 bg-yellow-500 text-white py-2 rounded-xl"
                    >
                        Edit
                    </button>

                    <button
                        onclick="document.getElementById('scheduleModal{{ $user->id }}').classList.remove('hidden')"
                        class="flex-1 bg-blue-600 text-white py-2 rounded-xl"
                    >
                        Schedule
                    </button>

                    <form
                        action="/users/{{ $user->id }}"
                        method="POST"
                        class="flex-1"
                    >

                        @csrf
                        @method('DELETE')

                        <button
                            type="button"
                            @click="
                                showDeleteUserModal = true;
                                userName = '{{ $user->name }}';
                                deleteUserAction = '/users/{{ $user->id }}';
                            "
                            class="w-full bg-red-500 text-white py-2 rounded-xl"
                        >
                            Delete
                        </button>

                    </form>

                </div>

            </div>

            <div
                id="editUserModal{{ $user->id }}"
                class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50"
            >

                <div class="bg-white rounded-2xl p-8 w-[600px]">

                    <h2 class="text-2xl font-bold mb-6">
                        Edit User
                    </h2>

                    <form action="/users/{{ $user->id }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-4">

                            <input
                                type="text"
                                name="name"
                                value="{{ $user->name }}"
                                class="border rounded-xl p-3"
                                required
                            >

                            <input
                                type="email"
                                name="email"
                                value="{{ $user->email }}"
                                class="border rounded-xl p-3"
                                required
                            >

                            <input
                                type="password"
                                name="password"
                                placeholder="Leave empty to keep current password"
                                class="border rounded-xl p-3"
                            >

                            <select
                                name="role"
                                class="border rounded-xl p-3"
                                required
                            >
                                <option
                                    value="teacher"
                                    {{ $user->role == 'teacher' ? 'selected' : '' }}
                                >
                                    Teacher
                                </option>

                                <option
                                    value="admin"
                                    {{ $user->role == 'admin' ? 'selected' : '' }}
                                >
                                    Admin
                                </option>
                            </select>

                        </div>

                        <div class="flex justify-end gap-3 mt-6">

                            <button
                                type="button"
                                onclick="document.getElementById('editUserModal{{ $user->id }}').classList.add('hidden')"
                                class="bg-gray-200 px-5 py-2 rounded-xl"
                            >
                                Cancel
                            </button>

                            <button
                                class="bg-blue-600 text-white px-5 py-2 rounded-xl"
                            >
                                Save Changes
                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <div
                id="scheduleModal{{ $user->id }}"
                class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50"
            >

                <div class="bg-white rounded-2xl p-8 w-[1000px] max-h-[90vh] overflow-y-auto">

                    <div class="flex justify-between items-center mb-6">

                        <h2 class="text-3xl font-bold">
                            {{ $user->name }} Schedule
                        </h2>

                        <button
                            onclick="document.getElementById('scheduleModal{{ $user->id }}').classList.add('hidden')"
                            class="bg-gray-200 px-4 py-2 rounded-xl"
                        >
                            Close
                        </button>

                    </div>

                    <div class="space-y-6">

                        @forelse($user->schedules as $schedule)

                            <div class="border rounded-xl p-4 flex justify-between items-center">

                                <div>

                                    <p class="font-bold">
                                        {{ $schedule->day_of_week }}
                                    </p>

                                    <p>
                                        {{ $schedule->start_time }}
                                        -
                                        {{ $schedule->end_time }}
                                    </p>

                                    <p>
                                        Room {{ $schedule->room->number }}
                                    </p>

                                    <p>
                                        {{ $schedule->subject }}
                                    </p>

                                </div>

                                <div class="flex gap-2">

                                    <button
                                        onclick="openEditScheduleModal(
                                            {{ $schedule->id }},
                                            '{{ $schedule->subject }}',
                                            '{{ $schedule->room_id }}',
                                            '{{ $schedule->day_of_week }}',
                                            '{{ substr($schedule->start_time,0,5) }}',
                                            '{{ substr($schedule->end_time,0,5) }}'
                                        )"
                                        class="bg-yellow-500 text-white px-6 py-3 rounded-xl"
                                    >
                                        Edit
                                    </button>

                                    <form
                                        action="/admin/schedules/{{ $schedule->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Delete this class?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            @click="
                                                showDeleteScheduleModal = true;
                                                subjectName = '{{ $schedule->subject }}';
                                                deleteScheduleAction = '/admin/schedules/{{ $schedule->id }}';
                                            "
                                            class="bg-red-500 text-white px-6 py-3 rounded-xl"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </div>

                        @empty

                            <div class="bg-gray-100 rounded-xl p-8 text-center">

                                No schedule assigned.

                            </div>

                        @endforelse

                    </div>

                    <div class="mt-8">

                        <button
                            onclick="openAddClassModal({{ $user->id }})"
                            class="bg-blue-600 text-white px-6 py-3 rounded-xl"
                        >
                            + Add Class
                        </button>

                       

                    </div>

                </div>

            </div>

        @endforeach

    </div>

                    <div id="addClassModal"
                            class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

                            <div class="bg-white rounded-3xl p-8 w-[700px]">

                                <div class="flex justify-between items-center mb-6">

                                    <h2 class="text-3xl font-bold">
                                        Add Class
                                    </h2>

                                    <button
                                        onclick="closeAddClassModal()"
                                        class="px-4 py-2 bg-slate-200 rounded-xl"
                                    >
                                        Close
                                    </button>

                                </div>

                                <form action="/admin/schedules" method="POST">

                                    @csrf

                                    <input
                                        type="hidden"
                                        name="user_id"
                                        id="schedule_user_id"
                                    >

                                    <div class="grid grid-cols-2 gap-4">

                                        <input
                                            type="text"
                                            name="subject"
                                            placeholder="Subject"
                                            class="border rounded-xl p-3"
                                            required
                                        >

                                        <select
                                            name="room_id"
                                            class="border rounded-xl p-3"
                                            required
                                        >

                                            <option value="">
                                                Select Room
                                            </option>

                                            @foreach($rooms as $room)

                                                <option value="{{ $room->id }}">
                                                    {{ $room->number }}
                                                </option>

                                            @endforeach

                                        </select>

                                        <select
                                            name="day_of_week"
                                            class="border rounded-xl p-3"
                                            required
                                        >

                                            <option>Monday</option>
                                            <option>Tuesday</option>
                                            <option>Wednesday</option>
                                            <option>Thursday</option>
                                            <option>Friday</option>

                                        </select>

                                        <div></div>

                                        <select
                                            name="start_time"
                                            required
                                            class="w-full border rounded-xl px-4 py-3"
                                        >
                                            <option value="">
                                                Select Start Time
                                            </option>

                                            @for($hour = 7; $hour <= 19; $hour++)

                                                @foreach(['00', '15', '30', '45'] as $minute)

                                                    <option value="{{ sprintf('%02d:%s', $hour, $minute) }}">
                                                        {{ sprintf('%02d:%s', $hour, $minute) }}
                                                    </option>

                                                @endforeach

                                            @endfor

                                            <option value="20:00">
                                                20:00
                                            </option>
                                        </select>

                                        <select
                                            name="end_time"
                                            required
                                            class="w-full border rounded-xl px-4 py-3"
                                        >
                                            <option value="">
                                                Select End Time
                                            </option>

                                            @for($hour = 7; $hour <= 19; $hour++)

                                                @foreach(['00', '15', '30', '45'] as $minute)

                                                    <option value="{{ sprintf('%02d:%s', $hour, $minute) }}">
                                                        {{ sprintf('%02d:%s', $hour, $minute) }}
                                                    </option>

                                                @endforeach

                                            @endfor

                                            <option value="20:00">
                                                20:00
                                            </option>
                                        </select>

                                    </div>

                                    <div class="flex justify-end gap-4 mt-6">

                                        <button
                                            type="button"
                                            onclick="closeAddClassModal()"
                                            class="px-6 py-3 bg-slate-200 rounded-xl"
                                        >
                                            Cancel
                                        </button>

                                        <button
                                            type="submit"
                                            class="px-6 py-3 bg-blue-600 text-white rounded-xl"
                                        >
                                            Save
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                        <div id="editScheduleModal"
                            class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

                            <div class="bg-white rounded-3xl p-8 w-[700px]">

                                <div class="flex justify-between items-center mb-6">

                                    <h2 class="text-3xl font-bold">
                                        Edit Class
                                    </h2>

                                    <button
                                        onclick="closeEditScheduleModal()"
                                        class="px-4 py-2 bg-slate-200 rounded-xl"
                                    >
                                        Close
                                    </button>

                                </div>

                                <form
                                    id="editScheduleForm"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PUT')

                                    <div class="grid grid-cols-2 gap-4">

                                        <input
                                            id="edit_subject"
                                            type="text"
                                            name="subject"
                                            class="border rounded-xl p-3"
                                            required
                                        >

                                        <select
                                            id="edit_room_id"
                                            name="room_id"
                                            class="border rounded-xl p-3"
                                            required
                                        >

                                            @foreach($rooms as $room)

                                                <option value="{{ $room->id }}">
                                                    {{ $room->number }}
                                                </option>

                                            @endforeach

                                        </select>

                                        <select
                                            id="edit_day"
                                            name="day_of_week"
                                            class="border rounded-xl p-3"
                                        >

                                            <option>Monday</option>
                                            <option>Tuesday</option>
                                            <option>Wednesday</option>
                                            <option>Thursday</option>
                                            <option>Friday</option>

                                        </select>

                                        <div></div>

                                        <select
                                            id="edit_start"
                                            name="start_time"
                                            class="border rounded-xl p-3"
                                            required
                                        >

                                            @for($hour = 7; $hour <= 19; $hour++)

                                                @foreach(['00', '15', '30', '45'] as $minute)

                                                    <option value="{{ sprintf('%02d:%s', $hour, $minute) }}">
                                                        {{ sprintf('%02d:%s', $hour, $minute) }}
                                                    </option>

                                                @endforeach

                                            @endfor

                                            <option value="20:00">
                                                20:00
                                            </option>

                                        </select>

                                        <select
                                            id="edit_end"
                                            name="end_time"
                                            class="border rounded-xl p-3"
                                            required
                                        >

                                            @for($hour = 7; $hour <= 19; $hour++)

                                                @foreach(['00', '15', '30', '45'] as $minute)

                                                    <option value="{{ sprintf('%02d:%s', $hour, $minute) }}">
                                                        {{ sprintf('%02d:%s', $hour, $minute) }}
                                                    </option>

                                                @endforeach

                                            @endfor

                                            <option value="20:00">
                                                20:00
                                            </option>

                                        </select>

                                    </div>

                                    <div class="flex justify-end gap-4 mt-6">

                                        <button
                                            type="button"
                                            onclick="closeEditScheduleModal()"
                                            class="bg-slate-200 px-6 py-3 rounded-xl"
                                        >
                                            Cancel
                                        </button>

                                        <button
                                            type="submit"
                                            class="bg-blue-600 text-white px-6 py-3 rounded-xl"
                                        >
                                            Save
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

    {{-- CREATE USER MODAL --}}

    <div
        id="createUserModal"
        class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50"
    >

        <div class="bg-white rounded-2xl p-8 w-[600px]">

            <h2 class="text-2xl font-bold mb-6">
                Add User
            </h2>

            <form action="/users" method="POST">

                @csrf

                <div class="grid grid-cols-2 gap-4">

                    <input
                        type="text"
                        name="name"
                        placeholder="Full Name"
                        class="border rounded-xl p-3"
                        required
                    >

                    <input
                        type="email"
                        name="email"
                        placeholder="Email"
                        class="border rounded-xl p-3"
                        required
                    >

                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        class="border rounded-xl p-3"
                        required
                    >

                    <select
                        name="role"
                        class="border rounded-xl p-3"
                        required
                    >
                        <option value="teacher">
                            Teacher
                        </option>

                        <option value="admin">
                            Admin
                        </option>

                    </select>

                </div>

                <div class="flex justify-end gap-3 mt-6">

                    <button
                        type="button"
                        onclick="document.getElementById('createUserModal').classList.add('hidden')"
                        class="bg-gray-200 px-5 py-2 rounded-xl"
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
        x-show="showDeleteUserModal"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        x-cloak
    >

        <div class="bg-white rounded-2xl p-6 w-full max-w-md">

            <h2 class="text-2xl font-bold mb-4">
                Delete User
            </h2>

            <p class="mb-6">
                Are you sure you want to delete
                <strong x-text="userName"></strong>?
            </p>

            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    @click="showDeleteUserModal = false"
                    class="px-4 py-2 border rounded-lg"
                >
                    Cancel
                </button>

                <form :action="deleteUserAction" method="POST">

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>

    </div>

    <div
        x-show="showDeleteScheduleModal"
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
        x-cloak
    >

        <div class="bg-white rounded-2xl p-6 w-full max-w-md">

            <h2 class="text-2xl font-bold mb-4">
                Delete Class
            </h2>

            <p class="mb-6">
                Delete class:
                <strong x-text="subjectName"></strong>?
            </p>

            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    @click="showDeleteScheduleModal = false"
                    class="px-4 py-2 border rounded-lg"
                >
                    Cancel
                </button>

                <form :action="deleteScheduleAction" method="POST">

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>

    </div>

    <script>

        function openAddClassModal(userId)
        {
            document.getElementById('schedule_user_id').value = userId;

            document
                .getElementById('addClassModal')
                .classList.remove('hidden');

            document
                .getElementById('addClassModal')
                .classList.add('flex');
        }

        function closeAddClassModal()
        {
            document
                .getElementById('addClassModal')
                .classList.add('hidden');

            document
                .getElementById('addClassModal')
                .classList.remove('flex');
        }

        function openEditScheduleModal(
            id,
            subject,
            roomId,
            day,
            start,
            end
        )
        {
            document.getElementById('edit_subject').value = subject;
            document.getElementById('edit_room_id').value = roomId;
            document.getElementById('edit_day').value = day;
            document.getElementById('edit_start').value = start;
            document.getElementById('edit_end').value = end;

            document.getElementById('editScheduleForm').action =
                '/admin/schedules/' + id;

            document
                .getElementById('editScheduleModal')
                .classList.remove('hidden');

            document
                .getElementById('editScheduleModal')
                .classList.add('flex');
        }

        function closeEditScheduleModal()
        {
            document
                .getElementById('editScheduleModal')
                .classList.add('hidden');

            document
                .getElementById('editScheduleModal')
                .classList.remove('flex');
        }

    </script>


</x-app-layout>

