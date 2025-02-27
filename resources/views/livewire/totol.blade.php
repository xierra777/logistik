<form wire:submit.prevent="save" class="px-4 max-h-[80vh] overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar]:hidden">
    <!-- Charge Section -->
    <div class="bg-white">
        <!-- Heading Bar -->
        <div class="bg-green-600 p-3 rounded-t-xl">
            <h2 class="text-white text-lg font-semibold">Charge</h2>
        </div>
        <!-- Form -->
        <div class="space-y-4 p-5 border border-gray-200 rounded-b-md shadow-lg">
            <!-- Row 1 -->
            <div class="grid grid-cols-3 gap-3">
                <!-- Charge -->
                <div>
                    <label for="charge" class="block text-sm font-medium text-gray-700">
                        Charge<span class="text-red-500">*</span>
                    </label>
                    <select id="charge" name="charge" wire:model="charge"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="FCL">FCL</option>
                    </select>
                </div>
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description / Name<span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="description" name="description" wire:model="description" placeholder=""
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <!-- Freight -->
                <div>
                    <label for="freight" class="block text-sm font-medium text-gray-700">
                        Freight<span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="freight" name="freight" wire:model="freight" placeholder=""
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Unit -->
                <div>
                    <label for="unit" class="block text-sm font-medium text-gray-700">
                        Unit<span class="text-red-500">*</span>
                    </label>
                    <select id="unit" name="unit" wire:model="unit"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">PER CONTAINER</option>
                        <option value=""></option>
                    </select>
                </div>
                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">
                        Quantity
                    </label>
                    <input type="number" name="quantity" id="quantity" wire:model="quantity" placeholder="" value="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <!-- OFD Type -->
                <div>
                    <label for="cofdtype" class="block text-sm font-medium text-gray-700">
                        OFD Type
                    </label>
                    <input type="text" id="cofdtype" name="cofdtype" wire:model="ofdtype" placeholder=""
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 3: Remarks -->
            <div class="w-full gap-4">
                <div class="max-w-lg">
                    <label for="remarks" class="block text-sm font-medium mb-2 dark:text-white">Remarks</label>
                    <textarea id="remarks" name="remarks" wire:model="remarks"
                        class="py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                        rows="3" placeholder="Say hi..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white" x-data="{
    srate: @entangle('srate'),
    sfcyamount: @entangle('sfcyamount'),
    taxable: @entangle('staxableamount'),
    taxRate: 0, // selected VAT/GST type multiplier
    get samountidr() {
        return this.srate * this.sfcyamount;
    },

    
    get taxAmount() {
        return this.taxable * this.taxRate;
    }
}">
        <!-- Heading Bar -->
        <div class="bg-orange-500 p-3 rounded-t-xl">
            <h2 class="text-white text-lg font-semibold">Sale</h2>
        </div>
        <!-- Form -->
        <div class="space-y-1.5 p-4 border border-gray-200 rounded-b-md shadow-lg">
            <!-- Row 1: Client, Currency, Exchange Rate -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="sclient" class="block text-sm font-medium text-gray-700">Client</label>
                    <select id="sclient" name="sclient" wire:model="sclient"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="PT-SALIM-INDOFOOD">PT.SALIM INDOFOOD - PT.SAMSUNG SDS</option>
                    </select>
                </div>
                <div>
                    <label for="scurrency" class="block text-sm font-medium text-gray-700">Currency</label>
                    <select id="scurrency" name="scurrency" wire:model="scurrency"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="USD">USD</option>
                        <option value="IDR">IDR</option>
                    </select>
                </div>
                <div>
                    <label for="srate" class="block text-sm font-medium text-gray-700">Ex.rate</label>
                    <input type="number" id="srate" name="srate" x-model.number="srate" wire:model="srate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 2: Amount/Qty, Included Tax, FCY Amount, Calculated Amount (IDR) -->
            <div class="grid grid-cols-3 gap-4">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="samount_qty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                        <input type="number" id="samount_qty" name="samount_qty" wire:model="samount_qty"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex-1">
                        <label for="sincludedtax" class="block text-sm font-medium text-gray-700">Included Tax?</label>
                        <select id="sincludedtax" name="sincludedtax" wire:model="sincludedtax"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">No</option>
                            <option value="">Yes</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="sfcyamount" class="block text-sm font-medium text-gray-700">FCY Amount</label>
                    <input type="number" id="sfcyamount" name="sfcyamount" x-model.number="sfcyamount" wire:model="sfcyamount"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="samountidr" class="block text-sm font-medium text-gray-700">Amount (IDR)</label>
                    <input type="number" id="samountidr" name="samountidr" :value="samountidr" wire:model="samountidr" readonly
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 3: Dr/Cr, VAT/GST Type, Taxable Amount -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="sdrcr" class="block text-sm font-medium text-gray-700">Dr / Cr</label>
                    <select id="sdrcr" name="sdrcr" wire:model="sdrcr"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="dr">Dr(+)</option>
                        <option value="cr">Cr(-)</option>
                    </select>
                </div>
                <div>
                    <label for="taxType" class="block text-sm font-medium text-gray-700">VAT / GST Type</label>
                    <!-- When a tax type is selected, its numeric value is stored in taxRate -->
                    <select id="taxType" name="taxType" x-model.number="taxRate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="0">Select Tax</option>
                        <option value="1.1">1.1</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="1">1</option>
                    </select>
                </div>
                <div>
                    <label for="staxableamount" class="block text-sm font-medium text-gray-700">Taxable Amount</label>
                    <input type="number" id="staxableamount" name="staxableamount" x-model.number="taxAmount" wire:model="staxableamount" value="0"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 4: VAT/GST Amount, W/H Tax Rate, W/H Tax Amount -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="svatgstamount" class="block text-sm font-medium text-gray-700">VAT/GST Amount</label>
                    <!-- This computed field multiplies taxable * taxRate directly -->
                    <input type="number" id="svatgstamount" name="svatgstamount" :value="taxable" wire:model="svatgstamount"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="swhtaxrate" class="block text-sm font-medium text-gray-700">W/H Tax Rate</label>
                    <select id="swhtaxrate" name="swhtaxrate" wire:model="swhtaxrate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="5%">5%</option>
                        <option value="10%">10%</option>
                    </select>
                </div>
                <div>
                    <label for="swhtaxamount" class="block text-sm font-medium text-gray-700">W/H Tax Amount</label>
                    <input type="number" id="swhtaxamount" name="swhtaxamount" wire:model="swhtaxamount"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
                </div>
            </div>

            <!-- Row 5: Remarks and Gross Profit -->
            <div class="grid grid-cols-3 gap-4">
                <div class="max-w-lg">
                    <label for="sremarks" class="block text-sm font-medium mb-2 dark:text-white">Remarks</label>
                    <textarea id="sremarks" name="sremarks" wire:model="sremarks"
                        class="py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                        rows="3" placeholder="Say hi..."></textarea>
                </div>
                <div class="col-start-3">
                    <label for="sgrossprofit" class="block text-sm font-medium text-gray-700">Gross Profit</label>
                    <input type="number" id="sgrossprofit" name="sgrossprofit" wire:model="sgrossprofit" readonly
                        class="mt-1 block w-full rounded-md text-green-700 border-0">
                </div>
            </div>
        </div>

        <!-- Cost Section -->
        <div class="bg-white">
            <!-- Heading Bar -->
            <div class="bg-blue-500 p-3 rounded-t-xl">
                <h2 class="text-white text-lg font-semibold">Cost</h2>
            </div>
            <!-- Form -->
            <div class="space-y-1.5 p-4 border border-gray-200 rounded-b-md shadow-lg">
                <!-- Row 1 -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Vendor -->
                    <div>
                        <label for="cvendor" class="block text-sm font-medium text-gray-700">Vendor</label>
                        <select id="cvendor" name="cvendor" wire:model="cvendor"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="PT-SALIM-INDOFOOD">PT.SALIM INDOFOOD - PT.SAMSUNG SDS</option>
                        </select>
                    </div>
                    <!-- No Invoice -->
                    <div>
                        <label for="creferenceno" class="block text-sm font-medium text-gray-700">No Invoice</label>
                        <input type="text" id="creferenceno" name="creferenceno" wire:model="creferenceno"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <!-- Date -->
                    <div>
                        <label for="cdate" class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" id="cdate" name="cdate" wire:model="cdate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Dr / Cr -->
                    <div>
                        <label for="cdrcr" class="block text-sm font-medium text-gray-700">Dr / Cr</label>
                        <select id="cdrcr" name="cdrcr" wire:model="cdrcr"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="dr">Dr(+)</option>
                            <option value="cr">Cr(-)</option>
                        </select>
                    </div>
                    <!-- Currency -->
                    <div>
                        <label for="ccurrency" class="block text-sm font-medium text-gray-700">Currency</label>
                        <select id="ccurrency" name="ccurrency" wire:model="ccurrency"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="USD">USD</option>
                            <option value="IDR">IDR</option>
                        </select>
                    </div>
                    <!-- Exchange Rate -->
                    <div>
                        <label for="crate" class="block text-sm font-medium text-gray-700">Ex.rate</label>
                        <input type="number" id="crate" name="crate" wire:model="crate"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Amount / Qty & Included Tax -->
                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <label for="camount_qty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                            <input type="number" id="camount_qty" name="camount_qty" wire:model="camount_qty" placeholder=""
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="flex-1">
                            <label for="cincludedtax" class="block text-sm font-medium text-gray-700">Included Tax?</label>
                            <select id="cincludedtax" name="cincludedtax" wire:model="cincludedtax"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">No</option>
                                <option value="">Yes</option>
                            </select>
                        </div>
                    </div>
                    <!-- FCY Amount -->
                    <div>
                        <label for="cfcyamount" class="block text-sm font-medium text-gray-700">FCY Amount</label>
                        <input type="number" id="cfcyamount" name="cfcyamount" wire:model="cfcyamount"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <!-- Amount (IDR) -->
                    <div>
                        <label for="camountidr" class="block text-sm font-medium text-gray-700">Amount (IDR)</label>
                        <input type="number" id="camountidr" name="camountidr" wire:model="camountidr" placeholder=""
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- VAT / GST Type -->
                    <div>
                        <label for="cvatgst" class="block text-sm font-medium text-gray-700">VAT / GST Type</label>
                        <select id="cvatgst" name="cvatgst" wire:model="cvatgst"
                            class="w-full rounded-md border-gray-300 shadow-sm">
                            <option value="vat">VAT</option>
                            <option value="gst">GST</option>
                        </select>
                    </div>
                    <!-- VAT/GST Amount -->
                    <div>
                        <label for="cvatgstamount" class="block text-sm font-medium text-gray-700">VAT/GST Amount</label>
                        <input type="number" id="cvatgstamount" name="cvatgstamount" wire:model="cvatgstamount"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
                    </div>
                    <!-- Taxable Amount -->
                    <div>
                        <label for="ctaxableamount" class="block text-sm font-medium text-gray-700">Taxable Amount</label>
                        <input type="number" id="ctaxableamount" name="ctaxableamount" wire:model="ctaxableamount" value=""
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Row 5 -->
                <div class="grid grid-cols-3 gap-4">
                    <!-- Remarks -->
                    <div class="max-w-lg">
                        <label for="cremarks" class="block text-sm font-medium mb-2 dark:text-white">Remarks</label>
                        <textarea id="cremarks" name="cremarks" wire:model="cremarks"
                            class="py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                            rows="3" placeholder="Say hi..."></textarea>
                    </div>
                    <!-- W/H TAX RATE -->
                    <div>
                        <label for="cwhtaxrate" class="block text-sm font-medium text-gray-700">W/H Tax Rate</label>
                        <select id="cwhtaxrate" name="cwhtaxrate" wire:model="cwhtaxrate"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="5%">5%</option>
                            <option value="10%">10%</option>
                        </select>
                    </div>
                    <!-- W/H TAX AMOUNT -->
                    <div>
                        <label for="cwhtaxamount" class="block text-sm font-medium text-gray-700">W/H Tax Amount</label>
                        <input type="number" id="cwhtaxamount" name="cwhtaxamount" wire:model="cwhtaxamount"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer (Buttons) -->
        <div class="flex justify-end mt-4 gap-2 p-4 border-t color-gray-200">
            <button type="button" @click="open = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                Save
            </button>
        </div>
</form>