<form wire:submit.prevent="save" class="py-5 px-3 max-h-[80vh] overflow-y-auto space-y-3  [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar]:hidden"
    x-data="{
        scurrency: @entangle('scurrency'),
        srate: @entangle('srate'),
        amount: @entangle('samount_qty'),
        sincludedtax: @entangle('sincludedtax'),
        svatgst: @entangle('svatgst'),
        swhtaxrate: @entangle('swhtaxrate'),
        fcyAmount: @entangle('sfcyamount'),
        samountidr: @entangle('samountidr'),
        camountidr : @entangle('camountidr'),
        // Number formatting function
        formatNumber(value) {
            if (isNaN(value) || value === null || value === undefined) return '';
            return new Intl.NumberFormat('de-DE', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value);
        },
        ccurrency: @entangle('ccurrency'),
        crate: @entangle('crate'),
        camount: @entangle('camount_qty'),
        cincludedtax: @entangle('cincludedtax'),
        cvatgst: @entangle('cvatgst'),
        cwhtaxrate: @entangle('cwhtaxrate'),    
        ctaxamount: @entangle ('cvatgstamount'),
        get fcyAmount() {
        const isTaxIncluded = (this.sincludedtax || 'No').trim() === 'Yes';
        const rateNum = parseFloat(
                        (this.svatgst || '0')
                            .replace('%', '')
                            .replace(',', '.')
                    ) || 0;
        const baseIdr = this.amount ;
            if (isTaxIncluded) {
        return parseFloat((baseIdr / (1 + rateNum / 100)).toFixed(3));
            } else {
                return baseIdr;
            }
       },
        get samountidr() {
            // Apakah tax included?
            const isTaxIncluded = (this.sincludedtax || 'No').trim() === 'Yes';

            // Bersihkan rate VAT jadi angka murni
            const rateNum = parseFloat(
                (this.svatgst || '0')
                    .replace('%', '')
                    .replace(',', '.')
            ) || 0;

            // Base IDR tanpa pajak
            const baseIdr = this.srate * (parseFloat(this.amount) || 0);

            if (isTaxIncluded) {
                // Jika tax include, gross = base + VAT
                return baseIdr / (1 + rateNum / 100);
            } else {
                return baseIdr;
            }
        },

        get gp() {
            return this.samountidr - this.camountidr;
        },
        get whtaxAmount() {
            const rateString = (this.swhtaxrate || '0').replace('%', '').replace(',', '.');
            const rate = parseFloat(rateString) || 0;
            return this.samountidr * (rate / 100);
        },
        get vatAmount() {
                    const rateString = (this.svatgst || '0').replace('%', '').replace(',', '.');
                    const rate = parseFloat(rateString) || 0;
                    return this.samountidr * (rate / 100);
        },
        get vatAmountUsd() {
                    const rateString = (this.svatgst || '0').replace('%', '').replace(',', '.');
                    const rate = parseFloat(rateString) || 0;
                    return this.fcyAmount * (rate / 100);
        },
        get swhtaxamountusd() {
            const rateString = (this.swhtaxrate || '0').replace('%', '').replace(',', '.');
            const rate = parseFloat(rateString) || 0;
            return this.fcyAmount * (rate / 100);
        },
        get totalTax() {
            return this.vatAmount + this.whtaxAmount;
        },
        // Computed properties
        get cfcyAmount() {
           const isTaxIncluded = (this.cincludedtax || 'No').trim() === 'Yes';
        const rateNum = parseFloat(
                        (this.cvatgst || '0')
                            .replace('%', '')
                            .replace(',', '.')
                    ) || 0;
        const baseIdr = this.camount ;
            if (isTaxIncluded) {
        return parseFloat((baseIdr / (1 + rateNum / 100)).toFixed(3));
            } else {
                return baseIdr;
            }
       },
        get camountidr() {
         // Apakah tax included?
            const isTaxIncluded = (this.cincludedtax || 'No').trim() === 'Yes';

            // Bersihkan rate VAT jadi angka murni
            const rateNum = parseFloat(
                (this.cvatgst || '0')
                    .replace('%', '')
                    .replace(',', '.')
            ) || 0;

            const baseIdr = this.crate * (parseFloat(this.camount) || 0);

            if (isTaxIncluded) {
                // Jika tax include, gross = base + VAT
                return baseIdr / (1 + rateNum / 100);
            } else {
                return baseIdr;
            }
        },

        get ctaxable() {
            // Clean tax rate (handle values like '1,1%')
            const rateString = (this.cvatgst || '0').replace('%', '').replace(',', '.');
            const rate = parseFloat(rateString) || 0;
            return this.camountidr * (rate / 100);
        },
        get cwhtaxamount() {
            // Clean tax rate (handle values like '1,1%')
            const rateString = (this.cwhtaxrate || '0').replace('%', '').replace(',', '.');
            const rate = parseFloat(rateString) || 0;
            return this.camountidr * (rate / 100);
        },
        get ctaxamount() {
            return this.ctaxable + this.cwhtaxamount;
        },

        // Initialization
        init() {
            this.$watch('cfcyAmount', value => @this.set('cfcyamount', value));
            this.$watch('camount', value => @this.set('camount_qty', value));
            this.$watch('formatNumber(ctaxamount)', value => @this.set('cvatgstamount', value));
            this.$watch('formatNumber(ctaxable)', value => @this.set('ctaxableamount', value));
            this.$watch('formatNumber(camountidr)', value => @this.set('camountidr', value));
            this.$watch('formatNumber(cwhtaxamount)', value => @this.set('cwhtaxamount', value));
            this.$watch('ccurrency', value => this.fetchExchangRate(value));
            this.fetchExchangRate(this.ccurrency);
            this.$watch('formatNumber(gp)', value => @this.set('sgrossprofit', value));
            this.$watch('fcyAmount', value =>  @this.set('sfcyamount', value));    
            this.$watch('amount', value => @this.set('samount_qty', value));
            this.$watch('formatNumber(vatAmount)', value => @this.set('svatgstamount', value));
            this.$watch('formatNumber(totalTax)', value => @this.set('staxableamount', value));
            this.$watch('formatNumber(whtaxAmount)', value => @this.set('swhtaxamount', value));
            this.$watch('formatNumber(vatAmountUsd)', value => @this.set('svatgstusd', value));
            this.$watch('formatNumber(samountidr)', value => @this.set('samountidr', value));
            this.$watch('formatNumber(gp)', value => @this.set('sgrossprofit', value));
            this.$watch('scurrency', value => this.fetchExchangeRate(value));
            this.fetchExchangeRate(this.scurrency);
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                },
        fetchExchangRate(currency) {
            if (!currency || currency.trim().length < 3) {
                this.crate = 0;
                return;
            }
            let curr = currency.trim().toUpperCase();
            fetch(`https://api.exchangerate-api.com/v4/latest/${curr}`)
                .then(response => response.json())
                .then(data => {
                    this.crate = data.rates?.IDR || 0;
                })
                .catch(error => {
                    console.error('Error fetching exchange rate:', error);
                    this.crate = 0;
                });
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
                    this.srate = data.rates?.IDR || 0;
                })
                .catch(error => {
                    console.error('Error fetching exchange rate:', error);
                    this.srate = 0;
                });
        }
    }">


    <div class="bg-white">
        <!-- Heading Bar -->
        {{$shipmentId}}
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
                        @foreach($chargeCoa as $c)
                        <option value="{{$c->charge_code}}">{{$c->charge_code}}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description / Name<span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="description" name="description" wire:model="description" placeholder=""
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <!-- Freight -->
                <div>
                    <label for="freight" class="block text-sm font-medium text-gray-700">
                        Freight<span class="text-red-500">*</span>
                    </label>
                    <select name="freight" id="freight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value=""></option>
                        <option value="prepaid">Prepaid</option>
                        <option value="collect">Collect</option>
                    </select>
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
                        <option value=""></option>
                        <option value="CONTAINER">PER CONTAINER</option>
                    </select>
                </div>
                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">
                        Quantity
                    </label>
                    <input type="text" name="quantity" id="quantity" wire:model="quantity" placeholder=""
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <!-- OFD Type -->
                <div>
                    <label for="cofdtype" class="block text-sm font-medium text-gray-700">
                        OFD Type
                    </label>
                    <select name="cofdtype" id="cofdtype" wire:model="ofdtype" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value=""></option>
                        <option value="OFD">OFD</option>
                        <option value="OFC">OFC</option>
                    </select>
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
    <!-- Sell Section -->
    <div class="bg-white">
        <!-- Heading Bar -->
        <div class="bg-orange-500 p-3 rounded-t-xl">
            <h2 class="text-white text-lg font-semibold">Sale</h2>
        </div>
        <!-- Sale Form -->
        <div class="space-y-1.5 p-4 border border-gray-200 rounded-b-md shadow-xl">
            <!-- Row 1: Client, Currency, Exchange Rate -->
            <div class="grid grid-cols-3 gap-4" wire:ignore>
                <div class="mb-4">
                    <label class="block font-medium">Pilih Client</label>
                    <select wire:model="sclient" id="sclient" class="w-full border rounded-md border-gray-300 p-2">
                        <option value="">-- Pilih Client --</option>
                        @foreach($clients ?? [] as $client)
                        <option wire:key="{{$client}}" value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('sclient') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                    <input type="text" id="srate" name="srate" :value="srate" wire:model="srate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            <!-- Row 2: Amount/Qty, Included Tax, FCY Amount, Calculated Amount (IDR) -->
            <div class="grid grid-cols-3 gap-4">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="samount_qty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                        <input type="text" id="samount_qty" name="samount_qty"
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
                        :value="formatNumber(fcyAmount)" readonly wire:model="sfcyamount"
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
                    <label for="swhtaxrate" class="block text-sm font-medium text-gray-700">W/H Tax Rate</label>
                    <select id="swhtaxrate" name="swhtaxrate" wire:model="swhtaxrate" x-model="swhtaxrate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="%">Select Tax</option>
                        <option value="">0</option>
                        <option value="2%">2%</option>
                        <option value="2.5%">2,5%</option>
                        <option value="7.5%">7,5%</option>
                    </select>
                </div>


                <div class="hidden">
                    <div>
                        <label for="svatgstusd" class="block text-sm font-medium text-gray-700">VAT TAX (USD)</label>
                        <input type="text" id="svatgstusd" name="svatgstusd"
                            :value="formatNumber(vatAmountUsd)" wire:model="svatgstusd"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="shwtaxrateusd" class="block text-sm font-medium text-gray-700">WHT TAX (USD)</label>
                        <input type="text" id="shwtaxrateusd" name="shwtaxrateusd"
                            :value="formatNumber(swhtaxamountusd)" wire:model="shwtaxrateusd"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            <!-- Row 4: VAT/GST Amount, W/H Tax Rate, W/H Tax Amount -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="svatgstamount" class="block text-sm font-medium text-gray-700">VAT TAX </label>
                    <input type="text" id="svatgstamount" name="svatgstamount"
                        :value="formatNumber(vatAmount)" readonly
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="swhtaxamount" class="block text-sm font-medium text-gray-700">W/H Tax Amount</label>
                    <input type="text" id="swhtaxamount" name="swhtaxamount"
                        :value="formatNumber(whtaxAmount)" wire:model="swhtaxamount"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="staxableamount" class="block text-sm font-medium text-gray-700">Total TAX</label>
                    <input type="text" id="staxableamount" name="staxableamount"
                        :value="formatNumber(totalTax)" wire:model="staxableamount"
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
                        <input type="text"
                            id="sgrossprofit"
                            name="sgrossprofit"
                            :value="formatNumber(gp)"
                            wire:model="sgrossprofit"
                            readonly
                            :class="{'text-red-500': gp < 0, 'text-green-700': gp >= 0}"
                            class="mt-1 block w-full focus:ring-0 focus:outline-none border-0">
                    </div>
                </div>
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
                <div class="mb-4">
                    <label class="block font-medium">Pilih Vendor</label>
                    <select wire:model="cvendor" class="w-full border rounded-md border-gray-300 p-2">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors ?? [] as $vendor)
                        <option wire:key="{{$vendor}}" value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('vendor') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
                    <input type="text" id="ccurrency" name="ccurrency" wire:model="ccurrency"
                        @input="ccurrency = $event.target.value.toUpperCase()"
                        x-model="ccurrency" class="block w-full rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <div>
                        <span class="text-gray-500 text-xs">* Use the currency code provided by the vendor</span>
                    </div>
                </div>
                <!-- Exchange Rate -->
                <div>
                    <label for="crate" class="block text-sm font-medium text-gray-700">Ex.rate</label>
                    <input type="text" id="crate" name="crate" wire:model="crate" :value="crate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 3 -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Amount / Qty & Included Tax -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="camount_qty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                        <input type="text" id="camount_qty" name="camount_qty" wire:model="camount_qty" x-model.number="camount"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex-1">
                        <label for="cincludedtax" class="block text-sm font-medium text-gray-700">Included Tax?</label>
                        <select id="cincludedtax" name="cincludedtax" wire:model="cincludedtax" x-model="cincludedtax"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                </div>
                <!-- FCY Amount -->
                <div>
                    <label for="cfcyamount" class="block text-sm font-medium text-gray-700">FCY Amount</label>
                    <input type="text" id="cfcyamount" name="cfcyamount" wire:model="cfcyamount" :value="cfcyAmount"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <!-- Amount (IDR) -->
                <div>
                    <label for="camountidr" class="block text-sm font-medium text-gray-700">Amount (IDR)</label>
                    <input type="text" id="camountidr" name="camountidr" wire:model="camountidr" placeholder="" :value="formatNumber(camountidr)"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 4 -->
            <div class="grid grid-cols-3 gap-4">
                <!-- VAT / GST Type -->
                <div>
                    <label for="cvatgst" class="block text-sm font-medium text-gray-700">VAT / GST TAX</label>
                    <select id="cvatgst" name="cvatgst" wire:model="cvatgst"
                        class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">0</option>
                        <option value="1.1%">1.1%</option>
                        <option value="1.2%">1.2%</option>
                        <option value="11%">11%</option>
                        <option value="12%">12%</option>
                    </select>
                </div>
                <!-- W/H TAX RATE -->
                <div>
                    <label for="cwhtaxrate" class="block text-sm font-medium text-gray-700">W/H Tax Rate (cwhtaxrate)</label>
                    <select id="cwhtaxrate" name="cwhtaxrate" wire:model="cwhtaxrate" x-model="cwhtaxrate"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="%">Select Tax</option>
                        <option value="">0</option>
                        <option value="2%">2%</option>
                        <option value="2.5%">2,5%</option>
                        <option value="7.5%">7,5%</option>
                    </select>
                </div>
                <!-- Taxable Amount -->
                <div>
                    <label for="ctaxableamount" class="block text-sm font-medium text-gray-700">VAT TAX</label>
                    <input type="text" id="ctaxableamount" name="ctaxableamount" wire:model="ctaxableamount"
                        :value="formatNumber(ctaxable)"
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
                <!-- VAT/GST Amount -->
                <div>
                    <label for="cvatgstamount" class="block text-sm font-medium text-gray-700">TOTAL TAX</label>
                    <input type="text" id="cvatgstamount" name="cvatgstamount" wire:model="cvatgstamount" :value="formatNumber(ctaxamount)"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
                </div>

                <!-- W/H TAX AMOUNT -->
                <div>
                    <label for="cwhtaxamount" class="block text-sm font-medium text-gray-700">W/H TAX</label>
                    <input type="text" id="cwhtaxamount" name="cwhtaxamount" wire:model="cwhtaxamount" :value="formatNumber(cwhtaxamount)"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
                </div>
            </div>


        </div>
    </div>

    <!-- Modal Footer (Buttons) -->
    <div class="flex justify-end mt-4 gap-2 p-4 border-t color-gray-200">
        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg" @click="$refs.modalContent.scrollTo({ top: 0, behavior: 'smooth' })">
            Cancel
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Save
        </button>
    </div>
</form>
@script()
<script>
    $(document).ready(function() {
        $('#sclient').select2({
            placeholder: "Select roles",
            allowClear: true,
            theme: 'tailwindcss-3'
        });
        $('#sclient').on('change', function() {
            let data = $(this).val();
            // console.log(data);
            // $wire.set('roles',data,false);
            $wire.sclient = data;
        });
    });
</script>
@endscript