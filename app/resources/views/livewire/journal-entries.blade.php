<div>

    @if (session()->has('message'))
    <div style="color:green;">{{ session('message') }}</div>
    @endif

    @if (session()->has('error'))
    <div style="color:red;">{{ session('error') }}</div>
    @endif

    <div x-data="{ step: 1 }">
        <div x-show="step === 1" class="p-4">
            <h1 class="text-2xl text-center mb-4 font-bold">Create Shipments</h1>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <label for="hs-radio-air-inbound" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="airInbound" id="hs-radio-air-inbound">
                    <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Air Inbound</span>
                </label>
                <label for="hs-radio-air-outbound" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 ring-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="airOutbound" id="hs-radio-air-outbound">
                    <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Air Outbound</span>
                </label>
                <label for="hs-radio-domestic-transport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="domesticTransportation" id="hs-radio-domestic-transport">
                    <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Domestic Transportation</span>
                </label>
                <label for="hs-radio-local-truck" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="localTruck" id="hs-radio-local-truck">
                    <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Local Truck</span>
                </label>
                <label for="hs-radio-logistics" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="Logistics" id="hs-radio-logistics">
                    <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Logistics</span>
                </label>
                <label for="hs-radio-OceanFclExport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="OceanFclExport" id="hs-radio-OceanFclExport">
                    <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Ocean FCL Export</span>
                </label>
                <label for="hs-radio-OceanFclImport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="OceanFclImport" id="hs-radio-OceanFclImport">
                    <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Ocean FCL Import</span>
                </label>
                <label for="hs-radio-OceanLclBulkExport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="OceanLclBulkExport" id="hs-radio-OceanLclBulkExport">
                    <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Ocean LCL/Bulk Export</span>
                </label>
                <label for="hs-radio-OceanLclBulkImport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
                    <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="OceanLclBulkImport" id="hs-radio-OceanLclBulkImport">
                    <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Ocean LCL/Bulk Import</span>
                </label>

            </div>
        </div>

        <div x-show="step === 2">
            <label for="email">Email</label>
            <input type="email" id="email">
        </div>

        <div x-show="step === 3">
            <label for="address">Address</label>
            <input type="text" id="address">
        </div>

        <div class="mt-3 p-4 gap-2">
            <button @click="step > 1 && step--" class="bg-red-500 px-6 py-3 rounded-sm text-white" :disabled="step === 1">Previous</button>
            <button @click="step < 3 && step++" class="bg-green-600 px-6 py-3 rounded-sm text-white" :disabled="step === 3">Next</button>
        </div>
    </div>


</div>