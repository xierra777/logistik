<div x-data="{ open: false }" class="p-3">
    <div class="grid grid-cols-1 md:grid-cols-2 border min-h-[800px] p-3">
        <div class="flex items-center justify-center border">
            <div class="w-[300px] h-[400px] bg-gray-100 flex items-center justify-center overflow-hidden">
                <img
                    src="{{ asset('storage/' . $users->profile_photo) }}"
                    alt="Profile Photo"
                    class="max-w-full max-h-full object-contain"
                    @click="open = true" />
            </div>
        </div>
        <div class="p-4">
            <h2 class="text-center mb-4 font-bold">Employee Details</h2>
            <div class="text-xl grid grid-cols-4 p-1">
                <p class="">Name</p>
                <span class="col-span-3">: {{ $users->name }}</span>
            </div>
            <div class="text-xl grid grid-cols-4 p-1">
                <p class="">Role</p>
                <span class="col-span-3">: {{ $users->role }}</span>
            </div>
            <div class="text-xl grid grid-cols-4 p-1">
                <p class="">Email</p>
                <span class="col-span-3">: {{ $users->email }}</span>
            </div>
            <div class="text-xl grid grid-cols-4 p-1">
                <p class="">Departement</p>
                <span class="col-span-3">: {{ null }}</span>
            </div>
            <div class="text-xl grid grid-cols-4 p-1">
                <p class="">Contact</p>
                <span class="col-span-3">: {{ null }}</span>
            </div>
            <div class="text-xl grid grid-cols-4 p-1">
                <p class="">Address</p>
                <span class="col-span-3">: {{ null }}</span>
            </div>
        </div>
        <div class="col-span-2">
            <hr class="my-4 h-1 bg-gradient-to-r from-blue-700 via-purple-500 to-purple-300 border-0 rounded-full shadow-md transition-all duration-500 ease-in-out">
            <div class="flex justify-end p-3 ">
                <a href="{{route('userList')}}" class="py-2 px-5 bg-blue-500 rounded-md text-white hover:text-gray-600 hover:scale-105 transition-transform hover:bg-blue-400 hover:shadow-md hover:shadow-blue-500">Back</a>
            </div>
        </div>

    </div>

    <!-- Modal -->
    <div
        x-cloak
        x-show="open"
        x-transition
        class="fixed inset-0 z-50 flex items-center justify-center"
        @keydown.escape.window="open = false">
        <!-- Background -->
        <div
            class="absolute inset-0 bg-black bg-opacity-60"
            @click="open = false"></div>

        <!-- Image Card -->
        <div class="relative bg-white p-6 border-4 border-gray-300 rounded-xl shadow-2xl max-w-3xl w-full z-10">
            <img
                src="{{ asset('storage/' . $users->profile_photo) }}"
                alt="Profile Full"
                class="w-full h-auto object-contain rounded" />
        </div>
    </div>
</div>