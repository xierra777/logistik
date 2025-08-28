<div class="max-w-full mx-auto p-6 bg-gradient-to-br from-cyan-50 via-purple-50 to-blue-50 min-h-screen"
    x-init="reinitSelect2()">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-xl p-8 mb-8 border border-purple-100">
        <div class="flex items-center justify-between">
            <div>
                <h1
                    class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-2">
                    <i class="fas fa-file-invoice-dollar mr-3 text-purple-500"></i>
                    Purchase Invoice
                </h1>
            </div>
            <div class="bg-gradient-to-r from-purple-200 to-pink-200 rounded-full p-4 animate-pulse">
                <i class="fas fa-sparkles text-3xl text-purple-600"></i>
            </div>
        </div>
    </div>

    <!-- Invoice Form -->
    <div
        class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-lg p-8 mb-8 border border-purple-100 hover:shadow-xl transition-all duration-300">
        <h2 class="text-2xl font-bold text-purple-700 mb-6 flex items-center">
            <i class="fas fa-edit mr-3 text-purple-500"></i>
            Invoice Details
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Invoice Number -->
            <div class="flex flex-col space-y-2 ">
                <label class="text-purple-700 font-semibold text-sm">Invoice No</label>
                <div class="relative">
                    <input type="text" wire:model.live="invoice_number"
                        class="w-full bg-purple-50 py-1.5 pr-8 pl-3 border-2 border-purple-200 rounded-md focus:border-purple-400 focus:bg-white transition-all duration-300 outline-none placeholder-purple-300"
                        placeholder="INV-2025-001">
                    <i class="fas fa-hashtag absolute right-3 top-1/2 transform -translate-y-1/2 text-purple-400"></i>
                </div>
            </div>

            <!-- Vendors -->
            <div class="flex flex-col space-y-2">
                <label class="text-purple-700 font-semibold text-sm">Vendors</label>
                <div wire:ignore>
                    <select name="vendors" id="vendorPurchasingInvoicing"
                        class="w-full py-1.5 pr-8 pl-3 bg-teal-50 border-2 border-teal-200 rounded-xl focus:border-teal-400 focus:bg-white transition-all duration-300 outline-none appearance-none"
                        wire:model.live="selectedVendor">
                        <option value=""></option>
                        @foreach($vendors as $vndr)
                        <option value="{{$vndr->id}}">
                            {{$vndr->name}}
                            ({{$vndr->total_transactions}} transaksi, {{$vndr->uninvoiced_transactions}} belum invoice)
                            @if($vndr->uninvoiced_transactions > 0)
                            ⚠️
                            @endif
                        </option>
                        @endforeach

                    </select>
                </div>
            </div>

            <!-- HAWB -->
            <div class="flex flex-col space-y-2">
                @if(in_array($shipment->shipmentType_job, ['air_inbound', 'air_outbound', 'domestics_transport']))
                <label class="text-purple-700 font-semibold text-sm">MAWB NO</label>
                @else
                <label class="text-purple-700 font-semibold text-sm">MBL NO</label>
                @endif <div class="bg-amber-50 rounded-xl py-1.5 pr-8 pl-3 border-2 border-amber-200">
                    <span class="text-amber-700 font-semibold">{{$job->jobBillLadingNo ?? '-'}}</span>
                </div>
            </div>

            <!-- Exchange Rate Toggle -->
            <div class="flex flex-col space-y-2">
                <label class="text-purple-700 font-semibold text-sm">Show Exchange Rate</label>
                <div class="flex items-center space-x-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model="showExchangeRate" class="sr-only peer">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600">
                        </div>
                        <span class="ml-3 text-purple-700 font-medium">Enable</span>
                    </label>
                </div>
            </div>

            <!-- Currency -->
            <div class="flex flex-col space-y-2">
                <label class="text-purple-700 font-semibold text-sm">Currency</label>
                <div class="relative">
                    <select wire:model.live="finalCurrency" id="finalCurrency"
                        class="w-full py-1.5 pr-8 pl-3 bg-teal-50 border-2 border-teal-200 rounded-xl focus:border-teal-400 focus:bg-white transition-all duration-300 outline-none appearance-none">
                        <option value=""></option>
                        <option value="IDR">Total dalam IDR</option>
                        <option value="USD">Total dalam USD</option>
                    </select>
                    <i
                        class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-teal-400"></i>
                </div>
            </div>
            <!-- Currency -->
            <div class="flex flex-col space-y-2" wire:ignore>
                <label class="text-purple-700 font-semibold text-sm">Bank</label>
                <div class="relative">
                    <select name="bank_id" id="bank_id"
                        class="w-full py-1.5 pr-8 pl-3 bg-teal-50 border-2 border-teal-200 rounded-xl focus:border-teal-400 focus:bg-white transition-all duration-300 outline-none appearance-none">
                        <option value=""></option>
                        @foreach($banks as $bank)
                        <option value="{{$bank->id}}">{{$bank->bank_name}} - {{$bank->customer->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Summary -->
    @if($shipmentId && $selectedVendor && $transactions->isNotEmpty())
    <div
        class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-lg p-8 mb-8 border border-purple-100 hover:shadow-xl transition-all duration-300">
        <h3 class="text-2xl font-bold text-purple-700 mb-6 flex items-center">
            <i class="fas fa-list-alt mr-3 text-purple-500"></i>
            Uninvoiced Transactions
        </h3>
        <!-- {{$transactions}} -->
        <div class="overflow-x-auto rounded-2xl border-2 border-purple-100">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-purple-100 to-pink-100">
                        <th class="p-4 text-left rounded-tl-2xl">
                            <div class="flex items-center space-x-3">
                                <label class="text-purple-700 font-semibold text-sm cursor-pointer">
                                    <input type="checkbox" wire:model.live="selectAll"
                                        class="w-5 h-5 text-purple-600 bg-purple-100 border-2 border-purple-300 rounded focus:ring-purple-500 focus:ring-2">
                                    @if($this->selectAll)
                                    All Selected ({{ count($selectedTransactionIds) }})
                                    @elseif(count($selectedTransactionIds) > 0)
                                    {{ count($selectedTransactionIds) }} Selected
                                    @else
                                    Select All
                                    @endif
                                </label>
                            </div>
                        </th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Charge</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Qty</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Currency</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Amount</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">VAT</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">WHT</th>
                        <th class="p-4 text-purple-700 font-semibold text-center rounded-tr-2xl">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $transaction)
                    <tr class="border-b border-purple-100 hover:bg-purple-50 transition-all duration-200">
                        <td class="p-4 text-center">
                            <input type="checkbox" wire:model.live="selectedTransactionIds"
                                value="{{ $transaction->id }}"
                                class="w-5 h-5 text-purple-600 bg-purple-100 border-2 border-purple-300 rounded focus:ring-purple-500 focus:ring-2">
                        </td>
                        <td class="p-4 text-gray-700 text-center">{{ $transaction->description ?? '' }}</td>
                        <td class="p-4 text-gray-700 text-center">{{ $transaction->quantity ?? '' }}</td>
                        <td class="p-4 text-gray-700 text-center">{{ $transaction->ccurrency ?? '' }}</td>
                        <td class="p-4 text-gray-700 text-center">{{ number_format($transaction->camountidr ?? 0, 2,
                            ',', '.') }}</td>
                        <td class="p-4 text-gray-700 text-center">{{ number_format($transaction->cvatgstamount ?? 0, 2,
                            ',', '.') }}</td>
                        <td class="p-4 text-gray-700 text-center">{{ number_format($transaction->cwhtaxamount ?? 0, 2,
                            ',', '.') }}</td>
                        <td class="p-4 font-bold text-purple-700 text-center">
                            {{ number_format(($transaction->camountidr ?? 0) + ($transaction->cvatgstamount ?? 0) +
                            ($transaction->cwhtaxamount ?? 0), 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gradient-to-r from-emerald-100 to-teal-100 font-bold">
                        <td class="p-4 rounded-bl-2xl"></td>
                        <td colspan="3" class="p-4 text-right text-emerald-800">Total</td>
                        <td class="p-4 text-emerald-800 text-center">{{ number_format($transactions->sum('camountidr')
                            ?? 0, 2, ',', '.') }}</td>
                        <td class="p-4 text-emerald-800 text-center">{{
                            number_format($transactions->sum('cvatgstamount') ?? 0, 2, ',', '.') }}</td>
                        <td class="p-4 text-emerald-800 text-center">{{ number_format($transactions->sum('cwhtaxamount')
                            ?? 0, 2, ',', '.') }}</td>
                        <td class="p-4 font-bold text-emerald-800 text-center rounded-br-2xl">
                            {{ number_format(($transactions->sum('camountidr') ?? 0) +
                            ($transactions->sum('cvatgstamount') ?? 0) + ($transactions->sum('cwhtaxamount') ?? 0), 2,
                            ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif

    <!-- Selected Transactions Summary -->
    @if(!empty($selectedTransactionIds))
    <div
        class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-3xl shadow-lg p-8 mb-8 border-2 border-blue-200 hover:shadow-xl transition-all duration-300">
        <h4 class="text-2xl font-bold text-blue-800 mb-6 flex items-center">
            <i class="fas fa-calculator mr-3 text-blue-600"></i>
            Selected Transactions Summary
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-blue-200 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-600 font-semibold text-sm">Subtotal</p>
                        <p class="text-2xl font-bold text-blue-800">
                            IDR {{ number_format($transactions->whereIn('id',
                            $selectedTransactionIds)->sum('camountidr') ?? 0, 2, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-coins text-3xl text-blue-400"></i>
                </div>
            </div>

            <div
                class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-emerald-200 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-emerald-600 font-semibold text-sm">VAT</p>
                        <p class="text-2xl font-bold text-emerald-800">
                            IDR {{ number_format($transactions->whereIn('id',
                            $selectedTransactionIds)->sum('cvatgstamount') ?? 0, 2, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-percent text-3xl text-emerald-400"></i>
                </div>
            </div>

            <div
                class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-amber-200 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-amber-600 font-semibold text-sm">WHT</p>
                        <p class="text-2xl font-bold text-amber-800">
                            IDR {{ number_format($transactions->whereIn('id',
                            $selectedTransactionIds)->sum('cwhtaxamount') ?? 0, 2, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-receipt text-3xl text-amber-400"></i>
                </div>
            </div>

            <div
                class="bg-white/70 backdrop-blur-sm rounded-2xl p-6 border border-purple-200 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-600 font-semibold text-sm">Total</p>
                        <p class="text-2xl font-bold text-purple-800">
                            IDR {{ number_format(($transactions->whereIn('id',
                            $selectedTransactionIds)->sum('camountidr') ?? 0) + ($transactions->whereIn('id',
                            $selectedTransactionIds)->sum('cvatgstamount') ?? 0) + ($transactions->whereIn('id',
                            $selectedTransactionIds)->sum('cwhtaxamount') ?? 0), 2, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-money-check-alt text-3xl text-purple-400"></i>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="bg-red-50 border-2 border-red-200 rounded-3xl p-6 mb-8 shadow-lg">
        <div class="flex items-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
            <h4 class="text-xl font-bold text-red-800">Please fix the following errors:</h4>
        </div>
        <ul class="space-y-2">
            @foreach($errors->all() as $error)
            <li class="flex items-center text-red-700">
                <i class="fas fa-times-circle text-red-500 mr-2"></i>
                {{ $error }}
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-lg p-8 mb-8 border border-purple-100">
        <div class="flex flex-wrap gap-4 justify-center">
            <button wire:click="save" wire:loading.attr="disabled" wire:target="save"
                class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2">
                <i class="fas fa-save"></i>
                <span wire:loading.remove wire:target="save">Save Invoice</span>
                <span wire:loading wire:target="save" class="flex items-center">
                    <i class="fas fa-spinner animate-spin mr-2"></i>
                    Saving...
                </span>
            </button>

            <button wire:click="saveAsDraft" wire:loading.attr="disabled" wire:target="saveAsDraft"
                class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-2">
                <i class="fas fa-file-alt"></i>
                <span wire:loading.remove wire:target="saveAsDraft">Save as Draft</span>
                <span wire:loading wire:target="saveAsDraft" class="flex items-center">
                    <i class="fas fa-spinner animate-spin mr-2"></i>
                    Saving Draft...
                </span>
            </button>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('error'))
    <div class="bg-red-50 border-2 border-red-200 rounded-3xl p-6 mb-8 shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 text-2xl mr-3"></i>
            <p class="text-red-800 font-semibold">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    @if(session()->has('message'))
    <div class="bg-green-50 border-2 border-green-200 rounded-3xl p-6 mb-8 shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
            <p class="text-green-800 font-semibold">{{ session('message') }}</p>
        </div>
    </div>
    @endif

    <!-- Invoices Table -->
    <div class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-lg p-8 mb-8 border border-purple-100">
        <h3 class="text-2xl font-bold text-purple-700 mb-6 flex items-center">
            <i class="fas fa-table mr-3 text-purple-500"></i>
            Issued Invoices
        </h3>

        <div class="overflow-x-auto rounded-2xl border-2 border-purple-100">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-purple-100 to-pink-100">
                        <th class="p-4 text-purple-700 font-semibold text-center rounded-tl-2xl">No</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Vendor Name</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Invoice Number</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Status</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Due Date</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Invoice Date</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Currency</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Total Amount</th>
                        <th class="p-4 text-purple-700 font-semibold text-center">Created By</th>
                        <th class="p-4 text-purple-700 font-semibold text-center ">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchasingInvoice as $invs)
                    <tr class="border-b border-purple-100 hover:bg-purple-50 transition-all duration-200">
                        <td class="p-4 text-center text-gray-700">{{ $loop->iteration }}</td>
                        <td class="p-4  text-gray-700 font-semibold">{{ $invs->client->name ?? '' }}</td>
                        <td class="p-4 text-center text-gray-700 font-semibold">{{ $invs->invoice_number ?? '' }}</td>
                        <td class="p-4 text-center whitespace-nowrap">
                            @if($invs->status === 'issued')
                            <span class="bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>ISSUED
                            </span>
                            @elseif($invs->status === 'draft')
                            <span class="bg-gray-100 text-gray-800 px-4 py-2 rounded-full text-sm font-semibold">
                                <i class="fas fa-file-alt mr-1"></i>DRAFT
                            </span>
                            @elseif($invs->status === 'void')
                            <span class="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-semibold">
                                <i class="fas fa-ban mr-1"></i>VOID
                            </span>
                            @endif
                        </td>

                        <td class="p-4 text-center">
                            @php
                            $dueTime = $invs->due_date;
                            $status = $invs->status;
                            @endphp
                            <div x-data="countdownTimer('{{ \Carbon\Carbon::parse($invs->due_date)->toIso8601String() }}', '{{ $status }}')"
                                x-init="start()" class="font-bold" :class="{
            'text-red-600': time === 'EXPIRED',
            'text-yellow-600': time === 'DRAFT',
            'text-blue-600': time !== 'EXPIRED' && time !== 'DRAFT' && time !== '',
            'text-gray-400': time === ''
        }">
                                <span x-text="time" class="text-sm">LOADING...</span>
                            </div>
                            @if($invs->due_stats === 'issued')
                            <div class="text-green-600 text-sm mt-1">{{$invs->due_date}}</div>
                            @endif
                        </td>
                        <td class="p-4 text-center text-gray-700">{{ $invs->invoice_date ?? '' }}</td>
                        <td class="p-4 text-center text-gray-700">
                            {{ optional($invs->transactions->first())->ccurrency ?? '' }}
                        </td>
                        <td class="p-4 text-center text-gray-700 font-semibold">{{ number_format($invs->total_amount ??
                            0, 2, ',', '.') }}</td>
                        <td class="p-4 text-center text-gray-700">{{$invs->users->name ?? ''}}</td>
                        <td class="p-4">
                            <div class="flex justify-center items-center space-x-2">
                                @if($invs->status === 'draft')
                                <button
                                    @click="window.dispatchEvent(new CustomEvent('confirm-issue-invoice', { detail: { id: {{ $invs->id }} } }))"
                                    wire:loading.attr="disabled" wire:target="issueInvoice"
                                    class="w-10 h-10 bg-gradient-to-r from-green-400 to-emerald-500 hover:from-green-500 hover:to-emerald-600 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
                                    title="Issue Invoice">
                                    <i class="fas fa-check text-sm"></i>
                                </button>
                                @endif

                                <button wire:click="previewPDF({{ $invs->id }})" wire:loading.attr="disabled"
                                    wire:target="previewPDF"
                                    class="w-10 h-10 bg-gradient-to-r from-yellow-400 to-orange-500 hover:from-yellow-500 hover:to-orange-600 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
                                    title="Preview Invoice">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>

                                <button wire:click="generatePDF({{ $invs->id }})" wire:loading.attr="disabled"
                                    wire:target="generatePDF"
                                    class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-500 hover:from-blue-500 hover:to-purple-600 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300"
                                    title="Print Invoice">
                                    <i class="fas fa-print text-sm"></i>
                                </button>
                                @if($invs->status === 'issued')
                                <!-- Tombol Void dengan Animasi -->
                                <button wire:click="confirmVoid({{ $invs->id }})" wire:loading.attr="disabled"
                                    class="group relative w-10 h-10 bg-gradient-to-r from-red-400 to-pink-500 hover:from-red-500 hover:to-pink-600 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-110 transition-all duration-300 active:scale-95"
                                    title="Void Invoice">
                                    <i
                                        class="fas fa-ban text-sm group-hover:rotate-12 transition-transform duration-300"></i>

                                    <!-- Ripple Effect -->
                                    <div
                                        class="absolute inset-0 rounded-full bg-white opacity-0 group-active:opacity-20 transition-opacity duration-150">
                                    </div>
                                </button>
                                @endif
                                @if($invs->status === 'void')
                                <!-- Tombol Void dengan Animasi -->
                                <button wire:click="reasonVoidingJobSaleInvoice({{ $invs->id }})"
                                    wire:loading.attr="disabled" title="Reason Voiding"
                                    class="group relative w-10 h-10 bg-gradient-to-l from-red-400 to-pink-300 hover:from-red-500 hover:to-pink-600 text-white rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transform hover:-translate-y-1 hover:scale-110 transition-all duration-300 active:scale-95">
                                    <i class="fa-solid fa-info"></i>
                                    <!-- Ripple Effect -->
                                    <div
                                        class="absolute inset-0 rounded-full bg-white opacity-0 group-active:opacity-20 transition-opacity duration-150">
                                    </div>
                                </button>
                                @endif


                                <!-- Custom Animations -->
                                <style>
                                    @keyframes fadeIn {
                                        from {
                                            opacity: 0;
                                        }

                                        to {
                                            opacity: 1;
                                        }
                                    }

                                    .animate-fadeIn {
                                        animation: fadeIn 0.3s ease-out;
                                    }

                                    /* Pulse animation untuk row yang baru di-void */
                                    .invoice-row.animate-pulse {
                                        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1);
                                    }

                                    @keyframes pulse {

                                        0%,
                                        100% {
                                            opacity: 1;
                                        }

                                        50% {
                                            opacity: .8;
                                        }
                                    }
                                </style>

                                <script>
                                    // Listen for remove animation event
                                    document.addEventListener('livewire:init', () => {
                                        Livewire.on('remove-void-animation', (event) => {
                                            setTimeout(() => {
                                                // Remove the void animation class after delay
                                                const invoiceRow = document.querySelector(`[data-invoice-id="${event.invoiceId}"]`);
                                                if (invoiceRow) {
                                                    invoiceRow.classList.remove('opacity-50', 'scale-95', 'bg-red-50');
                                                }
                                            }, 3000);
                                        });
                                    });
                                </script>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @if($voidReason_job_sale_invoice)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn"
        x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl transform" x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4" x-cloak>

            <!-- Header -->
            <div class="text-center mb-6">
                <div
                    class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <i class="fas fa-info text-red-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Reason Voiding Invoice</h3>
                <p class="text-gray-600">Invoice #{{ $invoice_number }}</p>
            </div>


            <!-- Reason Input -->
            <div class="mb-6">
                <textarea wire:model="void_reason" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200 resize-none"></textarea>

            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-3">
                <button type="button" wire:click="cancelReasonVoidingJobSaleInvoice"
                    class="px-6 py-3 bg-red-100 text-gray-700 rounded-lg hover:bg-red-200 transition-colors duration-200 font-medium disabled:opacity-50">
                    Ok
                </button>
            </div>
        </div>
    </div>
    @endif
    @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn"
        x-data="{ show: false }" x-init="setTimeout(() => show = true, 50)" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>

        <div class="bg-white rounded-2xl p-6 w-full max-w-md mx-4 shadow-2xl transform" x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4" x-cloak>

            <!-- Header -->
            <div class="text-center mb-6">
                <div
                    class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <i class="fas fa-ban text-red-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Void Invoice</h3>
                <p class="text-gray-600">Invoice #{{ $invoice_number }}</p>
            </div>

            <form wire:submit.prevent="voidInvoice">
                <!-- Reason Input -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">
                        <i class="fas fa-edit mr-2"></i>Alasan Void
                    </label>
                    <textarea wire:model="void_reason" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors duration-200 resize-none"
                        placeholder="Masukkan alasan kenapa invoice ini di-void..." {{ $isVoiding ? 'disabled' : ''
                        }}></textarea>
                    @error('void_reason')
                    <span class="text-red-500 text-sm flex items-center mt-1">
                        <i class="fas fa-exclamation-triangle mr-1"></i>{{ $message }}
                    </span>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="cancelVoid" {{ $isVoiding ? 'disabled' : '' }}
                        class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors duration-200 font-medium disabled:opacity-50">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>

                    <div x-data>
                        <button type="button" wire:loading.attr="disabled"
                            class="px-6 py-3 bg-gradient-to-r from-red-500 to-pink-600 text-white rounded-lg hover:from-red-600 hover:to-pink-700 transition-all duration-200 font-medium disabled:opacity-50 transform hover:scale-105 active:scale-95 min-w-[140px]"
                            @click="
            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this action!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d8',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, void it!',
                cancelButtonText: 'No, Keep it',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.voidInvoice();
                }
            })
        ">

                            <span wire:loading.remove wire:target="voidInvoice">
                                <i class="fas fa-ban mr-2"></i>Konfirmasi Void
                            </span>

                            <span wire:loading wire:target="voidInvoice" class="flex items-center justify-center">
                                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif
    <!-- PDF Preview Modal -->
    <div x-cloak x-data="{ open: false, pdfSrc: '', loading: false }" x-cloak @open-pdf-preview.window="
            loading = true;
            open = true;
            pdfSrc = $event.detail.pdf;
            console.log('PDF Loaded:', pdfSrc);
            setTimeout(() => loading = false, 800);
         ">
        <div x-show="open" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex justify-center items-center z-50 p-4"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="bg-white rounded-3xl shadow-2xl max-w-6xl w-full max-h-[90vh] overflow-hidden">
                <div
                    class="flex justify-between items-center p-6 border-b border-gray-200 bg-gradient-to-r from-purple-100 to-pink-100">
                    <h2 class="text-2xl font-bold text-purple-700 flex items-center">
                        <i class="fas fa-file-pdf mr-3 text-purple-500"></i>
                        Invoice Preview
                    </h2>
                    <button @click="open = false"
                        class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-6 py-2 rounded-xl font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-times mr-2"></i>Close
                    </button>
                </div>

                <div class="p-6">
                    <iframe :src="pdfSrc"
                        class="w-full h-[70vh] border-2 border-purple-200 rounded-2xl shadow-lg"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-end">
        <a href="{{ route('viewShipment', $shipmentId) }}"
            class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white font-bold py-4 px-8 rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Job</span>
        </a>
    </div>
</div>
@push('scripts')
<script>
    // document.addEventListener('livewire:navigated', function() {
    //     window.countdownTimer(dueTime, status);
    // });


    function countdownTimer(dueTime, status) {
        return {
            time: '',
            start() {
                const end = new Date(dueTime).getTime();

                const update = () => {
                    // Handle different statuses
                    if (status === 'void') {
                        this.time = '';
                        return;
                    }

                    if (status === 'draft') {
                        this.time = 'DRAFT';
                        return;
                    }

                    if (status === 'issued') {
                        const now = new Date().getTime();
                        const diff = end - now;

                        if (diff <= 0) {
                            this.time = 'EXPIRED';
                            return;
                        }

                        const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const s = Math.floor((diff % (1000 * 60)) / 1000);

                        this.time = `${d}d ${h}h ${m}m ${s}s`;
                    }
                };

                update();

                // Only set interval for issued invoices
                if (status === 'issued') {
                    setInterval(update, 1000);
                }
            }
        }
    }
</script>
<script>
    window.addEventListener('showSuccessAlert', event => {
        let data;

        // Handle both array and object
        if (Array.isArray(event.detail)) {
            data = event.detail[0]; // Ambil element pertama jika array
        } else {
            data = event.detail; // Gunakan langsung jika object
        }

        // console.log('Processed data:', data);

        if (data && data.title) {
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                confirmButtonText: data.confirmButtonText || 'OK'
            });
        } else {
            // console.error('Invalid data structure:', data);
        }
    });
    // Di Blade atau script JS (pastikan setelah Livewire dimuat)
</script>
@script
<script>
    window.reinitSelect2 = () => {
        [{
            sel: '#vendorPurchasingInvoicing',
            model: 'selectedVendor',
            placeholder: 'Select Vendor  '
        }, {
        sel: '#bank_id',
        model: 'bank_id',
        placeholder: 'Select Bank '
        },{sel: '#finalCurrency',
        model: 'finalCurrency',
        placeholder: 'Select Currency '
        },].forEach(({
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
    window.addEventListener('confirm-issue-invoice', function(e) {
        const id = e.detail.id;
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!\n The transaction will be locked!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'No, Wait',
            cancelButtonColor: 'red',
            confirmButtonText: 'Yes, issue it!'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('aku ke trigger');
                $wire.dispatch('issueInvoice', [id]);
            }
        });
    });
</script>
@endscript
@endpush