@section('title', 'User')
<div class="flex-col">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Management Users') }}
        </h2>
    </x-slot>
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto justify-end">
        <button wire:click="openModal" class="py-3 px-9 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:hover:bg-blue-900 dark:focus:bg-blue-900 ">
            Add Users
        </button>
    </div>

    @if($isOpen)
    <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 transition-all duration-300 ease-in-out"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="bg-white p-6 rounded-lg w-1/3 transform transition-all duration-300 ease-in-out"
            x-transition:enter="transform transition duration-500"
            x-transition:enter-start="translate-y-10 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transform transition duration-300"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="translate-y-10 opacity-0">

            <h2 class="text-xl mb-4">Tambah User</h2>
            <form wire:submit.prevent="save">

                <!-- Name -->
                <div class="mb-4">
                    <label class="block font-medium">Name</label>
                    <input type="text" wire:model="name" class="w-full border p-2 rounded" placeholder="Masukkan nama">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block font-medium">Email</label>
                    <input type="email" wire:model="email" class="w-full border p-2 rounded" placeholder="Masukkan email">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block font-medium">Password</label>
                    <input type="password" wire:model="password" class="w-full border p-2 rounded" placeholder="Masukkan password">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Profile Picture -->
                <div data-hs-file-upload='{
  "url": "/upload",
  "acceptedFiles": "image/*",
  "maxFiles": 1,
  "singleton": true
}'>
                    <template data-hs-file-upload-preview="">
                        <div class="size-20">
                            <img class="w-full object-contain rounded-full" data-dz-thumbnail="">
                        </div>
                    </template>

                    <div class="flex flex-wrap items-center gap-3 sm:gap-5">
                        <div class="group" data-hs-file-upload-previews="" data-hs-file-upload-pseudo-trigger="">
                            <span class="group-has-[div]:hidden flex shrink-0 justify-center items-center size-20 border-2 border-dotted border-gray-300 text-gray-400 cursor-pointer rounded-full hover:bg-gray-50 dark:border-neutral-700 dark:text-neutral-600 dark:hover:bg-neutral-700/50">
                                <svg class="shrink-0 size-7" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <circle cx="12" cy="10" r="3"></circle>
                                    <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"></path>
                                </svg>
                            </span>
                        </div>

                        <div class="grow">
                            <div class="flex items-center gap-x-2">
                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-xs font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" data-hs-file-upload-trigger="">
                                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="17 8 12 3 7 8"></polyline>
                                        <line x1="12" x2="12" y1="3" y2="15"></line>
                                    </svg>
                                    Upload photo
                                </button>
                                <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-xs font-semibold rounded-lg border border-gray-200 bg-white text-gray-500 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-800 dark:focus:bg-neutral-800" data-hs-file-upload-clear="">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end">
                    <button type="button" wire:click="closeModal" class="mr-2 bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
    @endif
    <br>
    <div class="p-1.5 min-w-full inline-block align-middle">
        <div class="flex items-center gap-3">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="bg-gray-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">No</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Profile Picture</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Nama</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Email</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $loop->iteration }}</td>

                        <!-- Profile Picture -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if ($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-12 h-12 rounded-full" alt="Profile Picture">
                            @else
                            <span class="text-gray-500">No Image</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $user->email }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium space-x-3">
                            <button wire:navigate href="/view-users/{{ $user->id }}"
                                class="font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                View
                            </button>
                            <button wire:navigate href="/edit-users/{{ $user->id }}"
                                class="font-bold text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                Update
                            </button>
                            <button type="button"
                                @click="$dispatch('confirm-delete', { get_id: {{ $user->id }} })"
                                class="font-bold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                                <img src="{{ asset('./images/nodata.svg') }}"
                                    alt="No data illustration"
                                    class="w-64 h-48 mb-4 opacity-75 dark:opacity-50">
                                <p class="text-gray-600 dark:text-neutral-300 text-lg font-medium mb-2">
                                    No users found!
                                </p>
                                <p class="text-sm text-gray-500 dark:text-neutral-500 text-center">
                                    Start by adding users or importing data.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <x-confirm-delete />
        </div>
        <!-- Pagination -->
        @if($users->hasPages())
        <div class="mt-4 px-1.5">
            {{ $users->links() }}
        </div>
        @endif

        <!-- Selector Rows Per Page -->
        <div class="mt-4">
            <select wire:model.live="perPage" class="py-1 px-2 bg-gray-100 border rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400 dark:focus:ring-neutral-600">
                <option value="5">5 Rows</option>
                <option value="10">10 Rows</option>
                <option value="25">25 Rows</option>
                <option value="50">50 Rows</option>
                <option value="100">100 Rows</option>
            </select>
        </div>
    </div>
</div>