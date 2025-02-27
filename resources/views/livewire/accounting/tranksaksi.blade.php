<form action="POST" class="px-4 max-h-[80vh] overflow-y-auto
                [&::-webkit-scrollbar]:w-2
                [&::-webkit-scrollbar]:hidden">
    <div class="flex justify-center m-2 rounded p-3">
        <caption class="py-2 text-start text-sm text-gray-600 dark:text-neutral-500">List of users</caption>
    </div>
    <div class="space-y-4 rounded">
        <div class="flex flex-col">
        </div>
    </div>
    <div class="space-y-4">
        <!-- Charge Table -->
        <div class="bg-white">
            <!-- Heading Bar (Optional) -->
            <div class="bg-green-600 p-3 rounded-t-xl">
                <h2 class="text-white text-lg font-semibold">Charge</h2>
            </div>

            <!-- Form -->
            <div class="space-y-4 p-5 border border-gray-200 rounded-b-md shadow-lg">
                <!-- Row 1 -->
                <div class="grid grid-cols-3 gap-3">
                    <!-- Charge -->
                    <div>
                        <label for="charge" class="block text-sm font-medium text-gray-700">Charge<span class="text-red-500">*</span></label>
                        <select id="charge" name="charge"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="FCL">FCL</option>
                        </select>
                    </div>
                    <!-- Description / Name -->
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
                        <select name="unit" id="unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">PER CONTAINER</option>
                            <option value=""></option>
                        </select>
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="text" name="quantity" id="quantity" placeholder="" value="0"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- OFD Type -->
                    <div>
                        <label for="ofdtype" class="block text-sm font-medium text-gray-700">OFD Type</label>
                        <input type="number" id="ofdtype" name="ofdtype" placeholder=""
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="w-full gap-4">
                    <!-- Remarks -->
                    <div class="max-w-lg">
                        <label for="remarks" class="block text-sm font-medium mb-2 dark:text-white">Remarks</label>
                        <textarea id="remarks" class="py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600 " rows="3" placeholder="Say hi..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sell -->
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
                    <!-- Gross Profit -->
                    <div class="col-start-3">
                        <label for="sgrossprofit" class="block text-sm font-medium text-gray-700">Gross Profit</label>
                        <input type="text" id="sgrossprofit" name="sgrossprofit" value="20000" readonly
                            class="mt-1 block w-full rounded-md text-green-700 border-0">
                    </div>
                </div>
            </div>
        </div>
        <!-- Cost -->
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