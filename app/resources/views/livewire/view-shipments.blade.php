<div class="p-1">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Shipments') }}
        </h2>
    </x-slot>
    <div>
        <div class="flex justify-end p-2">
            <a href="{{ route('house-b-l', ['shipmentId' => $shipment->id]) }}" class="py-3 px-4 mr-4 bg-green-500 text-white rounded">
                Invoice
            </a>

        </div>
        <div class="mb-4 text-center grid grid-cols-1">
            <span class="font-bold bg-gray-200 px-4 py-2">No Job</span>
            <span class="bg-white px-4 py-2">{{ $shipment->shipment_no ?? 'N/A' }}</span>
        </div>

        <!-- Grid data dengan 3 kolom (total 9 item) -->
        <div class="grid grid-cols-3">
            <!-- Baris 1 -->
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">MAWB/MBL</span>
                <span class="bg-white px-4 py-2">{{ $shipment->shipment_id ?? 'N/A' }}</span>
            </div>
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">ETA / Estimate time arrival</span>
                <span class="bg-white px-4 py-2">{{ $shipment->estimearrival ?? 'N/A' }}</span>
            </div>
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">ETA / Estimate time Departure</span>
                <span class="bg-white px-4 py-2">{{ $shipment->estimedelivery ?? 'N/A' }}</span>
            </div>
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">Shipper</span>
                <span class="bg-white px-4 py-2">{{ $shipment->shipper ?? 'N/A' }}</span>
            </div>
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">Consignee</span>
                <span class="bg-white px-4 py-2">{{ $shipment->consignee ?? 'N/A' }}</span>
            </div>
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">Notify</span>
                <span class="bg-white px-4 py-2">{{ $shipment->notify ?? 'N/A' }}</span>
            </div>
            <!-- Baris 2 -->
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">Port of Discharge</span>
                <span class="bg-white px-4 py-2">{{ $shipment->port_of_discharge ?? 'N/A' }}</span>
            </div>
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">Place Of Reciept</span>
                <span class="bg-white px-4 py-2">{{ $shipment->place_of_receipt ?? 'N/A' }}</span>
            </div>
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">Port of Loading</span>
                <span class="bg-white px-4 py-2">{{ $shipment->port_of_loading ?? 'N/A' }}</span>
            </div>
            <!-- Baris 3 -->
            <div class="flex flex-col">
                <span class="font-bold bg-gray-200 px-4 py-2">Ocean Vessel Mother</span>
                <span class="bg-white px-4 py-2">{{ $shipment->ocean_vessel_mother ?? 'N/A' }}</span>
            </div>
            <div class="flex flex-col col-span-2">
                <span class="font-bold bg-gray-200 px-4 py-2 text-center">Description</span>
                <div x-data="{ expanded: false }">
                    {{-- Short version (e.g. 30 words) --}}
                    <p x-show="!expanded">
                        {{ Str::words($shipment->description, 30, '...') }}
                    </p>

                    {{-- Full version --}}
                    <p x-show="expanded" x-cloak>
                        {{ $shipment->description }}
                    </p>

                    {{-- Read more / Show less toggle --}}
                    <button
                        @click="expanded = !expanded"
                        class="text-blue-500 hover:underline mt-2">
                        <span x-show="!expanded">Read more</span>
                        <span x-show="expanded">Show less</span>
                    </button>
                </div>

            </div>

        </div>





        <div class="p-4 bg-white rounded-md shadow-xl shadow-cyan-50 border-2 border-gray-100 mb-5 mt-5">
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
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pcs</label>
                                    <input type="text" wire:model.defer="newContainer.pcs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('newContainer.pcs') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                                    <input type="text" wire:model.defer="newContainer.unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('newContainer.unit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">volume_weight</label>
                                    <input type="text" wire:model.defer="newContainer.volume_weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('newContainer.volume_weight') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">chargeable_weight</label>
                                    <input type="text" wire:model.defer="newContainer.chargeable_weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @error('newContainer.chargeable_weight') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                        <td class="px-4 py-2 text-sm gap-2">
                            <button wire:click="editContainer({{ $container->id }})" class="text-indigo-600 hover:text-indigo-600">Edit</button>
                            <button wire:click="$dispatch('confirm-apus', { get_id: {{ $container->id }} })" class="text-red-500">Delete
                            </button>
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
            <div class="border rounded-lg overflow-hidden">
                <div x-data="{ open: false }" @close-modal.window="open = false"
                    x-ref="modalContent">
                    <div class=" flex justify-end mb-4 p-4">
                        <a href="{{ route('sale-invoice', ['shipmentId' => $shipment->id]) }}" class="py-3 px-4 mr-4 bg-green-500 text-white rounded">
                            Invoice
                        </a>
                        <a href="{{ route('purchase-invoice', ['shipmentId' => $shipment->id]) }}" class="p-4 bg-red-500 text-white rounded-lg mr-4">
                            Print PI
                        </a>
                        <button @click="open = true; $dispatch('reloadTransactionData', { shipmentId: '{{ $shipment->id }}' })"
                            class="py-3 px-4 bg-blue-600 text-white rounded-lg">
                            Add Cost
                        </button>
                    </div>

                    <!-- Background Overlay -->
                    <div x-cloak x-show="open"
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:leave="transition ease-in duration-200"
                        class="fixed inset-0 bg-gray-500 bg-opacity-50 z-40">
                    </div>

                    <!-- Modal Container -->
                    <div x-cloak x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="scale-90 opacity-0"
                        x-transition:enter-end="scale-100 opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="scale-100 opacity-100"
                        x-transition:leave-end="scale-90 opacity-0"
                        class="fixed inset-0 flex items-center justify-center z-50 px-4">
                        <div class="bg-white rounded-lg shadow-md w-full max-w-7.5xl">
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
                            <livewire:accounting.tranksaksi :shipmentId="$shipment->id" :key="'transaction-' . $shipment->id" />
                            <!-- End Form -->
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr class="divide-x divide-gray-200 dark:divide-neutral-700">
                                <th class="p-3 text-center text-xs font-medium text-gray-500 uppercase">Edit</th>
                                <th class="p-4 text-center text-xs font-medium text-gray-500 uppercase">Line No</th>
                                <th class="p-6 text-center text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Unit</th>
                                <th class="px-6 py-3 bg-orange-500 text-center text-xs font-medium text-gray-100 uppercase">Client</th>
                                <th class="px-6 py-3 bg-orange-500 text-center text-xs font-medium text-gray-100 uppercase">Sale</th>
                                <th class="px-6 py-3 bg-orange-500 text-center text-xs font-medium text-gray-100 uppercase">Amount (IDR)</th>
                                <th class="px-6 py-3 bg-orange-500 text-center text-xs font-medium text-gray-100 uppercase">Dr/Cr</th>
                                <th class="px-6 py-3 bg-blue-500 text-center text-xs font-medium text-gray-100 uppercase">Vendor</th>
                                <th class="px-6 py-3 bg-blue-500 text-center text-xs font-medium text-gray-100 uppercase">Cost</th>
                                <th class="px-6 py-3 bg-blue-500 text-center text-xs font-medium text-gray-100 uppercase">Amount (IDR)</th>
                                <th class="px-6 py-3 bg-blue-500 text-center text-xs font-medium text-gray-100 uppercase">Dr/Cr</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Freight</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Gross Profit</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            @forelse($shipment->transactions as $transaction)
                            <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700 divide-x divide-gray-200">
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center gap-2 items-center p-2">
                                        <button wire:click="editTransaction({{ $transaction->id }})">
                                            <i class="fa-solid fa-pen-to-square fa-2xl" style="color: #FFD43B;"></i> </button>
                                        <button wire:click="$dispatch('confirm-delete', { get_id: {{ $transaction->id }} })">
                                            <i class="fa-solid fa-trash fa-2xl" style="color: #ff0000;"></i> </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 text-center">{{ $transaction->description ?? '' }}</td>
                                <td class="px-6 py-4 text-center">{{ $transaction->unit ?? '' }}</td>
                                <td class="px-6 py-4 text-center bg-orange-500 text-gray-100">{{ $transaction->sclient ?? '' }}</td>
                                <td class="px-6 py-4 text-center bg-orange-500 text-gray-100">{{ $transaction->quantity }} x {{$transaction->samount_qty}}x{{$transaction->srate}}</td>
                                <td class="px-6 py-4 text-center bg-orange-500 text-gray-100">{{ $transaction->samountidr ?? '' }}</td>
                                <td class="px-6 py-4 text-center bg-orange-500 text-gray-100">{{ $transaction->sdrcr ?? '' }}</td>
                                <td class="px-6 py-4 text-center bg-blue-500 text-gray-100">{{ $transaction->cvendor ?? '' }}</td>
                                <td class="px-6 py-4 text-center bg-blue-500 text-gray-100">{{ $transaction->cost ?? '' }}</td>
                                <td class="px-6 py-4 text-center bg-blue-500 text-gray-100">{{ $transaction->camountidr ?? '' }}</td>
                                <td class="px-6 py-4 text-center bg-blue-500 text-gray-100">{{ $transaction->cdrcr ?? '' }}</td>
                                <td class="px-6 py-4 text-center ">{{ $transaction->freight ?? '' }}</td>
                                <td class="px-6 py-4 text-center">
                                    {{ number_format(
                                    floatval(str_replace(',', '.', str_replace('.', '', $transaction->samountidr))) *
                                    floatval(str_replace(',', '.', str_replace('.', '', $transaction->srate))) - floatval(str_replace(',', '.', str_replace('.', '', $transaction->camountidr))) ,
                                    2, ',', '.'
                                ) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="14" class="bg-cyan-500 text-white p-4 text-center">Tidak ada transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div x-data="{ show: @entangle('isEditing') }">
            <div x-cloak x-show="show"
                x-transition:enter="transition ease-out duration-300 delay-150"
                x-transition:leave="transition ease-in duration-200"
                class="fixed inset-0 bg-gray-500 bg-opacity-50 z-40">
            </div>
            <div x-cloak x-show="show"
                class="fixed inset-0 flex items-center justify-center z-50 px-4"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="scale-90 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-90 opacity-0">

                <!-- Modal Content -->


                <!-- Form or content for editing transaction -->
                <livewire:accounting.edit-transaction
                    :transactionId="$transactionId"
                    :shipmentId="$shipmentId"
                    :key="'edit-transaction-'.$transactionId" />

                <!-- Close button -->
            </div>
        </div>



        <hr class="border-gray-500 dark:border-neutral-500 mt-5">


        <!-- SIGMA Button -->
        <div class="p-4 flex justify-end">
            <a href="{{ url ('/shipments') }}"
                class="py-3 px-9 inline-flex items-center gap-x-2 shadow-sm text-sm font-medium rounded-lg bg-blue-300 text-blue-800 hover:bg-blue-200 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
                Back
            </a>
        </div>
    </div>
    <div x-data @confirm-delete.window="
    const get_id = $event.detail.get_id;
    
    Swal.fire({
        title: 'Are you sure?, <br> want to delete this transaction?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $wire.confirmDelete(get_id).then(() => {
                Swal.fire('Deleted!', 'Data berhasil dihapus.', 'success');
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Cancelled', 'Data batal dihapus.', 'error');
        }
    });
"></div>

    <div x-data @confirm-apus.window="
    const get_id = $event.detail.get_id;
    
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $wire.containerDelete(get_id).then(() => {
                Swal.fire('Deleted!', 'Data berhasil dihapus.', 'success');
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            Swal.fire('Cancelled', 'Data batal dihapus.', 'error');
        }
    });
"></div>

</div>