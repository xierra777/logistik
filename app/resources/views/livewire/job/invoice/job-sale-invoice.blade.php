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
                <select name="" id="" wire:model.livew="customer">
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
                    class="w-full h-4 rounded border-gray-300 text-blue-600 bg-gray-100 
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
    <h3 class="text-lg font-bold mb-2">Transaction Details (From Job)</h3>
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
                    <input type="checkbox" wire:model="selectedTransactionIds" value="{{ $transaction->id }}" class="form-checkbox text-blue-600 rounded-md">
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


    <!-- Buttons -->
    <div class="mt-4 flex gap-2">
        <button
            wire:click="generatePDF"
            wire:loading.attr="disabled"
            wire:target="generatePDF"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            <span wire:loading.remove wire:target="generatePDF">
                Print Invoice for Selected Customer
            </span>
            <span wire:loading wire:target="generatePDF">
                Processing...
            </span>
        </button>
        <button
            wire:click="previewPDF"
            wire:loading.attr="disabled"
            wire:target="previewPDF"
            class="bg-yellow-600 text-white px-4 py-2 rounded-lg">
            <span wire:loading.remove wire:target="previewPDF">
                Preview Invoice
            </span>
            <span wire:loading wire:target="previewPDF">
                Loading...
            </span>
        </button>
    </div>


    @if(session()->has('error'))
    <div class="mt-4 p-2 bg-red-200 text-red-800 rounded-lg">
        {{ session('error') }}
    </div>
    @endif
    @if(session()->has('message'))
    <div class="mt-4 p-2 bg-green-200 text-green-800 rounded-lg">
        {{ session('message') }}
    </div>
    @endif
</div>