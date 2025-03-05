<div class="p-1">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Shipments') }}
        </h2>
    </x-slot>
    <div class="max-w-full mx-auto p-4 shadow-md shadow-cyan-100 rounded-md mb-8">
        <div class="grid grid-cols-4 border-1border-gray-200 ">
            <!-- First Row (Labels) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Shipment ID</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Shipper</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Consignee</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Notify</div>

            <!-- First Row (Data) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->shipment_id ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->shipper ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->consignee ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->notify ?? 'N/A' }}</div>

            <!-- Second Row (Labels) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Ocean Vessel Feeder</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Ocean Vessel Mother</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Port of Discharge</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Port of Loading</div>
            <!-- Second Row (Data) -->

            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->ocean_vessel_feeder ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->ocean_vessel_mother ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->port_of_discharge ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->port_of_loading ?? 'N/A' }}</div>



            <!-- Third Row (Labels) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Combined Transport</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Description</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Other Info</div>


            <!-- Third Row (Data) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->combined_transport ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->description ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ 'N/A' }}</div> <!-- Assuming no other info, replace as needed -->


        </div>
    </div>




    <div class="p-4 bg-white rounded-md shadow-xl shadow-cyan-50 border-2 border-gray-100 mb-5">
        <!-- Create Container Modal using Alpine.js -->
        <div x-data="{ openCreateContainer: false }">
            <div class="flex justify-end">
                <button @click="openCreateContainer = true" class="py-3 px-4 bg-green-600 text-white rounded-lg">
                    Add Container
                </button>
            </div>

            <div x-cloak x-show="openCreateContainer"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="scale-90 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-90 opacity-0"
                class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Create Container</h2>
                        <button @click="openCreateContainer = false" class="text-gray-500 hover:text-gray-700">
                            &times;
                        </button>
                    </div>
                    <form wire:submit.prevent="createContainer">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Container ID</label>
                                <input type="text" wire:model.defer="newContainer.container_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('newContainer.container_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Container Type</label>
                                <input type="text" wire:model.defer="newContainer.container_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('newContainer.container_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Container Seal</label>
                                <input type="text" wire:model.defer="newContainer.container_seal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('newContainer.container_seal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Gross Weight</label>
                                <input type="text" wire:model.defer="newContainer.gross_weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('newContainer.gross_weight') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pack Type</label>
                                <input type="text" wire:model.defer="newContainer.pack_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('newContainer.pack_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Measurement</label>
                                <input type="text" wire:model.defer="newContainer.measurement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                @error('newContainer.measurement') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end gap-2">
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
        <h3 class="text-xl font-semibold mb-2">Containers</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">No</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Container ID</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Seal</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Pack Type</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Gross Weight</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Measurement</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($shipment->containers as $container)
                <tr>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $container->container_id }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $container->container_type }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $container->container_seal }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $container->pack_type }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $container->gross_weight }}</td>
                    <td class="px-4 py-2 text-sm text-gray-800">{{ $container->measurement ?? 'N/A'}}</td>
                    <td class="px-4 py-2 text-sm">
                        <button wire:click="editContainer({{ $container->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                        <button wire:click="deleteContainer({{ $container->id }})" class="ml-2 text-red-600 hover:text-red-900">Delete</button>
                    </td>
                </tr>
                @endforeach
                @if($shipment->containers->isEmpty())
                <tr>
                    <td colspan="7" class="px-4 py-2 text-center text-sm text-gray-500">No containers found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>



    <div class="border rounded-lg overflow-hidden">
        <div x-data="{ open: false }">
            <!-- Button to open modal -->
            <div class="flex justify-end mb-4 p-4">
                <button @click=" open = true " class="py-3 px-4 bg-blue-600 text-white rounded-lg">
                    Add Cost
                </button>
            </div>

            <!-- Background Overlay -->
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
                class="fixed inset-0 flex items-center justify-center z-50 pointer-events-auto px-4">
                <div class="bg-white rounded-lg shadow-md w-full max-w-7,5sxl ">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center p-4 border-b">
                        <h2 class="text-lg font-semibold text-gray-800">Costing</h2>
                        <button @click="open = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <!-- Form -->
                    @livewire('accounting.tranksaksi', ['shipmentId' => $shipment->id])
                    <!-- End Form -->
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="divide-y divide-gray-200 dark:divide-neutral-700">
                    <tr class="divide-x divide-gray-200 dark:divide-neutral-700">
                        <th scope="col" class="p-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Edit</th>
                        <th scope="col" class="p-4 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Line No</th>
                        <th scope="col" class="p-6 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Description</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Unit</th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-center text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Client</th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-center text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Sale</th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-center text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Amount (IDR)</th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-center text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Dr/Cr</th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-center text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Vendor</th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-center text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Cost</th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-center text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Amount (IDR)</th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-center text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Dr/Cr</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Freight</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Gross Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    @forelse($shipment->transactions as $transaction)
                    <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700 divide-x divide-gray-200 dark:divide-neutral-700">
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-800 dark:text-neutral-200">
                            <button class="w-10 h-10 rounded-xl">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-medium text-gray-800 dark:text-neutral-200">
                            {{ $transaction->line_no ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-medium text-gray-800 dark:text-neutral-200">
                            {{ $transaction->description ?? 'No Data' }}
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-medium text-gray-800 dark:text-neutral-200">
                            {{ $transaction->unit ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-medium bg-orange-500 text-gray-100 dark:text-neutral-200">
                            {{ $transaction->sclient ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-medium bg-orange-500 text-gray-100 dark:text-neutral-200">
                            {{ $transaction->sale ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-medium bg-orange-500 text-gray-100 dark:text-neutral-200">
                            {{ $transaction->samountidr ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-medium bg-orange-500 text-gray-100 dark:text-neutral-200">
                            {{ $transaction->sdrcr ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium bg-blue-500 text-gray-100 dark:text-neutral-200">
                            {{ $transaction->cvendor ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium bg-blue-500 text-gray-100 dark:text-neutral-200">
                            {{ $transaction->cost ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium bg-blue-500 text-gray-100 dark:text-neutral-200">
                            {{ $transaction->camountidr ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium bg-blue-500 text-gray-100 dark:text-neutral-200">
                            {{ $transaction->cdrcr ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $transaction->freight ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $transaction->gross_profit ?? 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="14" class="bg-cya-500 text-white p-4">
                            <div class="flex items-center justify-center h-full">
                                Tidak ada transaksi.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <hr class="border-gray-300 dark:border-neutral-200 mt-4 ">


    <!-- SIGMA Button -->
    <div class="p-4 flex justify-end">
        <a href="{{ url ('/shipments') }}"
            class="py-3 px-9 inline-flex items-center gap-x-2 shadow-sm text-sm font-medium rounded-lg bg-blue-300 text-blue-800 hover:bg-blue-200 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
            Back
        </a>
    </div>
    @if($editingContainerId)
    <div wire:transition.scale.origin.opacity class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-lg">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Edit Container</h2>
                <button wire:click="cancelEdit" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form wire:submit.prevent="updateContainer">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Container ID</label>
                        <input type="text" wire:model.defer="editContainerData.container_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('editContainerData.container_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Container Type</label>
                        <input type="text" wire:model.defer="editContainerData.container_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        @error('editContainerData.container_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Container Seal</label>
                        <input type="text" wire:model.defer="editContainerData.container_seal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gross Weight</label>
                        <input type="text" wire:model.defer="editContainerData.gross_weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pack Type</label>
                        <input type="text" wire:model.defer="editContainerData.pack_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Measurement</label>
                        <input type="text" wire:model.defer="editContainerData.measurement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" wire:click="cancelEdit" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                        Update Container
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>