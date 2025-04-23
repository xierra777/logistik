<div class="p-6 text-dark-900 dark:text-gray-100">
    <!-- Header Slot -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customers') }}
        </h2>
    </x-slot>
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto justify-end">
        <a href="/customers/create"
            class="py-3 px-9 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:hover:bg-blue-900 dark:focus:bg-blue-900">
            Tambah data
        </a>
    </div>
    <br>
    <div class="table-container">
        <!-- Pesan Status -->
        @if(session('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-neutral-800 dark:text-green-400">
            {{ session('message') }}
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-neutral-800 dark:text-red-400">
            {{ session('error') }}
        </div>
        @endif

        <!-- Tabel Customers -->
        <div class="p-1.5 inline-block w-full align-middle">
            <div class="overflow-x-auto table-cont  ainer">
                <div class="border rounded-md overflow-hidden dark:border-neutral-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="bg-gray-50 dark:bg-neutral-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">No</th>
                                <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Name</th>
                                <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Country</th>
                                <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Roles</th>
                                <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-500 dark:divide-neutral-800 bg-white dark:bg-neutral-900">
                            @forelse($customers as $customer)
                            <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $customer->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $customer->country }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300"> {{ implode(', ', $customer->roles) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium space-x-3">
                                    <button wire:navigate href="/view-customers/{{ $customer->id }}"
                                        class="font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        View
                                    </button>
                                    <button wire:navigate href="/edit-customers/{{ $customer->id }}"
                                        class="font-bold text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        Update
                                    </button>
                                    <button type="button"
                                        @click="$dispatch('confirm-delete', { get_id: {{ $customer->id }} })"
                                        class="font-bold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                                        <img src="{{ asset('./images/nodata.svg') }}"
                                            alt="No data illustration"
                                            class="w-64 h-48 mb-4 opacity-75 dark:opacity-50">
                                        <p class="text-gray-600 dark:text-neutral-300 text-lg font-medium mb-2">
                                            No customers found!
                                        </p>
                                        <p class="text-sm text-gray-500 dark:text-neutral-500 text-center">
                                            Start by adding customers or importing data.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <x-confirm-delete />
            </div>

            <!-- Pagination -->
            @if($customers->hasPages())
            <div class="mt-4 px-1.5">
                {{ $customers->links() }}
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
</div>
</div>