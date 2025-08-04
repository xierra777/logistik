@section('title', 'User')
<div class="flex-col p-6 text-dark-900 dark:text-gray-100 ">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Management Users') }}
        </h2>
    </x-slot>
    <div x-data="{ open: false }" @keydown.escape.window="open = false" @close-modal.window="open = false">
        <!-- Button to open modal -->
        <div class="flex justify-end gap-2 mb-4 justify-end">
            <button @click="open = true" class="py-s3 px-4 bg-blue-600 text-white rounded-lg">
                Add Users
            </button>
        </div>
        <!-- Background Overlay with Transparent Gray -->
        <div x-cloak x-show="open"
            x-transition:enter="transition ease-out duration-300 delay-150"
            x-transition:leave="transition ease-in duration-200"
            class="fixed inset-0 bg-gray-500 bg-opacity-50 pointer-events-none z-40">
        </div>

        <!-- Modal Container -->
        <div x-cloak x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="scale-90 opacity-0"
            x-transition:enter-end="scale-100 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="scale-100 opacity-100"
            x-transition:leave-end="scale-90 opacity-0"
            class="fixed inset-0 flex items-center justify-center z-50 pointer-events-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                <div class="flex justify-end ">
                    <button @click="$dispatch('close-modal')" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:text-gray-800 hover:bg-gray-300 text-xl font-bold transition-transform">
                        &times;
                    </button>
                </div>
                <h3 class="text-xl font-bold mb-4">Add Employee</h3>
                <form wire:submit.prevent="save">
                    <!-- Profile Photo Field -->
                    <div class="mb-4">
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-white">Profile Photo</label>
                        <div class="flex items-center gap-x-4 mt-1">
                            <div class="w-16 h-16 border border-gray-200 border border-gray-400 rounded-full flex items-center justify-center text-xl font-bold text-gray-400">
                                @if ($profile_photo)
                                <img src="{{ $profile_photo->temporaryUrl() }}" alt="Profile Preview"
                                    class="object-cover rounded-full w-full h-full">
                                @else
                                <img src="{{ asset('./images/ppdonat.jpg') }}" class="rounded-full">
                                @endif
                            </div>
                            <label for="profile_photo" class="cursor-pointer inline-flex items-center gap-x-2 py-2 px-3 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none">
                                Upload Photo
                            </label>
                            <input type="file" id="profile_photo"
                                wire:model="profile_photo"
                                class="hidden"
                                wire:loading.attr="disabled"
                                x-on:livewire-upload-start="$wire.dispatch('fileUploadStarted')"
                                x-on:livewire-upload-finish="$wire.dispatch('fileUploadFinished')"
                                x-on:livewire-upload-error="$wire.dispatch('fileUploadFinished')">
                        </div>
                        <div wire:loading wire:target="profile_photo" class="mt-2 text-sm text-blue-600">
                            <div class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </div>
                        </div>
                        @error('profile_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Name Field -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-white">Name</label>
                        <input type="text" id="name" wire:model="name" placeholder="Enter full name"
                            class="mt-1 block w-full px-3 py-2 border border-gray-400 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-white">Email</label>
                        <input type="email" id="email" wire:model="email" placeholder="Enter email"
                            class="mt-1 block w-full px-3 py-2 border border-gray-400 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="current-password" class="block text-sm font-medium text-gray-700 dark:text-white">Password</label>
                        <input type="password" id="password" wire:model="password" placeholder="Enter password"
                            class="mt-1 block w-full px-3 py-2 border border-gray-400 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role Field -->
                    <!-- Select -->
                    <div wire:ignore class="mb-4">
                        <label for="roles">Roles</label>
                        <select wire:model="role" data-hs-select='{
              "placeholder": "Select option...",
              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-400 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",
              "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-400 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
              "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
              "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600 dark:text-blue-500 \" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
              "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
            }' class="hidden">
                            <option value="">Choose</option>
                            <option value="speradmin">SUPER ADMIN</option>
                            <option value="admin">Operational</option>
                            <option value="Sales">Sales</option>
                            <option value="owner">owner</option>
                            <option value="Manager">Manager</option>
                        </select>
                    </div>

                    <!-- End Select -->
                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-2 pt-5">
                        <button type="button" @click="open = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Created by</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Created At</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Updated by</th>
                        <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Updated At</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium text-gray-800 dark:text-neutral-200">{{ $loop->iteration }}</td>

                        <!-- Profile Picture -->
                        <td class="px-6 py-4 whitespace-nowrap text-center align-middle text-sm">
                            @if ($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}"
                                class="w-14 h-14 object-cover rounded-full mx-auto"
                                alt="Profile Picture">
                            @else
                            <span class="text-gray-500">No Image</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $user->email }}</td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-center gap-3">
                                <a wire:navigate href="{{route('userView',[$user->id])}}"
                                    class="font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    View
                                </a>
                                <button @click="$dispatch('open-edit', { id: {{ $user->id }} })"
                                    class="font-bold text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                    Update
                                </button>
                                <button type="button"
                                    @click="$dispatch('confirm-delete', { get_id: {{ $user->id }} })"
                                    class="font-bold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    Delete
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $user->creator?->name ?? 'System' }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $user->created_at }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $user->creatorUpdated?->name ?? '' }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $user->updated_at }}</td>
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
    <div x-data="{ openEdit: false }" @open-edit.window="
    openEdit = true; 
    $wire.opencase($event.detail.id)
