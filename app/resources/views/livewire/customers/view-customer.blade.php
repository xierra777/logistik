@section('title', 'View Customer')
<div class="p-4">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Details Customer') }}
        </h2>
    </x-slot>

    <div class="bg-stone-350 ">
        <div class="font-bold border border-gray-300 p-2 bg-gray-100">
            Customer Details
        </div>
        <table class="w-full border border-gray-300 border-collapse">
            <tbody class="divide-y divide-gray-300">
                <tr class="bg-gray-50">
                    <td class="border px-4 py-2 font-semibold w-1/4">Name</td>
                    <td class="border px-4 py-2 w-1/4">{{ $customer->name }}</td>
                    <td class="border px-4 py-2 font-semibold w-1/4">Country</td>
                    <td class="border px-4 py-2 w-1/4">{{ $customer->country }}</td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-semibold">Email</td>
                    <td class="border px-4 py-2">{{ $customer->email }}</td>
                    <td class="border px-4 py-2 font-semibold">Contact</td>
                    <td class="border px-4 py-2">{{ $customer->contact }}</td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="border px-4 py-2 font-semibold">Web</td>
                    <td class="border px-4 py-2">{{ $customer->web }}</td>
                    <td class="border px-4 py-2 font-semibold">Roles</td>
                    <td class="border px-4 py-2 uppercase">
                        {{ is_array($customer->roles) 
                            ? implode(', ', array_map(fn($role) => str_replace('_', ' ', $role), $customer->roles)) 
                            : str_replace('_', ' ', $customer->roles) }}
                    </td>
                </tr>
            </tbody>
        </table>



        <div class="mt-3">
            <div class="border border-1 border-gray-300 rounded-md mt-5">
                <h2 class="text-lg font-semibold border border-1 p-2 border-cyan-300 bg-cyan-400 rounded-t-md">Accounting</h2>
                <table class="w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Account Code</th>
                            <th class="border border-gray-300 px-4 py-2">Account Name</th>
                            <th class="border border-gray-300 px-4 py-2">Term Type</th>
                            <th class="border border-gray-300 px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $customer->chartOfAccount->account_code}}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $customer->chartOfAccount->account_name ?? ''}}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $customer->chartOfAccount->term_type ?? ''}}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                <a
                                    href="{{ route('chartOfAccount') }}"
                                    class="inline-block px-3 py-2 bg-cyan-400 rounded-md hover:rounded-xl transform hover:scale-105 transition duration-150">
                                    Chart of Account
                                </a>

                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3">
            <div class="border border-1 border-gray-300 rounded-md mb-2">
                <div class="flex justify-between border border-1 p-2 border-cyan-300 bg-cyan-400 rounded-t-md">
                    <h2 class="text-lg font-semibold border-collapse ">Address</h2>
                    <button @click="openCreateContainer = true" class="py-1 px-2 bg-green-600 text-white rounded-lg">
                        Add Address
                    </button>
                </div>
                <div x-data="{ openCreateContainer: false }"
                    @close-create-container.window="openCreateContainer = false">
                    <div x-cloak x-show="openCreateContainer"
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start=" opacity-0"
                        x-transition:enter-end="scale-100 opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="transition ease-in duration-100 scale-100 opacity-100"
                        x-transition:leave-end="opacity-0"

                        class="fixed inset-0 bg-gray-500 bg-opacity-50 pointer-events-none z-40">
                    </div>
                    <div x-cloak x-show="openCreateContainer"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="scale-90 opacity-0"
                        x-transition:enter-end="scale-100 opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="scale-100 opacity-100"
                        x-transition:leave-end="scale-90 opacity-0"
                        class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-5xl">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold">Create Address</h2>
                                <button @click="openCreateContainer = false" class="text-gray-500 hover:text-gray-700">
                                    &times;
                                </button>
                            </div>
                            <form wire:submit.prevent="createAddress">
                                <div class="p-3">
                                    <div>
                                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                        <textarea name="" id="address" wire:model="address"></textarea>
                                        <input type="text" id=""
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Enter address">
                                        @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class=" mt-4 flex justify-end gap-2">
                                    <button type="button" @click="openCreateContainer = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                        Save Container
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <table class="w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">No</th>
                            <th class="border border-gray-300 px-4 py-2">Address</th>
                            <th class="border border-gray-300 px-4 py-2">Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customer->addresses as $a)
                        <tr class="text-center bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $a->address }}</td>
                            <td class="border border-gray-300 px-4 py-2 ">
                                <div class="flex justify-center gap-2">
                                    <button wire:click="editAddress({{ $a->id }})">
                                        <p class="font-bold text-yellow-600 hover:text-yellow-800">Edit</p>
                                    </button>
                                    <button
                                        type="button"
                                        @click="$dispatch('confirm-delete', { get_id: {{ $a->id }} })"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-confirm-delete />
            </div>
        </div>
        <div x-data="{ show: @entangle('isEditing') }">
            <!-- Overlay -->
            <div x-cloak x-show="show"
                x-transition:enter="transition ease-out duration-300 delay-150"
                x-transition:leave="transition ease-in duration-200"
                class="fixed inset-0 bg-gray-500 bg-opacity-50 z-40">
            </div>

            <!-- Modal -->
            <div x-cloak x-show="show"
                class="fixed inset-0 flex items-center justify-center z-50 px-4"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="scale-90 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-90 opacity-0">

                <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6 relative">
                    <!-- Window Close Button -->
                    <button @click="show = false"
                        class="absolute top-2 right-2 text-gray-600 hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Modal Content -->
                    <h2 class="text-lg font-semibold mb-4">Edit Data</h2>
                    <form action="" wire:submit="updateCostumer">
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" id="address" wire:model="editINGaddress"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Enter address">
                            @error('address') <span class="text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div class="gap-2 text-right">
                            <button @click="show = false"
                                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                Close
                            </button>
                            <button type="submit" class="rounded-lg bg-sky-500 text-white hover:scale-105 transition-transform py-2 px-5 hover:bg-cyan-400">Selesai</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <hr class="border border-gray-300 m-5">
        <div class="mt-5 flex  justify-end m-2">
            <a href="{{ route('listCust') }}"
                class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg 
               transform transition duration-200 ease-in-out shadow:hover-cyan-200
               hover:bg-cyan-400 hover:scale-110  ">
                Back
            </a>
        </div>
    </div>