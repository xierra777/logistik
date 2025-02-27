@section('title', 'User')
<div class="flex-col p-6 text-dark-900 dark:text-gray-100 ">
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Management Users') }}
    </h2>
  </x-slot>
  <div x-data="{ open: false }">
    <!-- Button to open modal -->
    <div class="flex justify-end gap-2 mb-4 justify-end">
      <button @click="open = true" class="py-3 px-4 bg-blue-600 text-white rounded-lg">
        Open Modal
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
        <h3 class="text-xl font-bold mb-4">Add Employee</h3>

        <form wire:submit.prevent="save">
          <!-- Profile Photo Field -->
          <div class="mb-4">
            <label for="profile_photo" class="block text-sm font-medium text-gray-700 dark:text-white">Profile Photo</label>
            <div class="flex items-center gap-x-4 mt-1">
              <div class="w-16 h-16 border border-gray-200 rounded-full flex items-center justify-center text-xl font-bold text-gray-400">
                @if ($profile_photo)
                <img src="{{ $profile_photo->temporaryUrl() }}" alt="Profile Preview"
                  class="object-cover rounded-full w-full h-full">
                @else
                <span>0</span>
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
              class="mt-1 block w-full px-3 py-2 border rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>

          <!-- Email Field -->
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-white">Email</label>
            <input type="email" id="email" wire:model="email" placeholder="Enter email"
              class="mt-1 block w-full px-3 py-2 border rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>

          <!-- Password Field -->
          <div class="mb-4">
            <label for="current-password" class="block text-sm font-medium text-gray-700 dark:text-white">Password</label>
            <input type="password" id="password" wire:model="password" placeholder="Enter password"
              class="mt-1 block w-full px-3 py-2 border rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>

          <!-- Role Field -->
          <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700 dark:text-white">Role</label>
            <select id="role" wire:model="role"
              class="mt-1 block w-full px-3 py-2 border rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400">
              <option value="">Select a Role</option>
              <option value="admin">Admin</option>
              <option value="superadmin">Superadmin</option>
              <option value="user">User</option>
            </select>
            @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>

          <!-- Modal Footer -->
          <div class="flex justify-end gap-2">
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
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $loop->iteration }}</td>

            <!-- Profile Picture -->
            <td class="px-6 py-4 whitespace-nowrap text-sm">
              @if ($user->profile_photo)
              <img src="{{ asset('storage/' . $user->profile_photo) }}" class="w-14 h-14 object-cover rounded-full" alt="Profile Picture">
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