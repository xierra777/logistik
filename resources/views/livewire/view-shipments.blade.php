<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Shipments') }}
        </h2>
    </x-slot>
    <div class="max-w-full mx-auto">
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

    <div class="pt-4 flex justify-end">
        <a class="py-3 px-9 nline-flex items-center gap-x-2 text-sm font-medium rounded-lg border-transparent bg-blue-300 text-blue-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:hover:bg-blue-900 dark:focus:bg-blue-900 text-stone-50 hover:text-stone-500" href="/shipments">SIGMA</a>
    </div>



</div>