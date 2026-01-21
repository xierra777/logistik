<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quotation List') }}
        </h2>
    </x-slot>

    <div class="mt-4 shadow-lg ">
        <div class="bg-gray-400 rounded-t-lg">
            <div class=" ">
                <div x-data="{ open: false }" @keydown.escape.window="open = false"
                    @close-transaction-modal.window="open = false" x-ref="modalContent">
                    <div class="flex items-center justify-between p-3 ">
                        <div class="flex-1"></div> <!-- Spacer kiri -->
                        <p class="font-bold  text-center">TRANSACTION</p>
                        <div class="flex-1 flex justify-end gap-2">
                            <button @click="open = true;"
                                class="py-2 px-3 bg-blue-600 text-white rounded-lg text-sm">Add Cost</button>

                        </div>
                    </div>
                    <!-- Background Overlay -->
                    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:leave="transition ease-in duration-200"
                        class="fixed inset-0 bg-gray-500 bg-opacity-50 z-40">
                    </div>

                    <!-- Modal Container -->
                    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="scale-90 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-90 opacity-0"
                        class="fixed inset-0 flex items-center justify-center z-50 px-4">
                        <div class="bg-white rounded-2xl shadow-md w-full max-w-7.5xl">
                            <!-- Modal Header -->
                            <div class="relative flex items-center p-4 border-b">
                                <h2 class="absolute left-1/2 -translate-x-1/2 text-lg font-semibold text-gray-800">
                                    Quotation
                                </h2>

                                <button @click="open = false"
                                    class="ml-auto text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Form -->
                            <div>
                                <div class=" gap-4 p-4 w-full ">
                                    <div class="flex gap-4">
                                        <div class="w-full">
                                            <label for="" class="font-semibold">Company Name</label>
                                            <select wire:model="sclient" id="sclient"
                                                class="w-full border rounded-md border-gray-300 p-2">
                                                <option value="">-- Pilih Client --</option>
                                            </select>
                                        </div>
                                        <div class="port-container" data-model="port_of_loading"
                                            data-radio-name="i nputTypeLoading" wire:ignore
                                            wire:change="port_of_loading">
                                            <h2 class="text-lg font-semibold">Port Of Loading / POL</h2>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Select or Input
                                                    Port</label>
                                                <div class="flex items-center gap-3">
                                                    <label class="cursor-pointer">
                                                        <input type="radio" value="select" name="inputTypeLoading"
                                                            checked> Select from List
                                                    </label>
                                                    <label class="cursor-pointer">
                                                        <input type="radio" value="input" name="inputTypeLoading"> Enter
                                                        Port Manually
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- Select Dropdown -->
                                            <div class="select-container">
                                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                                <select wire:model="port_of_loading"
                                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                                    <option value="" disabled selected>Select a port...</option>
                                                </select>
                                            </div>
                                            <!-- Input Field -->
                                            <div class="input-container hidden">
                                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                                <input wire:model="port_of_loading" type="text"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                                    placeholder="Enter port name">
                                            </div>
                                        </div>

                                        <div class="port-container" data-model="port_of_final"
                                            data-radio-name="inputTypeFinal" wire:ignore wire:change="port_of_final">
                                            <h2 class="text-lg font-semibold">Port Of Final / POF</h2>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Select or Input
                                                    Port</label>
                                                <div class="flex items-center gap-3">
                                                    <label class="cursor-pointer">
                                                        <input type="radio" value="select" name="inputTypeFinal"
                                                            checked> Select from List
                                                    </label>
                                                    <label class="cursor-pointer">
                                                        <input type="radio" value="input" name="inputTypeFinal"> Enter
                                                        Port Manually
                                                    </label>
                                                </div>
                                            </div>
                                            <!-- Select Dropdown -->
                                            <div class="select-container">
                                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                                <select wire:model="port_of_final"
                                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                                    <option value="" disabled selected>Select a port...</option>
                                                </select>
                                            </div>
                                            <!-- Input Field -->
                                            <div class="input-container hidden">
                                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                                <input wire:model="port_of_final" type="text"
                                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                                    placeholder="Enter port name">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table-hover min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-center">
                <thead>
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            No
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Description
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Unit
                        </th>
                        <th scope="col"
                            class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Client
                        </th>
                        <th scope="col"
                            class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Sale
                        </th>
                        <th scope="col"
                            class="px-6 py-3 bg-orange-500 text-xs whitespace-nowrap font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Amount (IDR)
                        </th>
                        <th scope="col"
                            class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Dr/Cr
                        </th>
                        <th scope="col"
                            class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Vendor
                        </th>
                        <th scope="col"
                            class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Cost
                        </th>
                        <th scope="col"
                            class="px-6 py-3 bg-blue-500 text-xs whitespace-nowrap font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Amount (IDR)
                        </th>
                        <th scope="col"
                            class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Dr/Cr
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Freight
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Gross Profit
                        </th>
                    </tr>
                </thead>
                <tbody class="">

                    <tr>
                        <td scope="col"
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        </td>
                        <td scope="col"
                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            <div class="flex items-center space-x-3">
                                <!-- Delete Button -->
                                <div x-data>
                                    <button
                                        class="px-3 py-2 bg-red-600 text-white rounded-full hover:scale-105 hover:bg-red-700 transition-transform"
                                        @click="
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'You won\'t be able to revert this!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d8',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'No, Keep it',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $wire.confirmDelete();
                            }
                        })
                    ">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>

                                <!-- Update Button -->
                                <button type="button" wire:click="editTransaction( )"
                                    class="px-3 py-2 bg-blue-500 rounded-full text-white hover:bg-blue-600 transition transform hover:scale-105">
                                    <i class="fa-solid fa-pen-to-square"></i> </button>
                            </div>
                        </td>


                    </tr>
                    <tr wire:loading.remove>
                        <td colspan="13" class="py-12 text-center">
                            <div class="flex flex-col text-center items-center justify-center">
                                <img src="{{ asset('images/nodata.svg') }}" alt="No dataShipments illustration"
                                    class="w-64 h-48 mb-4 opacity-75  dark:opacity-50">
                                <p class="text-md font-medium text-gray-600 dark:text-neutral-300">
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr wire:loading class="animate-pulse">
                        <td colspan="6" class="py-12 text-center text-gray-500 dark:text-neutral-400">
                            Retrieving dataShipments…
                        </td>
                    </tr>
                </tbody>
            </table>
            <x-confirm-delete :message="'Are you sure you want to delete this transaction?'"
                :key="'confirm-delete-job-transaction-' . now()->timestamp" />
        </div>
    </div>
</div>