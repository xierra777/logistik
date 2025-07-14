@section('title', 'Shipments')

<div class="p-6">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shipments') }}
        </h2>
    </x-slot>
    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto justify-end mb-4">
        <a href="/create-shipment"
            class="py-3 px-9 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:hover:bg-blue-900 dark:focus:bg-blue-900">
            Tambah Shipments
        </a>
    </div>
    <div class="flex justify-end w-full">
        <div class="flex flex-col items-center gap-2" wire:ignore>
            <div class="flex space-x-2 w-full text-center rounded-lg border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-2">
                <input type="date" wire:model.live="start_date"
                    class="block w-full sm:w-48 text-sm rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                <input type="date" wire:model.live="end_date"
                    class="block w-full sm:w-48 text-sm rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
            </div>
            <div class="flex space-x-2 w-full mb-4 rounded-lg border border-gray-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-2">
                <select data-hs-select='{
                        "placeholder": "Select option...",
                        "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
                        "toggleClasses": "hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 relative py-3 ps-4 pe-9 flex gap-x-2 text-nowrap w-full cursor-pointer bg-white border border-gray-200 rounded-lg text-start text-sm focus:outline-hidden focus:ring-2 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:focus:outline-hidden dark:focus:ring-1 dark:focus:ring-neutral-600",
                        "dropdownClasses": "mt-2 z-50 w-full max-h-72 p-1 space-y-0.5 bg-white border border-gray-200 rounded-lg overflow-hidden overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-neutral-700 dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500 dark:bg-neutral-900 dark:border-neutral-700",
                        "optionClasses": "py-2 px-4 w-full text-sm text-gray-800 cursor-pointer hover:bg-gray-100 rounded-lg focus:outline-hidden focus:bg-gray-100 hs-select-disabled:pointer-events-none hs-select-disabled:opacity-50 dark:bg-neutral-900 dark:hover:bg-neutral-800 dark:text-neutral-200 dark:focus:bg-neutral-800",
                        "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"hidden hs-selected:block\"><svg class=\"shrink-0 size-3.5 text-blue-600 dark:text-blue-500 \" xmlns=\"http:.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><polyline points=\"20 6 9 17 4 12\"/></svg></span></div>",
                        "extraMarkup": "<div class=\"absolute top-1/2 end-3 -translate-y-1/2\"><svg class=\"shrink-0 size-3.5 text-gray-500 dark:text-neutral-500 \" xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"m7 15 5 5 5-5\"/><path d=\"m7 9 5-5 5 5\"/></svg></div>"
                        }' class="hidden" wire:model.live="searchField">
                    <option value="shipment_id">Shipment ID</option>
                    <option value="client">Client</option>
                    <option value="type_job">Type Job</option>

                </select>
                <input type="text"
                    wire:model.live="searchTerm"
                    placeholder="Type your query…"
                    class="border p-2 rounded-lg flex-1 border border-gray-300  " />
            </div>
        </div>
    </div>

    <div class="p-1.5 min-w-full inline-block align-middle">
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
        <div class="overflow-auto max-w-full">

        </div>
    </div>
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
            <div class="w-full overflow-x-auto rounded-lg border dark:border-neutral-700">
                <table class="min-w-max w-full table-auto divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            @foreach (['Shipment No', 'Client', 'Department', 'POL', 'POD', 'ETD', 'ETA', 'Action'] as $th)
                            <th scope="col" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase dark:text-neutral-400 ">
                                {{ $th }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-700">
                        @forelse($shipment as $s)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                            <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-neutral-200">
                                <a href="{{ route('viewShipment', ['id' => $s->id]) }}">
                                    {{ $s->shipment_id }}
                                </a>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-800 dark:text-neutral-300">
                                <a href="{{ 'view-customers/' . $s->shipmentClient_id }}"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-bold">
                                    {{ $s->client->name ?? '' }}
                                </a>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-800 dark:text-neutral-300 font-bold">
                                {{ strtoupper(str_replace('_', ' ', $s->shipmentsTypeJob)) }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-800 dark:text-neutral-300 truncate max-w-[200px]">
                                {{ $s->dataShipments['shipmentPort_of_loading'] ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-800 dark:text-neutral-300 truncate max-w-[200px]">
                                {{ $s->dataShipments['shipmentPort_of_discharge'] ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-800 dark:text-neutral-300">
                                {{ \Carbon\Carbon::parse($s->dataShipments['shipmentEstimearrival'] ?? now())->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-800 dark:text-neutral-300">
                                {{ \Carbon\Carbon::parse($s->dataShipments['shipmentEstimedelivery'] ?? now())->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right">
                                <div class="flex justify-center space-x-3 font-bold">
                                    <button
                                        wire:navigate
                                        href="{{ route('viewShipment', ['id' => $s->id]) }}"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        View
                                    </button>
                                    <button
                                        wire:navigate
                                        href="{{ route('updateShipment', ['id' => $s->id]) }}"
                                        class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                        Update
                                    </button>
                                    <button
                                        type="button"
                                        @click="$dispatch('confirm-delete', { get_id: {{ $s->id }} })"
                                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr wire:loading.remove>
                            <td colspan="8" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <img src="{{ asset('images/nodata.svg') }}"
                                        alt="No data illustration"
                                        class="w-64 h-48 mb-4 opacity-75 dark:opacity-50">
                                    <p class="text-lg font-medium text-gray-600 dark:text-neutral-300">
                                        No shipments found!
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-neutral-500">
                                        Start by adding shipments or importing data.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        <tr wire:loading class="animate-pulse">
                            <td colspan="8" class="py-12 text-center text-gray-500 dark:text-neutral-400">
                                Retrieving data…
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <x-confirm-delete />
            </div>


            <!-- Pagination -->
            @if($shipment->hasPages())
            <div class="mt-4 px-1.5 flex justify-end">
                {{ $shipment->links() }}
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