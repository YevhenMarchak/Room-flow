<x-app-layout>
    <x-slot name="header">
        Profile
    </x-slot>

    <div class="grid grid-cols-3 gap-8">

        <!-- LEFT CARD -->

        <div class="bg-white rounded-3xl shadow p-8 h-fit">

            <div class="flex flex-col items-center">

                <!-- AVATAR -->

                <div class="w-32 h-32 rounded-full bg-slate-900 text-white flex items-center justify-center text-5xl font-bold mb-6 shadow-lg">

                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}

                </div>

                <!-- USER INFO -->

                <h2 class="text-2xl font-bold mb-2">
                    {{ Auth::user()->name }}
                </h2>

                <p class="text-slate-500 text-lg mb-1 capitalize">
                    {{ Auth::user()->role }}
                </p>

                <p class="text-slate-400">
                    {{ Auth::user()->email }}
                </p>

            </div>

            <!-- STATS -->

            <div class="mt-10 space-y-4">

                <div class="bg-slate-100 rounded-2xl p-4">

                    <p class="text-slate-500 text-sm mb-1">
                        Account Created
                    </p>

                    <p class="font-bold">
                        {{ Auth::user()->created_at->format('d M Y') }}
                    </p>

                </div>

                @if(Auth::user()->role == 'teacher')

                    <div class="bg-slate-100 rounded-2xl p-4">

                        <p class="text-slate-500 text-sm mb-1">
                            Total Reservations
                        </p>

                        <p class="font-bold text-2xl">
                            {{ Auth::user()->reservations->count() }}
                        </p>

                    </div>

                @endif

            </div>

        </div>

        <!-- RIGHT SIDE -->

        <div class="col-span-2 space-y-8">

            <!-- ACCOUNT INFO -->

            <div class="bg-white rounded-3xl shadow p-8">

                <h2 class="text-2xl font-bold mb-6">
                    Account Information
                </h2>

                <div class="grid grid-cols-2 gap-6">

                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Full Name
                        </label>

                        <input
                            type="text"
                            value="{{ Auth::user()->name }}"
                            disabled
                            class="w-full border border-slate-200 rounded-2xl p-4 bg-slate-50"
                        >

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Email Address
                        </label>

                        <input
                            type="text"
                            value="{{ Auth::user()->email }}"
                            disabled
                            class="w-full border border-slate-200 rounded-2xl p-4 bg-slate-50"
                        >

                    </div>

                    <div>

                        <label class="block text-sm font-semibold mb-2">
                            Role
                        </label>

                        <input
                            type="text"
                            value="{{ Auth::user()->role }}"
                            disabled
                            class="w-full border border-slate-200 rounded-2xl p-4 bg-slate-50 capitalize"
                        >

                    </div>

                </div>

            </div>

            <!-- CHANGE PASSWORD -->

            <div class="bg-white rounded-3xl shadow p-8">

                <h2 class="text-2xl font-bold mb-6">
                    Change Password
                </h2>

                @if (session('status') === 'password-updated')

                    <div class="mb-6 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl">
                        Password updated successfully.
                    </div>

                @endif

                <form method="post" action="{{ route('password.update') }}">

                    @csrf
                    @method('put')

                    <div class="space-y-6">

                        <div>

                            <label class="block text-sm font-semibold mb-2">
                                Current Password
                            </label>

                            <input
                                type="password"
                                name="current_password"
                                class="w-full border border-slate-200 rounded-2xl p-4"
                            >

                            @error('current_password')
                                <p class="text-red-500 text-sm mt-2">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        <div>

                            <label class="block text-sm font-semibold mb-2">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                class="w-full border border-slate-200 rounded-2xl p-4"
                            >

                            @error('password')
                                <p class="text-red-500 text-sm mt-2">
                                    {{ $message }}
                                </p>
                            @enderror

                        </div>

                        <div>

                            <label class="block text-sm font-semibold mb-2">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="w-full border border-slate-200 rounded-2xl p-4"
                            >

                        </div>

                        <button
                            class="bg-slate-900 hover:bg-slate-800 text-white px-8 py-4 rounded-2xl transition"
                        >
                            Update Password
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>