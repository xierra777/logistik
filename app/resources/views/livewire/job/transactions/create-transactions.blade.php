<form wire:submit.prevent="save" class="py-5 px-3 max-h-[80vh] overflow-y-auto space-y-3  [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar]:hidden"
    x-data="{
    // --- Source Section (Sales/Income) ---
    scurrency: @entangle('scurrency'),
    srate: @entangle('srate'),
    amount: @entangle('samount_qty').defer || 0,
    sincludedtax: @entangle('sincludedtax'),
    svatgst: @entangle('svatgst'),
    swhtaxrate: @entangle('swhtaxrate'),
    fcyAmount: @entangle('sfcyamount'),
    samountidr: @entangle('samountidr'),
    quantity: @entangle('quantity'),

    // --- Cost Section (Purchasing/Expense) ---
    ccurrency: @entangle('ccurrency'),
    crate: @entangle('crate'),
    camount: @entangle('camount_qty'),
    cincludedtax: @entangle('cincludedtax'),
    cvatgst: @entangle('cvatgst'),
    cwhtaxrate: @entangle('cwhtaxrate'),
    camountidr: @entangle('camountidr'),
    ctaxamount: @entangle('cvatgstamount'),
    taxData: @js($taxData),
    
    // Getter untuk mendapatkan rate berdasarkan ID
    get currentSwhtRate() {
        return this.taxData[this.swhtaxrate] || 0;
    },
    get currentCwhtRate() {
        return this.taxData[this.cwhtaxrate] || 0;
    },
    get currentSvatRate() {
        return this.taxData[this.svatgst] || 0;
    },
    get currentCvatRate() {
        return this.taxData[this.cvatgst] || 0;
    },    // --- Internal State (Non-binding) ---
    gp: 0,
    cfcyAmount: 0,
    camount: 0,
    samountIdrRaw: 0,
    camountIdrRaw: 0,

    // --- UI Display Formatting ---
    get samountIdrDisplay() {
        return this.formatNumber(this.samountIdrRaw);
    },
    get camountIdrDisplay() {
        return this.formatNumber(this.camountIdrRaw);
    },
    formatNumber(value) {
        if (isNaN(value) || value === null || value === undefined) return '';
        return new Intl.NumberFormat('de-DE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(value);
    },

    // --- Source Calculations ---
    get fcyAmount() {
        const included = (this.sincludedtax || 'No').trim() === 'Yes';
        const vatRate = parseFloat(this.currentSvatRate || 0) || 0;
        
        if (included) {
            // Amount sudah include VAT, perlu extract base amount
            return parseFloat(((parseFloat(this.amount) || 0) / (1 + vatRate)).toFixed(3));
        } else {
            // Amount belum include VAT
            return parseFloat(this.amount) || 0;
        }
    },

    get samountIdrComputed() {
        const included = (this.sincludedtax || 'No').trim() === 'Yes';
        const vatRate = parseFloat(this.currentSvatRate || 0) || 0;
        const whtRate = parseFloat(this.currentSwhtRate || 0) || 0;
        
        const round = (num, decimals = 2) =>
            Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);
        
        const fcyAmount = parseFloat(this.fcyAmount) || 0;
        const rate = parseFloat(this.srate) || 0;
        
        let baseAmount;
        
        if (included) {
            // fcyAmount udah base amount (net of VAT)
            baseAmount = rate * fcyAmount;
        } else {
            baseAmount = rate * fcyAmount;
        }
        
        const result = round(baseAmount);
        this.samountIdrRaw = result;
        this.$nextTick(() => @this.set('samountidr', result));
        return result;
    },

    // Fix property names dan logic
    get whtaxAmount() { 
        return this.samountIdrRaw * (this.currentSwhtRate || 0) || 0;
    },

    get vatAmount() {
        return this.samountIdrRaw * (this.currentSvatRate || 0) || 0;
    },
    get vatAmountUsd() {
        return this.vatAmount / (parseFloat(this.srate) || 0);
    },
    get swhtaxamountusd() {
        return this.whtaxAmount / (parseFloat(this.srate) || 0);
    },
    get totalTax() {
        return (this.vatAmount || 0) + (this.whtaxAmount || 0);
    },


    // --- Cost Calculations ---
    get camountIdrComputed() {
        const included = (this.cincludedtax || 'No').trim() === 'Yes';
        // Karena option value sudah dalam format decimal (0.11 = 11%)
        const vatRate = parseFloat(this.cvatgst || 0) || 0;
        const whtRate = parseFloat(this.cwhtaxrate || 0) || 0;

        const round = (num, decimals = 2) =>
            Math.round(num * Math.pow(10, decimals)) / Math.pow(10, decimals);

        let result;

        if (included) {
            // Jika tax included:
            // camountidr = base amount (sebelum pajak)
            // camount = total amount (termasuk pajak)
            const totalTaxRate = vatRate;
            const baseAmount = (this.crate * parseFloat(this.cfcyAmount || 0)) / (1 + totalTaxRate);
            result = round(baseAmount);
        } else {
            const baseAmount = this.crate * (parseFloat(this.cfcyAmount) || 0);
            result = round(baseAmount);
        }

        this.camountIdrRaw = result;
        this.$nextTick(() => @this.set('camountidr', result));
        return result;
    },
    get cfcyAmount() {
        const included = (this.sincludedtax || 'No').trim() === 'Yes';
    // Karena option value sudah dalam format decimal (0.11 = 11%)
    const cvatRate = parseFloat(this.cvatgst || 0) || 0;
    const cwhtRate = parseFloat(this.cwhtaxrate || 0) || 0;

    if (included) {
        // fcyAmount = base FCY (sebelum pajak)
        const totalTaxRate = cvatRate;
        return parseFloat(((parseFloat(this.camount) || 0) / (1 + totalTaxRate)).toFixed(3));
    } else {
        // fcyAmount = amount asli
        return this.quantity * parseFloat(this.camount) || 0;
    }
    },
    get ctaxable() {
       return this.camountIdrRaw * (this.currentCvatRate || 0) || 0;
    },
    get cwhtaxamount() {
    
        return this.camountIdrRaw * (this.currentCwhtRate || 0) || 0;

    },
    get ctaxamount() {
        return this.ctaxable + this.cwhtaxamount;
    },

    // --- Gross Profit ---
    get gp() {
    const base = this.samountIdrRaw - this.camountIdrRaw;

        return this.formatNumber(base);    
    },
    resetModal() {
        this.amount = 0;
        this.quantity = 1;
        this.svatgst = 0;
        this.swhtaxrate = 0;
        this.samountIdrRaw = 0;
        this.camountIdrRaw = 0;
        this.camount = 0;
        this.cvatgst = 0;
        this.cwhtaxrate = 0;
        this.$nextTick(() => {
            @this.set('samount_qty', 0);
            @this.set('sfcyamount', 0);
            @this.set('samountidr', 0);
            @this.set('sgrossprofit', 0);
            @this.set('svatgstamount', 0);
            @this.set('swhtaxamount', 0);
            @this.set('staxableamount', 0);
            @this.set('svatgstusd', 0);
            @this.set('camount_qty', 0);
            @this.set('cfcyamount', 0);
            @this.set('camountidr', 0);
            @this.set('cvatgstamount', 0);
            @this.set('ctaxableamount', 0);
            @this.set('cwhtaxamount', 0);
            this.samountIdrComputed;
            this.camountIdrComputed;
        });
    },
    // --- Initialization ---
    init() {
        window.reinitSelect2();
    this.resetModal();
        // Watch for recalculation
        this.$watch('srate', () => this.samountIdrComputed);
        this.$watch('amount', () => this.samountIdrComputed);
        this.$watch('sincludedtax', () => this.samountIdrComputed);
        this.$watch('svatgst', () => this.samountIdrComputed);

        this.$watch('crate', () => this.camountIdrComputed);
        this.$watch('camount', () => this.camountIdrComputed);
        this.$watch('cincludedtax', () => this.camountIdrComputed);
        this.$watch('cvatgst', () => this.camountIdrComputed);

        // Sync back to Livewire
        this.$watch('cfcyAmount', value => @this.set('cfcyamount', value));
        this.$watch('camount', value => @this.set('camount_qty', value));
        this.$watch('ctaxamount', value => @this.set('cvatgstamount', value));
        this.$watch('ctaxable', value => @this.set('ctaxableamount', value));
        this.$watch('cwhtaxamount', value => @this.set('cwhtaxamount', value));

        this.$watch('gp', value => @this.set('sgrossprofit', value));
        this.$watch('amount', value => @this.set('samount_qty', value));
        this.$watch('fcyAmount', value => @this.set('sfcyamount', value));
        this.$watch('vatAmount', value => @this.set('svatgstamount', value));
        this.$watch('totalTax', value => @this.set('staxableamount', value));
        this.$watch('whtaxAmount', value => @this.set('swhtaxamount', value));
        this.$watch('vatAmountUsd', value => @this.set('svatgstusd', value));

        // Fetch currency rates
        this.$watch('scurrency', value => this.fetchExchangeRate(value));
        this.fetchExchangeRate(this.scurrency);

        this.$watch('ccurrency', value => this.fetchExchangRate(value));
        this.fetchExchangRate(this.ccurrency);

        this.$nextTick(() => {
            this.samountIdrComputed;
            this.camountIdrComputed;
        });
    },

    // --- Currency API Fetching ---
    fetchExchangeRate(currency) {
        if (!currency || currency.trim().length < 3) {
            this.srate = 0;
            return;
        }
        fetch(`https://api.exchangerate-api.com/v4/latest/${currency.trim().toUpperCase()}`)
            .then(res => res.json())
            .then(data => this.srate = data.rates?.IDR || 0)
            .catch(err => {
                console.error('Error fetching source rate:', err);
                this.srate = 0;
            });
    },
    fetchExchangRate(currency) {
        if (!currency || currency.trim().length < 3) {
            this.crate = 0;
            return;
        }
        fetch(`https://api.exchangerate-api.com/v4/latest/${currency.trim().toUpperCase()}`)
            .then(res => res.json())
            .then(data => this.crate = data.rates?.IDR || 0)
            .catch(err => {
                console.error('Error fetching cost rate:', err);
                this.crate = 0;
            });
    }
}"
    x-init="init(); $nextTick(() => window.reinitSelect2())"
    x-cloak>


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
                    <div wire:ignore>
                        <select id="charge" name="charge" wire:model="charge"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" require>
                            <option value=""></option>
                            @foreach($chargeCoa as $c)
                            <option value="{{$c->charge_code}}">{{$c->charge_code}} - {{$c->charge_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('charge') <span class="text-red-500">{{$message}}</span> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description / Name<span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" id="description" name="description" wire:model="description" placeholder=""
                            class="block py-1.5 pr-8 pl-3 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><span wire:loading></span>
                    </div>
                </div>
                <!-- Freight -->
                <div wire:ignore>
                    <label for="freight" class="block text-sm font-medium text-gray-700">
                        Freight<span class="text-red-500">*</span>
                    </label>
                    <select name="freight" id="freight" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value=""></option>
                        <option value="prepaid">Prepaid</option>
                        <option value="collect">Collect</option>
                    </select>
                </div>
            </div>
            <!-- Row 2 -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Unit -->
                <div wire:ignore>
                    <label for="unit" class="block text-sm font-medium text-gray-700">
                        Unit<span class="text-red-500">*</span>
                    </label>
                    <select id="unit" name="unit" wire:model="unit"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value=""></option>
                        <option value="">-- Pilih --</option>
                        <option value="CONTAINER">PER CONTAINER</option>
                        <option value="PALLET">PER PALLET</option>
                        <option value="DOCUMENT">PER DOCUMENT</option>
                    </select>
                </div>
                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">
                        Quantity
                    </label>
                    <input type="text" name="quantity" id="quantity" wire:model="quantity" placeholder="" x-model="quantity"
                        class="block py-1.5 pr-8 pl-3 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>
                <!-- OFD Type -->
                <div wire:ignore>
                    <label for="cofdtype" class="block text-sm font-medium text-gray-700">
                        OFD Type
                    </label>
                    <select name="cofdtype" id="cofdtype" wire:model="ofdtype" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                <div class="">
                    <label class="block font-medium">Pilih Client</label>
                    <select wire:model="sclient" id="sclient" class="w-full border rounded-md border-gray-300 p-2">
                        <option value="">-- Pilih Client --</option>
                        @foreach($clients ?? [] as $client)
                        <option value="{{ $client->id }}">{{$client->customer_code}} - {{ $client->name }}</option>
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
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="srate" class="block text-sm font-medium text-gray-700">Ex.rate</label>
                    <input type="text" id="srate" name="srate" :value="srate" wire:model="srate"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            <!-- Row 2: Amount/Qty, Included Tax, FCY Amount, Calculated Amount (IDR) -->
            <div class="grid grid-cols-3 gap-4">
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="samount_qty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                        <input type="text" id="samount_qty" name="samount_qty"
                            x-model.number="amount" wire:model="samount_qty"
                            class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex-1" wire:ignore>
                        <label for="sincludedtax" class="block text-sm font-medium text-gray-700">Included Tax?</label>
                        <select id="sincludedtax" name="sincludedtax"
                            wire:model="sincludedtax" x-model="sincludedtax"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="sfcyamount" class="block text-sm font-medium text-gray-700">FCY Amount</label>
                    <input type="text" id="sfcyamount" name="sfcyamount"
                        :value="formatNumber(fcyAmount)" readonly wire:model="sfcyamount"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="samountidr" class="block text-sm font-medium text-gray-700">Amount (IDR)</label>
                    <input type="text" id="samountidr" name="samountidr"
                        :value="formatNumber(samountidr)" wire:model="samountidr" readonly
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
            <!-- Row 3: Dr/Cr, VAT/GST Type, Taxable Amount -->
            <div class="grid grid-cols-3 gap-4" wire:ignore>
                <div>
                    <label for="sdrcr" class="block text-sm font-medium text-gray-700">Dr / Cr</label>
                    <select id="sdrcr" name="sdrcr" wire:model="sdrcr"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm">
                        <option value="dr">Dr(+)</option>
                        <option value="cr">Cr(-)</option>
                    </select>
                </div>
                <div wire:ignore>
                    <label for="svatgst" class="block text-sm font-medium text-gray-700">VAT / GST Type</label>
                    <select id="svatgst" name="svatgst" x-model="svatgst" wire:model="svatgst"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Tax</option>
                        @foreach($taxRates as $id => $rate)
                        <option value="{{$id}}">
                            {{$rate * 100}}%
                        </option>
                        @endforeach
                    </select>
                </div>
                <div wire:ignore>
                    <label for="swhtaxrate" class="block text-sm font-medium text-gray-700">W/H Tax Rate</label>
                    <select id="swhtaxrate" name="swhtaxrate" wire:model="swhtaxrate" x-model="swhtaxrate"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Tax</option>
                        @foreach($taxRatesWht as $id => $rate)
                        <option value="{{$id}}">
                            {{$rate * 100}}%
                        </option>
                        @endforeach
                    </select>
                </div>


                <div class="hidden">
                    <div>
                        <label for="svatgstusd" class="block text-sm font-medium text-gray-700">VAT TAX (USD)</label>
                        <input type="text" id="svatgstusd" name="svatgstusd"
                            :value="formatNumber(vatAmountUsd)"
                            class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="shwtaxrateusd" class="block text-sm font-medium text-gray-700">WHT TAX (USD)</label>
                        <input type="text" id="shwtaxrateusd" name="shwtaxrateusd"
                            :value="formatNumber(swhtaxamountusd)"
                            class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            <!-- Row 4: VAT/GST Amount, W/H Tax Rate, W/H Tax Amount -->
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label for="svatgstamount" class="block text-sm font-medium text-gray-700">VAT TAX </label>
                    <input type="text" id="svatgstamount" name="svatgstamount"
                        :value="formatNumber(vatAmount)" readonly
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="swhtaxamount" class="block text-sm font-medium text-gray-700">W/H Tax Amount</label>
                    <input type="text" id="swhtaxamount" name="swhtaxamount"
                        :value="formatNumber(whtaxAmount)"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label for="staxableamount" class="block text-sm font-medium text-gray-700">Total TAX</label>
                    <input type="text" id="staxableamount" name="staxableamount"
                        :value="formatNumber(totalTax)"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                    <div class="flex items-center space-x-2 py-1.5 pr-8 pl-3 rounded-t-md border-b focus:outline-none focus:ring-0 border-gray-300">
                        <span class="text-gray-700">Rp.</span>
                        <input type="text"
                            id="sgrossprofit"
                            name="sgrossprofit"
                            :value="formatNumber(gp)"
                            wire:model="sgrossprofit"
                            readonly
                            :class="{'text-red-500': gp < 0, 'text-green-700': gp >= 0}"
                            class="block w-full focus:ring-0 focus:outline-none border-0">
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
                <div class="" wire:ignore>
                    <label class="block font-medium">Pilih Vendor</label>
                    <select wire:model="cvendor" id="cvendor" class="w-full border rounded-md border-gray-300 p-2">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $vendor)
                        <option wire:key="{{$vendor}}" value="{{ $vendor->id }}">{{$vendor->customer_code}} - {{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('vendor') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- No Invoice -->
                <div>
                    <label for="creferenceno" class="block text-sm font-medium text-gray-700">No Invoice</label>
                    <input type="text" id="creferenceno" name="creferenceno" wire:model="creferenceno"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <!-- Date -->
                <div>
                    <label for="cdate" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" id="cdate" name="cdate" wire:model="cdate"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 2 -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Dr / Cr -->
                <div wire:ignore>
                    <label for="cdrcr" class="block text-sm font-medium text-gray-700">Dr / Cr</label>
                    <select id="cdrcr" name="cdrcr" wire:model="cdrcr"
                        class="block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="dr">Dr(+)</option>
                        <option value="cr">Cr(-)</option>
                    </select>
                </div>
                <!-- Currency -->
                <div>
                    <label for="ccurrency" class="block text-sm font-medium text-gray-700">Currency</label>
                    <input type="text" id="ccurrency" name="ccurrency" wire:model="ccurrency"
                        @input="ccurrency = $event.target.value.toUpperCase()"
                        x-model="ccurrency" class="block w-full py-1.5 pr-8 pl-3 rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <div>
                        <span class="text-gray-500 text-xs">* Use the currency code provided by the vendor</span>
                    </div>
                </div>
                <!-- Exchange Rate -->
                <div>
                    <label for="crate" class="block text-sm font-medium text-gray-700">Ex.rate</label>
                    <input type="text" id="crate" name="crate" wire:model="crate" :value="crate"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 3 -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Amount / Qty & Included Tax -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label for="camount_qty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                        <input type="text" id="camount_qty" name="camount_qty" wire:model="camount_qty" x-model.number="camount"
                            class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex-1" wire:ignore>
                        <label for="cincludedtax" class="block text-sm font-medium text-gray-700">Included Tax?</label>
                        <select id="cincludedtax" name="cincludedtax" wire:model="cincludedtax" x-model="cincludedtax"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                        </select>
                    </div>
                </div>
                <!-- FCY Amount -->
                <div>
                    <label for="cfcyamount" class="block text-sm font-medium text-gray-700">FCY Amount</label>
                    <input type="text" id="cfcyamount" name="cfcyamount" :value="formatNumber(cfcyAmount)" readonly
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <!-- Amount (IDR) -->
                <div>
                    <label for="camountidr" class="block text-sm font-medium text-gray-700">Amount (IDR)</label>
                    <input type="text" id="camountidr" name="camountidr" placeholder="" :value="formatNumber(camountidr)"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Row 4 -->
            <div class="grid grid-cols-3 gap-4">
                <!-- VAT / GST Type -->
                <div wire:ignore>
                    <label for="cvatgst" class="block text-sm font-medium text-gray-700">VAT / GST TAX</label>
                    <select id="cvatgst" name="cvatgst" wire:model="cvatgst"
                        class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select Tax</option>
                        @foreach($taxRates as $id => $rate)
                        <option value="{{$id}}">
                            {{$rate * 100}}%
                        </option>
                        @endforeach
                    </select>
                </div>
                <!-- W/H TAX RATE -->
                <div wire:ignore>
                    <label for="cwhtaxrate" class="block text-sm font-medium text-gray-700">W/H Tax Rate (cwhtaxrate)</label>
                    <select id="cwhtaxrate" name="cwhtaxrate" wire:model="cwhtaxrate" x-model="cwhtaxrate"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Tax</option>
                        @foreach($taxRatesWht as $id => $rate)
                        <option value="{{$id}}">
                            {{$rate*100}}%
                        </option>
                        @endforeach
                    </select>
                </div>
                <!-- Taxable Amount -->
                <div>
                    <label for="ctaxableamount" class="block text-sm font-medium text-gray-700">VAT TAX</label>
                    <input type="text" id="ctaxableamount" name="ctaxableamount"
                        :value="formatNumber(ctaxable)"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                    <input type="text" id="cvatgstamount" name="cvatgstamount" :value="formatNumber(ctaxamount)"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
                </div>

                <!-- W/H TAX AMOUNT -->
                <div>
                    <label for="cwhtaxamount" class="block text-sm font-medium text-gray-700">W/H TAX</label>
                    <input type="text" id="cwhtaxamount" name="cwhtaxamount" :value="formatNumber(cwhtaxamount)"
                        class="block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="0">
                </div>
            </div>


        </div>
    </div>

    <!-- Modal Footer (Buttons) -->
    <div class="flex justify-end mt-4 gap-2 p-4 border-t color-gray-200">
        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded-lg" @click="$refs.modalContent.scrollTo({ top: 0, behavior: 'smooth' })">
            Cancel
        </button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Save
        </button>
    </div>
</form>
@push('script')
@script()
<script>
    window.reinitSelect2 = () => {
        [{
                sel: '#sclient',
                model: 'sclient',
                placeholder: 'Select Client '
            }, {
                sel: '#sincludedtax',
                model: 'sincludedtax',
                placeholder: 'Select tax '
            }, {
                sel: '#cvatgst',
                model: 'cvatgst',
                placeholder: 'Select VAT '
            },
            {
                sel: '#svatgst',
                model: 'svatgst',
                placeholder: 'Select VAT '
            },
            {
                sel: '#swhtaxrate',
                model: 'swhtaxrate',
                placeholder: 'Select Tax Rate '
            },
            {
                sel: '#charge',
                model: 'charge',
                placeholder: 'Select Charge'
            },
            {
                sel: '#cwhtaxrate',
                model: 'cwhtaxrate',
                placeholder: 'Select Tax Rate'
            }, {
                sel: '#cvendor',
                model: 'cvendor',
                placeholder: 'Select Vendor'
            },
            {
                sel: '#freight',
                model: 'freight',
                placeholder: 'Select Freight'
            },
            {
                sel: '#cofdtype',
                model: 'ofdtype',
                placeholder: 'Select OFD  Type'
            },
            {
                sel: '#cdrcr',
                model: 'cdrcr',
                placeholder: 'Select DR CR'
            },
            {
                sel: '#sdrcr',
                model: 'sdrcr',
                placeholder: 'Select DR CR'
            }, {
                sel: '#unit',
                model: 'unit',
                placeholder: 'Select Unit'
            },
        ].forEach(({
            sel,
            model,
            placeholder
        }) => {
            const $el = $(sel);
            if (!$el.length) return;

            if ($el.hasClass('select2-hidden-accessible')) {}

            $el.select2({
                placeholder,
                allowClear: true,
                theme: 'tailwindcss-3',
                width: '100%',
            });

            // Watch for Livewire updates
            Livewire.hook('message.processed', () => {
                if ($el.val() !== $wire[model]) {
                    $el.val($wire[model]).trigger('change.select2');
                }
            });
            $el.off('change.lw').on('change.lw', function() {
                const value = $(this).val();
                $wire.set(model, value);
                // console.log(value);
            });
        });
    };
</script>
@endscript
@endpush