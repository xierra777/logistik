<div paddingClass="p-0">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Shipments') }}
        </h2>
    </x-slot>
    <div class="max-w-full mx-auto p-4">
        <div class="grid grid-cols-4 border-1border-gray-200">
            <!-- First Row (Labels) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Shipment ID</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Container ID</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Container Type</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Shipper</div>

            <!-- First Row (Data) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->shipment_id ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->container_id ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->container_type ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->shipper ?? 'N/A' }}</div>

            <!-- Second Row (Labels) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Consignee</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Notify</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Ocean Vessel Feeder</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Ocean Vessel Mother</div>

            <!-- Second Row (Data) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->consignee ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->notify ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->ocean_vessel_feeder ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->ocean_vessel_mother ?? 'N/A' }}</div>

            <!-- Third Row (Labels) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Port of Discharge</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Combined Transport</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Port of Loading</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Packages</div>

            <!-- Third Row (Data) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->port_of_discharge ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->combined_transport ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->port_of_loading ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->packages ?? 'N/A' }}</div>

            <!-- Fourth Row (Labels) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Description</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Gross Weight</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Measurement</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-gray-200 font-bold">Other Info</div>

            <!-- Fourth Row (Data) -->
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->description ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->gross_weight ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ $shipment->measurement ?? 'N/A' }}</div>
            <div class="border-1border-gray-200 px-4 py-2 bg-white">{{ 'N/A' }}</div> <!-- Assuming no other info, replace as needed -->
        </div>
    </div>
    <div x-data="{ open: false }">
        <!-- Button to open modal -->
        <div class="flex justify-start mb-4 p-4">
            <button @click="open = true" class="py-3 px-4 bg-blue-600 text-white rounded-lg">
                Open Modal
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
            <div class="bg-white rounded-lg shadow-lg w-full max-w-7,5sxl ">
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

                <!-- Modal Content -->
                <form action="POST" class="px-4 max-h-[80vh] overflow-y-auto
                [&::-webkit-scrollbar]:w-2
                [&::-webkit-scrollbar]:hidden">
                    <div class="flex justify-center m-2 rounded p-3">
                        <label for="" class="text-gray-500"> " this Modal Is Scrollable ;D"</label>
                    </div>
                    <div class="space-y-4 rounded">
                        <div class="flex flex-col">
                            <livewire:accounting.charge />
                        </div>
                        <div class="flex flex-col">
                            <livewire:accounting.sell />
                        </div>
                        <div class="flex flex-col">
                            <livewire:accounting.cost />
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end mt-4 gap-2 p-4 border-t color-gray-200">
                        <button type="button" @click="open = false"
                            class="px-4 py-2 bg-gray-500 text-white rounded-lg">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                            Add
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="border rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                <thead class="divide-y divide-gray-200 dark:divide-neutral-700">
                    <tr class="divide-x divide-gray-200 dark:divide-neutral-700">
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Edit
                        </th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Line No</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Description</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Unit</th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-start text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Client</th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-start text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Sale</th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-start text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Amount (IDR)</th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-start text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Dr/Cr</th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-start text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Vendor</th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-start text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Cost</th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-start text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Amount (IDR)</th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-start text-xs font-medium text-gray-100 uppercase dark:text-neutral-500">Dr/Cr</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Freight</th>
                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">Gross Profit</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                    <tr class="hover:bg-gray-100 dark:hover:bg-neutral-700 divide-x divide-gray-200 dark:divide-neutral-700">
                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-neutral-200">
                            <button class="flex items-center gap-x-2 text-sm font-medium rounded-lg bg-blue-300 text-blue-800 hover:bg-blue-200 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path
                                        d="M12 4a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2h-6v6a1 1 0 1 1-2 0v-6H5a1 1 0 1 1 0-2h6V5a1 1 0 0 1 1-1z">
                                    </path>
                                </svg>
                            </button>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-neutral-200">John Brown</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-neutral-200">Sample Desc</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-800 dark:text-neutral-200">Unit</td>
                        <td class="px-6 py-4 text-sm font-medium bg-orange-500 text-gray-100 dark:text-neutral-200">Client</td>
                        <td class="px-6 py-4 text-sm font-medium bg-orange-500 text-gray-100 dark:text-neutral-200">Sale</td>
                        <td class="px-6 py-4 text-sm font-medium bg-orange-500 text-gray-100 dark:text-neutral-200">1234567</td>
                        <td class="px-6 py-4 text-sm font-medium bg-orange-500 text-gray-100 dark:text-neutral-200">Dr</td>
                        <td class="px-6 py-4 text-sm font-medium bg-blue-500 text-gray-100 dark:text-neutral-200">Vendor</td>
                        <td class="px-6 py-4 text-sm font-medium bg-blue-500 text-gray-100 dark:text-neutral-200">Cr</td>
                        <td class="px-6 py-4 text-sm font-medium bg-blue-500 text-gray-100 dark:text-neutral-200">Dr</td>
                        <td class="px-6 py-4 text-sm font-medium bg-blue-500 text-gray-100 dark:text-neutral-200">Cr</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <!-- SIGMA Button -->
    <div class="pt-4 flex justify-end">
        <a href="/shipments"
            class="py-3 px-9 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-blue-300 text-blue-800 hover:bg-blue-200 focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
            SIGMA
        </a>
    </div>
</div>