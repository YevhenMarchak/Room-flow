<div
    x-data="{
        open: false,
        action: '',
        message: ''
    }"

    @open-confirm.window="
        console.log('EVENT');

        open = true;
        action = $event.detail.action;
        message = $event.detail.message;
    "
>

    <div
        x-show="open"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
        x-cloak
    >

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">

            <h2 class="text-xl font-bold mb-4">
                Confirmation
            </h2>

            <p class="text-gray-600 mb-6" x-text="message"></p>

            <div class="flex justify-end gap-3">

                <button
                    @click="open = false"
                    class="px-4 py-2 rounded-xl border"
                >
                    Cancel
                </button>

                <form :action="action" method="POST">

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-xl"
                    >
                        Delete
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>