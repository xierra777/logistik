<!-- Container (adjust max-w to control overall width) -->
<div class="bg-white">
    <!-- Heading Bar (Optional) -->
    <div class="bg-blue-500 p-3 rounded-t-xl">
        <h2 class="text-white text-lg font-semibold">Cost</h2>
    </div>

    <!-- Form -->
    <div class="space-y-1.5 p-4 border border-gray-200 rounded-b-md shadow-lg">
        <!-- Row 1 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Client -->
            <div>
                <label for="cvendor" class="block text-sm font-medium text-gray-700">Vendor</label>
                <select id="cvendor" name="cvendor"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="PT-SALIM-INDOFOOD">PT.SALIM INDOFOOD - PT.SAMSUNG SDS</option>
                    <!-- More options... -->
                </select>
            </div>

            <!-- Reference No. -->
            <div>
                <label for="creferenceno" class="block text-sm font-medium text-gray-700">No Invoice</label>
                <input type="text" id="creferenceno" name="creferenceno"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <!-- Date -->
                <label for="cdate" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" id="cdate" name="cdate"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 2 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Debit/Credit -->
            <div>
                <label for="cdrcr" class="block text-sm font-medium text-gray-700">Dr / Cr</label>
                <select name="cdrcr" id="cdrcr" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="dr">Dr(+)</option>
                    <option value="cr">Cr(-)</option>
                </select>
            </div>
            <!-- Currency -->
            <div>
                <label for="ccurrency" class="block text-sm font-medium text-gray-700">Currency</label>
                <select name="ccurrency" id="ccurrency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="USD">USD</option>
                    <option value="IDR">IDR</option>
                </select>
            </div>
            <!-- Rate -->
            <div>
                <label for="crate" class="block text-sm font-medium text-gray-700">Ex.rate</label>
                <input type="text" id="crate" name="crate"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 3 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Amount / Qty & Included Tax -->
            <div class="flex space-x-4">
                <!-- First Field: Amount / Qty -->
                <div class="flex-1">
                    <label for="camount_qty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                    <input type="text" id="camount_qty" name="camount_qty" placeholder=""
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <!-- Second Field: includedtax -->
                <div class="flex-1">
                    <label for="cincludedtax" class="block text-sm font-medium text-gray-700"> Included Tax?
                    </label>
                    <select id="cincludedtax" name="cincludedtax"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">No</option>
                        <option value="">Yes</option>
                    </select>
                </div>
            </div>

            <!-- FCY Amount -->
            <div>
                <label for="cfcyamount" class="block text-sm font-medium text-gray-700">FCY Amount</label>
                <input type="text" id="cfcyamount" name="cfcyamount"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Amount (IDR) -->
            <div>
                <label for="camountidr" class="block text-sm font-medium text-gray-700">Amount (IDR)</label>
                <input type="text" id="camountidr" name="camountidr" placeholder=""
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 4 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- VAT -->
            <div>
                <label for="cvatgst" class="block text-sm font-medium text-gray-700">VAT / GST Type </label>
                <select name="cvatgst" id="cvatgst" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="vat">VAT</option>
                    <option value="gst">GST</option>
                </select>
            </div>

            <!-- VAT/GST AMOUNT -->
            <div>
                <label for="cvatgstamount" class="block text-sm font-medium text-gray-700">VAT/GST Amount</label>
                <input type="text" id="cvatgstamount" name="cvatgstamount"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
            </div>

            <!-- Taxavle Amount -->
            <div>
                <label for="ctaxableamount" class="block text-sm font-medium text-gray-700">Taxable Amount</label>
                <input type="text" id="ctaxableamount" name="ctaxableamount" value=""
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 5 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Remarks -->
            <div class="max-w-lg">
                <label for="cremarks" class="block text-sm font-medium mb-2 dark:text-white">Remarks</label>
                <textarea id="cremarks" class="py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 " rows="3" placeholder="Say hi..."></textarea>
            </div>

            <!-- W/H TAX RATE -->
            <div>
                <label for="cwhtaxrate" class="block text-sm font-medium text-gray-700">W/H Tax Rate</label>
                <select name="cwhtaxrate" id="cwhtaxrate" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="5%">5%</option>
                    <option value="10%">10%</option>
                </select>
            </div>
            <!-- W/H TAX AMOUNT -->
            <div>
                <label for="cwhtaxamount" class="block text-sm font-medium text-gray-700">W/H Tax Amount</label>
                <input type="text" id="cwhtaxamount" name="cwhtaxamount"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
            </div>
        </div>
    </div>
</div>