<!-- Container (adjust max-w to control overall width) -->
<div class="bg-white">
    <!-- Heading Bar (Optional) -->
    <div class="bg-green-600 p-3 rounded-t-xl">
        <h2 class="text-white text-lg font-semibold">Charge</h2>
    </div>

    <!-- Form -->
    <div class="space-y-4 p-5 border border-gray-200 rounded-b-md shadow-lg">
        <!-- Row 1 -->
        <div class="grid grid-cols-3 gap-3">
            <!-- Client -->
            <div>
                <label for="charge" class="block text-sm font-medium text-gray-700">Charge<span class="text-red-500">*</span></label>
                <select id="charge" name="charge"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="FCL">FCL</option>
                </select>
            </div>
            <!-- VAT / GST Amount -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description / Name<span class="text-red-500">*</span></label>
                <input type="text" id="description" name="description" placeholder=""
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Freight -->
            <div>
                <label for="freight" class="block text-sm font-medium text-gray-700">Freight<span class="text-red-500">*</span></label>
                <input type="number" id="freight" name="freight" placeholder=""
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 2 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Unit -->
            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700">Unit<span class="text-red-500">*</span></label>
                <select name="" id="" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">PER CONTAINER</option>
                    <option value=""></option>
                </select>
            </div>

            <!-- Currency -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                <input type="text" name="quantity" id="quantity" placeholder="" value="0"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- OFD Type -->
            <div>
                <label for="cofdtype" class="block text-sm font-medium text-gray-700">OFD Type</label>
                <input type="number" id="cofdtype" name="cofdtype" placeholder="1100"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 3 -->
        <div class="w-full gap-4">
            <!-- Remarks -->
            <div class="max-w-lg">
                <label for="textarea-label" class="block text-sm font-medium mb-2 dark:text-white">Remarks</label>
                <textarea id="textarea-label" class="py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 " rows="3" placeholder="Say hi..."></textarea>
            </div>
        </div>
    </div>
</div>