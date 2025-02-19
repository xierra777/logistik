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
    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none" aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-scale-animation-modal" data-hs-overlay="#hs-scale-animation-modal">
        Open modal
    </button>
    <div id="hs-scale-animation-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none" role="dialog" tabindex="-1" aria-labelledby="hs-scale-animation-modal-label">
        <div class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 id="hs-scale-animation-modal-label" class="font-bold text-gray-800 dark:text-white">
                        Modal title
                    </h3>
                    <button type="button" class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:hover:bg-neutral-600 dark:text-neutral-400 dark:focus:bg-neutral-600" aria-label="Close" data-hs-overlay="#hs-scale-animation-modal">
                        <span class="sr-only">Close</span>
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <p class="mt-1 text-gray-800 dark:text-neutral-400">
                        This is a wider card with supporting text below as a natural lead-in to additional content.
                    </p>
                </div>
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-700 dark:focus:bg-neutral-700" data-hs-overlay="#hs-scale-animation-modal">
                        Close
                    </button>
                    <button type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                        Save changes
                    </button>
                </div>
            </div>
        </div>
    </div>
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