<div class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-4">Sale Invoice</h2>

    <!-- Invoice Header -->
    <table class="w-full text-sm border-collapse p-2 ">
        <tr class="gap-2">
            <td class="font-bold">Invoice No:</td>
            <td>
                <input type="text" wire:model="invoice_number" class="w-full px-3 py-2 border rounded-lg bg-gray-100">
            </td>
            <td class="font-bold">Client:</td>
            <td>
                <select name="" id="" class="cursor-pointer" wire:model.live="customer">
                    @if($job->client)
                    <option value="{{ $job->client->id }}">{{ $job->client->name }}</option>
                    @else
                    <option value="">Client not found</option>
                    @endif
                </select>
            </td>
        </tr>
        <tr>
            <td class="font-bold">HAWB NO</td>
            <td class="font-bold">{{$job->jobBillLadingNo}}</td>
            <td class="font-bold">Show Exchange Rate:</td>
            <td class="border-gray-900 ">
                <input
                    type="checkbox"
                    wire:model="showExchangeRate"
                    class="h-4 rounded border-gray-300 text-blue-600 bg-gray-100
                    focus:ring-blue-500 focus:ring-2
                    dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600
                    hover:scale-105 transition-transform duration-150 ease-in-out
                    cursor-pointer">
            </td>
            <td class="font-bold">Currency:</td>
            <td>
                <select wire:model.live="finalCurrency" class="w-full px-3 py-2 border rounded-lg">
                    <option value="IDR">Total dalam IDR</option>
                    <option value="USD">Total dalam USD</option>
                </select>
            </td>
        </tr>
    </table>



    <!-- Transaction Summary -->
    @if($job && $job->client && $transactions->isNotEmpty())
    <h3 class="text-lg font-bold mb-2 mt-6">Transaction Details (From Job)</h3>
    <table class="w-full border text-sm mb-6">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border text-left">
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input
                            type="checkbox"
                            wire:click="selectAllTransactions"
                            class="form-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 transition duration-150">
                        <span class="text-sm text-gray-700">Select All</span>
                    </label>
                </th>
                <th class="p-2 border">Charge</th>
                <th class="p-2 border">Qty</th>
                <th class="p-2 border">Currency</th>
                <th class="p-2 border">Amount</th>
                <th class="p-2 border">VAT</th>
                <th class="p-2 border">WHT</th>
                <th class="p-2 border">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td class="p-2 border text-center">
                    <input type="checkbox" wire:model.live="selectedTransactionIds" value="{{ $transaction->id }}" class="form-checkbox text-blue-600 rounded-md">
                </td>
                <td class="p-2 border">{{ $transaction->description ?? '' }}</td>
                <td class="p-2 border">{{ $transaction->quantity ?? '' }}</td>
                <td class="p-2 border">{{ $transaction->scurrency ?? '' }}</td>
                <td class="p-2 border">
                    {{ number_format($transaction->samountidr ?? 0, 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format($transaction->svatgstamount ?? 0, 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format($transaction->swhtaxamount ?? 0, 2, ',', '.') }}
                </td>
                <td class="font-bold p-2 border">
                    {{ number_format(($transaction->samountidr ?? 0) + ($transaction->svatgstamount ?? 0) + ($transaction->swhtaxamount ?? 0), 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td></td>
                <td colspan="3" class="p-2 border text-right">Total</td>
                <td class="p-2 border">
                    {{ number_format($transactions->sum('samountidr') ?? 0, 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format($transactions->sum('svatgstamount') ?? 0, 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format($transactions->sum('swhtaxamount') ?? 0, 2, ',', '.') }}
                </td>
                <td class="font-bold p-2 border">
                    {{ number_format(
                    ($transactions->sum('samountidr') ?? 0) +
                    ($transactions->sum('svatgstamount') ?? 0) +
                    ($transactions->sum('swhtaxamount') ?? 0),
                    2, ',', '.'
                ) }}
                </td>
            </tr>
        </tfoot>
    </table>
    @endif

    <!-- Selected Transactions Summary -->
    @if(!empty($selectedTransactionIds))
    <div class="bg-blue-50 p-4 rounded-lg mb-4">
        <h4 class="font-bold text-blue-800 mb-2">Selected Transactions Summary</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="font-medium">Subtotal:</span>
                <div class="text-lg font-bold">
                    IDR {{ number_format($transactions->whereIn('id', $selectedTransactionIds)->sum('samountidr') ?? 0, 2, ',', '.') }}
                </div>
            </div>
            <div>
                <span class="font-medium">VAT:</span>
                <div class="text-lg font-bold">
                    IDR {{ number_format($transactions->whereIn('id', $selectedTransactionIds)->sum('svatgstamount') ?? 0, 2, ',', '.') }}
                </div>
            </div>
            <div>
                <span class="font-medium">WHT:</span>
                <div class="text-lg font-bold">
                    IDR {{ number_format($transactions->whereIn('id', $selectedTransactionIds)->sum('swhtaxamount') ?? 0, 2, ',', '.') }}
                </div>
            </div>
            <div>
                <span class="font-medium">Total:</span>
                <div class="text-xl font-bold text-green-600">
                    IDR {{ number_format(
                        ($transactions->whereIn('id', $selectedTransactionIds)->sum('samountidr') ?? 0) +
                        ($transactions->whereIn('id', $selectedTransactionIds)->sum('svatgstamount') ?? 0) +
                        ($transactions->whereIn('id', $selectedTransactionIds)->sum('swhtaxamount') ?? 0),
                        2, ',', '.'
                    ) }}
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Validation Errors -->
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
        <h4 class="font-bold text-red-800 mb-2">Please fix the following errors:</h4>
        <ul class="list-disc list-inside text-red-700 text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Buttons -->
    <div class="mt-6 flex flex-wrap gap-2">
        <button
            wire:click="save"
            wire:loading.attr="disabled"
            wire:target="save"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition disabled:opacity-50">
            <span wire:loading.remove wire:target="save">
                Save Invoice
            </span>
            <span wire:loading wire:target="save">
                Saving...
            </span>
        </button>

        <button
            wire:click="saveDraft"
            wire:loading.attr="disabled"
            wire:target="saveDraft"
            class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition disabled:opacity-50">
            <span wire:loading.remove wire:target="saveDraft">
                Save as Draft
            </span>
            <span wire:loading wire:target="saveDraft">
                Saving Draft...
            </span>
        </button>


    </div>

    <!-- Flash Messages -->
    @if(session()->has('error'))
    <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ session('error') }}
        </div>
    </div>
    @endif

    @if(session()->has('message'))
    <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('message') }}
        </div>
    </div>
    @endif
    <table class="w-full border text-sm mb-6 mt-4">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">No</th>
                <th class="p-2 border">Invoice number</th>
                <th class="p-2 border">Status</th>
                <th class="p-2 border">Due Date</th>
                <th class="p-2 border">Invoice Date</th>
                <th class="p-2 border">Total Amount</th>
                <th class="p-2 border">Created By</th>
                <th class="p-2 border">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoicesIssued as $invs)
            <tr class="text-center">
                <td class="p-2 border">{{ $loop->iteration }}</td>
                <td class="p-2 border">{{ $invs->invoice_number ?? '' }}</td>
                <td class="p-2 border uppercase ">
                    <p class=" @if($invs->status === 'issued') bg-green-200 text-green-800
                    @elseif($invs->status === 'draft') bg-gray-200 text-gray-800
                    @elseif($invs->status === 'void') bg-red-200 text-red-800
                    @endif rounded-lg p-1"> {{ $invs->status ?? '' }}
                    </p>
                </td>
                @php
                $dueTime = $invs->due_date ;// fallback kalau kosong
                @endphp
                <td class="p-2 border font-bold">
                    <div
                        x-data="countdownTimer('{{ \Carbon\Carbon::parse($invs->due_date)->toIso8601String() }}')"
                        x-init="start()"
                        class="text-red-600">
                        <span x-text="time">LOADING...</span>
                    </div>
                    /
                    <p class="text-green-500">{{$invs->due_date}}</p>
                </td>

                <td class="p-2 border">{{ $invs->invoice_date ?? '' }}</td>
                <td class="p-2 border">
                    {{ number_format($invs->total_amount ?? 0, 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{$invs->created_by}}
                </td>
                <td class="p-2 border">

                    <button class="border p-1 pointer-cursor rounded-full bg-yellow-400 text-center" wire:click="previewPDF({{ $invs->id }})"
                        wire:loading.attr="disabled"
                        wire:target="previewPDF">
                        <i class="fa-solid fa-eye fa-lg" style="color: #ffffff;"></i>
                    </button>
                    <button class="border pointer-cursor p-1 rounded-full bg-red-600 text-center" wire:click="generatePDF"
                        wire:loading.attr="disabled"
                        wire:target="generatePDF">
                        <i class="fa-solid fa-print fa-lg" style="color: #ffffff;"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div
        x-data="{ open: false, pdfSrc: '', loading: false }"
        x-cloak
        @open-pdf-preview.window="
        loading = true;
        open = true;
        pdfSrc = $event.detail.pdf;
        console.log('PDF Loaded:', pdfSrc);
        setTimeout(() => loading = false, 800);
    ">
        <!-- Modal -->
        <div x-show="open"
            class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50 transition"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90">
            <div class="bg-white p-4 rounded-lg shadow-xl max-w-5xl w-full relative">
                <!-- Loading spinner (optional) -->
                <!-- <template x-if="loading">
                    <div class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center z-50">
                        <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-cyan-600"></div>
                    </div>
                </template> -->

                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-2 mb-2">
                    <h2 class="text-lg font-semibold">Invoice Preview</h2>
                    <button @click="open = false" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Close</button>
                </div>

                <!-- PDF Viewer -->
                <iframe
                    :src="pdfSrc"
                    class="w-full h-[600px] border rounded"
                    frameborder="0"></iframe>
            </div>
        </div>
    </div>

    <div class="flex justify-end p-3">
        <a href="{{ route('viewJob', $jobId) }}"
            class="bg-blue-200 p-2 rounded-md hover:bg-blue-500 hover:text-white 
          transition-transform transform hover:scale-105 inline-block">
            back
        </a>
    </div>
</div>
@push('scripts')
<script>
    function countdownTimer(dueTime) {
        return {
            time: '',
            start() {
                const end = new Date(dueTime).getTime();

                const update = () => {
                    const now = new Date().getTime();
                    const diff = end - now;

                    if (diff <= 0) {
                        this.time = 'Expired';
                        return;
                    }

                    const d = Math.floor(diff / (1000 * 60 * 60 * 24));
                    const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((diff % (1000 * 60)) / 1000);

                    this.time = `${d}d ${h}h ${m}m `;
                };

                update();
                setInterval(update, 1000);
            }
        }
    }
</script>
@endpush