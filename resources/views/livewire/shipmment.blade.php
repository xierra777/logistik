<div class="flex flex-col">
    <div class="-m-1.5 overflow-x-auto">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 px-1.5">
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="/shipment"
                    class="py-3 px-9 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-100 text-blue-800 hover:bg-blue-200 focus:outline-none focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:hover:bg-blue-900 dark:focus:bg-blue-900">
                    Tambah data
                </a>
                <button wire:click="downloadExcel"
                    class="py-3 px-9 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-300 text-green-800 hover:bg-green-200 focus:outline-none focus:bg-blue-200 disabled:opacity-50 disabled:pointer-events-none dark:text-blue-400 dark:hover:bg-blue-900 dark:focus:bg-blue-900">
                    Export Data
                </button>
            </div>
            <div class="flex items-center gap-2">
                <input type="date" wire:model.live="start_date"
                    class="block w-full sm:w-48 text-sm rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                <span class="text-gray-500 dark:text-neutral-400">to</span>
                <input type="date" wire:model.live="end_date"
                    class="block w-full sm:w-48 text-sm rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
            </div>
        </div>

        <!-- File Upload Form -->
        <div class="max-w-sm mb-6 px-1.5">
            <form wire:submit.prevent="importExcel">
                <label class="block">
                    <span class="sr-only">Choose Excel file</span>
                    <input type="file" wire:model="file"
                        class="block w-full text-sm text-gray-500
                                  file:me-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-blue-600 file:text-white
                                  hover:file:bg-blue-700 file:disabled:opacity-50 file:disabled:pointer-events-none
                                  dark:file:bg-neutral-700 dark:file:text-neutral-300">
                </label>
                @error('file')
                <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
                <button type="submit"
                    class="mt-3 py-2 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    Import Excel
                </button>
            </form>
        </div>

        <!-- Status Messages -->
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
        <div class="p-1.5 min-w-full inline-block align-middle">
            <div class="overflow-x-auto table-container">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                    <thead class="bg-gray-50 dark:bg-neutral-800">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">No</th>
                            <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400"></th>
                            <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">B/L</th>
                            <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Shipper</th>
                            <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Consignee</th>
                            <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900">
                        @forelse($shipments as $shipment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox"
                                    wire:model="mySelected"
                                    value="{{ $shipment->id }}"
                                    class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $shipment->shipment_id }}</td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $shipment->shipper }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-300">{{ $shipment->consignee }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium space-x-3">
                                <button wire:navigate href="/view-shipments/{{ $shipment->id }}"
                                    class="font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    View
                                </button>
                                <button wire:navigate href="/edit-shipments/{{ $shipment->id }}"
                                    class="font-bold text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300">
                                    Update
                                </button>
                                <button type="button"
                                    @click="$dispatch('confirm-delete', { get_id: {{ $shipment->id }} })"
                                    class="font-bold text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    Delete
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center max-w-md mx-auto">
                                    <img src="{{ asset('./images/nodata.svg') }}"
                                        alt="No data illustration"
                                        class="w-64 h-48 mb-4 opacity-75 dark:opacity-50">
                                    <p class="text-gray-600 dark:text-neutral-300 text-lg font-medium mb-2">
                                        No shipments found!
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-neutral-500 text-center">
                                        Start by add shipments or importing data using the Excel upload above.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
                <x-confirm-delete />
                <!-- <p>Showing {{ $perPage }} rows per page.</p> -->
            </div>

            <!-- Pagination -->
            @if($shipments->hasPages())
            <div class="mt-4 px-1.5">
                {{ $shipments->links() }}
            </div>
            @endif
            <br>
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