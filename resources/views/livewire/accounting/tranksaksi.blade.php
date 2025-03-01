<form wire:submit.prevent="save" class="px-4 max-h-[80vh] overflow-y-auto [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar]:hidden">
    <!-- Sale Section with Alpine.js for auto calculation and API fetch -->
    <div class="bg-white" x-data="{
        // Entangled Livewire properties and local state
        scurrency: @entangle('scurrency'),
        srate: @entangle('srate'),
        amount: @entangle('samount_qty'),
        sincludedtax: @entangle('sincludedtax'),
        svatgst: @entangle('svatgst'),
        swhtaxrate: @entangle('swhtaxrate'),
        // Number formatting function
        formatNumber(value) {
            if (isNaN(value) || value === null || value === undefined) return '';
            return new Intl.NumberFormat('de-DE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value);
        },

        // Computed properties
        get fcyAmount() {
            return this.amount;
        },
        get samountidr() {
            return parseFloat(this.srate || 0) * parseFloat(this.amount || 0);
        },
        get gp() {
            const isTaxIncluded = (this.sincludedtax || 'No').toString().trim() === 'Yes';
            return isTaxIncluded ? this.samountidr - this.taxAmount : this.samountidr;
        },
        get taxable() {
            // Clean tax rate (handle values like '1,1%')
            const rateString = (this.svatgst || '0').replace('%', '').replace(',', '.');
            const rate = parseFloat(rateString) || 0;
            return this.samountidr * (rate / 100);
        },
        get swhtaxamount() {
            // Clean tax rate (handle values like '1,1%')
            const rateString = (this.swhtaxrate || '0').replace('%', '').replace(',', '.');
            const rate = parseFloat(rateString) || 0;
            return this.samountidr * (rate / 100);
        },
        get taxAmount() {
            return this.taxable + this.swhtaxamount;
        },

        // Initialization
        init() {
            this.$watch('fcyAmount', value => @this.set('sfcyamount', value));
            this.$watch('amount', value => @this.set('samount_qty', value));
            this.$watch('formatNumber(taxAmount)', value => @this.set('svatgstamount', value));
            this.$watch('formatNumber(taxable)', value => @this.set('staxableamount', value));
            this.$watch('formatNumber(samountidr)', value => @this.set('samountidr', value));
            this.$watch('scurrency', value => this.fetchExchangeRate(value));
            this.fetchExchangeRate(this.scurrency);
        },
        fetchExchangeRate(currency) {
            if (!currency || currency.trim().length < 3) {
                this.srate = 0;
                return;
            }
            let curr = currency.trim().toUpperCase();
            fetch(`https://api.exchangerate-api.com/v4/latest/${curr}`)
                .then(response => response.json())
                .then(data => {
                    this.formatNumber(srate) = data.rates?.IDR || 0;
                })
                .catch(error => {
                    console.error('Error fetching exchange rate:', error);
                    this.srate = 0;
                });
        }
    }">
        <!-- Heading Bar -->
        <div class="bg-orange-500 p-3 rounded-t-xl">
            <h2 class="text-white text-lg font-semibold">Sale</h2>
        </div>

        <!-- Sale Form -->
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
                    <input type="text" name="scurrency" id="scurrency" 
                           wire:model="scurrency" x-model="scurrency"
                           @input="scurrency = $event.target.value.toUpperCase()"
                           autocomplete="off"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                  </div>
                  
                <div>
                    <label for="srate" class="block text-sm font-medium text-gray-700">Ex.rate</label>
                    <input type="text" id="srate" name="srate" x-model="formatNumber(srate)" wire:model="srate"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 2: Amount/Qty, Included Tax, FCY Amount, Calculated Amount (IDR) -->
            <div class="grid grid-cols-3 gap-4">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="samount_qty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                        <input type="number" id="samount_qty" name="samount_qty" 
                               x-model.number="amount" wire:model="samount_qty"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex-1">
                        <label for="sincludedtax" class="block text-sm font-medium text-gray-700">Included Tax?</label>
                        <select id="sincludedtax" name="sincludedtax" 
                                wire:model="sincludedtax" x-model="sincludedtax"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="sfcyamount" class="block text-sm font-medium text-gray-700">FCY Amount</label>
                    <input type="text" id="sfcyamount" name="sfcyamount" 
                           :value="fcyAmount" readonly wire:model="sfcyamount"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="samountidr" class="block text-sm font-medium text-gray-700">Amount (IDR)</label>
                    <input type="text" id="samountidr" name="samountidr" 
                           :value="formatNumber(samountidr)" wire:model="samountidr" readonly
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
                    <label for="svatgst" class="block text-sm font-medium text-gray-700">VAT / GST Type</label>
                    <select id="svatgst" name="svatgst" x-model="svatgst" wire:model="svatgst"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Tax</option>
                        <option value="1,1%">1.1%</option>
                        <option value="11%">11%</option>
                        <option value="12%">12%</option>
                        <option value="1%">1%</option>
                    </select>
                </div>
                <div>
                    <label for="staxableamount" class="block text-sm font-medium text-gray-700">Taxable Amount</label>
                    <input type="text" id="staxableamount" name="staxableamount" 
                           :value="formatNumber(taxable)" wire:model="staxableamount"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 4: VAT/GST Amount, W/H Tax Rate, W/H Tax Amount -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="svatgstamount" class="block text-sm font-medium text-gray-700">VAT/GST Amount</label>
                    <input type="text" id="svatgstamount" name="svatgstamount" 
                           :value="formatNumber(taxAmount)" readonly
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="swhtaxrate" class="block text-sm font-medium text-gray-700">W/H Tax Rate</label>
                    <select id="swhtaxrate" name="swhtaxrate" wire:model="swhtaxrate" x-model="swhtaxrate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="%">Select Tax</option>
                        <option value="2%">2%</option>
                        <option value="2.5%">2,5%</option>
                        <option value="7.5%">7,5%</option>
                    </select>
                </div>
                <div>
                    <label for="swhtaxamount" class="block text-sm font-medium text-gray-700">W/H Tax Amount</label>
                    <input type="text" id="swhtaxamount" name="swhtaxamount" 
                           :value="formatNumber(swhtaxamount)" wire:model="swhtaxamount"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 5: Remarks and Gross Profit -->
            <div class="grid grid-cols-3 gap-4">
                <div class="max-w-lg">
                    <label for="sremarks" class="block text-sm font-medium mb-2 dark:text-white">Remarks</label>
                    <textarea id="sremarks" name="sremarks" wire:model="sremarks"
                        class="py-3 px-4 block w-full border-gray-300 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500"
                        rows="3" placeholder="Say hi.."></textarea>
                </div>
                <div class="col-start-3">
                    <label for="sgrossprofit" class="block text-sm font-medium text-gray-700">Gross Profit</label>
                    <div class="flex items-center space-x-2  rounded-t-md border-b focus:outline-none focus:ring-0 border-gray-300">
                        <span class="text-gray-700">Rp.</span>
                        <input type="text" id="sgrossprofit" name="sgrossprofit" 
                               :value="formatNumber(gp)" wire:model="sgrossprofit" readonly
                               class="mt-1 block w-full  focus:ring-0 focus:outline-none  text-green-700 border-0" >
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
    </div>
</form>