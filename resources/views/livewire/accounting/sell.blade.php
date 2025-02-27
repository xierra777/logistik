<!-- Container (adjust max-w to control overall width) -->
<div class="bg-white">
    <!-- Heading Bar (Optional) -->
    <div class="bg-orange-500 p-3 rounded-t-xl">
        <h2 class="text-white text-lg font-semibold">Sale</h2>
    </div>

    <!-- Form -->
    <div class="space-y-1.5 p-4 border border-gray-200 rounded-b-md shadow-lg">
        <!-- Row 1 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Client -->
            <div>
                <label for="sclient" class="block text-sm font-medium text-gray-700">Client</label>
                <select id="sclient" name="sclient"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="PT-SALIM-INDOFOOD">PT.SALIM INDOFOOD - PT.SAMSUNG SDS</option>
                    <!-- More options... -->
                </select>
            </div>

            <!-- Currency -->
            <div>
                <label for="scurrency" class="block text-sm font-medium text-gray-700">Currency</label>
                <select name="scurrency" id="scurrency" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="USD">USD</option>
                    <option value="IDR">IDR</option>
                </select>
            </div>

            <!-- D/T or G/F -->
            <div>
                <label for="srate" class="block text-sm font-medium text-gray-700">Ex.rate</label>
                <input type="text" id="srate" name="srate"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 2 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Remarks -->
            <div class="flex space-x-4">
                <!-- First Field: Amount / Qty -->
                <div class="flex-1">
                    <label for="samount_qty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                    <input type="number" id="samount_qty" name="samount_qty" placeholder=""
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <!-- Second Field: includedtax -->
                <div class="flex-1">
                    <label for="sincludedtax" class="block text-sm font-medium text-gray-700"> Included Tax?
                    </label>
                    <select id="sincludedtax" name="sincludedtax"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">No</option>
                        <option value="">Yes</option>
                    </select>
                </div>
            </div>

            <!-- FCY Amount -->
            <div>
                <label for="sfcyamount" class="block text-sm font-medium text-gray-700">FCY Amount</label>
                <input type="text" id="sfcyamount" name="sfcyamount"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Amount (IDR) -->
            <div>
                <label for="samountidr" class="block text-sm font-medium text-gray-700">Amount (IDR)</label>
                <input type="text" id="samountidr" name="samountidr" placeholder=""
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 3 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Ex Rate -->
            <div>
                <label for="sdrcr" class="block text-sm font-medium text-gray-700">Dr / Cr</label>
                <select name="sdrcr" id="sdrcr" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="dr">Dr(+)</option>
                    <option value="cr">Cr(-)</option>
                </select>
            </div>

            <!-- VAT -->
            <div>
                <label for="svatgst" class="block text-sm font-medium text-gray-700">VAT / GST Type </label>
                <select name="svatgst" id="svatgst" class="w-full rounded-md border-gray-300 shadow-sm">
                    <option value="vat">VAT</option>
                    <option value="gst">GST</option>
                </select>
            </div>

            <!-- Balance Amount -->
            <div>
                <label for="staxableamount" class="block text-sm font-medium text-gray-700">Taxable Amount</label>
                <input type="text" id="staxableamount" name="staxableamount" value="0"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 4 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- VAT/GST AMOUNT -->
            <div>
                <label for="svatgstamount" class="block text-sm font-medium text-gray-700">VAT/GST Amount</label>
                <input type="text" id="svatgstamount" name="svatgstamount"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
            </div>
            <!-- W/H TAX RATE -->
            <div>
                <label for="swhtaxrate" class="block text-sm font-medium text-gray-700">W/H Tax Rate</label>
                <select name="swhtaxrate" id="swhtaxrate" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="5%">5%</option>
                    <option value="10%">10%</option>
                </select>
            </div>
            <!-- W/H TAX AMOUNT -->
            <div>
                <label for="swhtaxamount" class="block text-sm font-medium text-gray-700">W/H Tax Amount</label>
                <input type="text" id="swhtaxamount" name="swhtaxamount"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
            </div>
        </div>

        <!-- Row 5 -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Remarks -->
            <div class="max-w-lg">
                <label for="sremarks" class="block text-sm font-medium mb-2 dark:text-white">Remarks</label>
                <textarea id="sremarks" class="py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 " rows="3" placeholder="Say hi..."></textarea>
            </div>

            <!--             
            <div>

            </div> -->
            <!-- Gross Profit -->
            <div class="col-start-3">
                <label for="sgrossprofit" class="block text-sm font-medium text-gray-700">Gross Profit</label>
                <input type="text" id="sgrossprofit" name="sgrossprofit" value="20000" readonly
                    class="mt-1 block w-full rounded-md text-green-700 border-0">
            </div>
        </div>
    </div>
</div>