" @close-edit.window="openEdit = false">
        <!-- Button to open modal -->

        <!-- Background Overlay with Transparent Gray -->
        <div x-cloak x-show="openEdit"
            x-transition:enter="transition ease-out duration-300 delay-150"
            x-transition:leave="transition ease-in duration-200"
            class="fixed inset-0 bg-gray-500 bg-opacity-50 pointer-events-none z-40">
        </div>

        <!-- Modal Container -->
        <div x-cloak x-show="openEdit"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="scale-90 opacity-0"
            x-transition:enter-end="scale-100 opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="scale-100 opacity-100"
            x-transition:leave-end="scale-90 opacity-0"
            class="fixed inset-0 flex items-center justify-center z-50 pointer-events-auto">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                <div class="flex justify-end ">
                    <button @click="$dispatch('close-edit')" class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:text-gray-800 hover:bg-gray-300 text-xl font-bold transition-transform">
                        &times;
                    </button>
                </div>
                <h3 class="text-xl font-bold mb-4">Add Employee</h3>
                <form wire:submit.prevent="update">
                    <!-- Profile Photo Field -->
                    <div class="mb-4">
                        <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-white">Profile Photo</label>
                        <div class="flex items-center gap-x-4 mt-1">
                            <div class="w-16 h-16 border border-gray-200 rounded-full flex items-center justify-center text-xl font-bold text-gray-400">
                                @if ($profile_photo)
                                {{-- Foto baru yang diupload --}}
                                <img src="{{ $profile_photo->temporaryUrl() }}" alt="New Profile Preview"
                                    class="object-cover rounded-full w-full h-full">
                                @elseif ($existing_photo)
                                {{-- Foto existing --}}
                                <img src="{{ asset('storage/' . $existing_photo) }}" alt="Current Profile"
                                    class="object-cover rounded-full w-full h-full">
                                @else
                                {{-- Default foto --}}
                                <img src="{{ asset('./images/ppdonat.jpg') }}" alt="Default Profile"
                                    class="object-cover rounded-full w-full h-full">
                                @endif
                            </div>
                            <label for="profile_photo" class="cursor-pointer inline-flex items-center gap-x-2 py-2 px-3 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none">
                                Upload Photo
                            </label>
                            <input type="file" id="editProfile_photo"
                                wire:model="profile_photo"
                                class="hidden"
                                wire:loading.attr="disabled"
                                x-on:livewire-upload-start="$wire.dispatch('fileUploadStarted')"
                                x-on:livewire-upload-finish="$wire.dispatch('fileUploadFinished')"
                                x-on:livewire-upload-error="$wire.dispatch('fileUploadFinished')">
                        </div>
                        <div wire:loading wire:target="profile_photo" class="mt-2 text-sm text-blue-600">
                            <div class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </div>
                        </div>
                        @error('profile_photo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Name Field -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-white">Name</label>
                        <input type="text" id="editName" wire:model="name" placeholder="Enter full name"
                            class="mt-1 block w-full px-3 py-2 border rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
                        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-white">Email</label>
                        <input type="email" id="editEmail" wire:model="email" placeholder="Enter email"
                            class="mt-1 block w-full px-3 py-2 border rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
                        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="mb-4">
                        <label for="current-password" class="block text-sm font-medium text-gray-700 dark:text-white">Password</label>
                        <input type="password" id="editPassword" wire:model="password" placeholder="Enter password"
                            class="mt-1 block w-full px-3 py-2 border rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
                        @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Role Field -->
                    <!-- Select -->
                    <div wire:ignore class="mb-4">
                        <label for="roles">Roles</label>
                        <select wire:model="role" data-hs-select='{
              "placeholder": "Select option...",
              "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
              "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-neutral-600",
              "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
              "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-none focus:bg-gray-100 hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
              "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600 dark:text-blue-500 \" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
              "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
            }' class="hidden">
                            <option value="">Choose</option>
                            <option value="speradmin">SUPER ADMIN</option>
                            <option value="admin">Operational</option>
                            <option value="Sales">Sales</option>
                            <option value="owner">owner</option>
                            <option value="Manager">Manager</option>
                        </select>
                    </div>

                    <!-- End Select -->
                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-2 pt-5">
                        <button type="button" @click="openEdit = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>