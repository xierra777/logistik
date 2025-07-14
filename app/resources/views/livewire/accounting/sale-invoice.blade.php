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
                <select wire:model.live="customer_id" class="w-full px-3 py-2 border rounded-lg">
                    <option value="">Select Client</option>
                    @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        <tr class>
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
    @if($shipmentId && $customer_id && $transactions->isNotEmpty())
    <h3 class="text-lg font-bold mb-2">Transaction Details</h3>
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
                <td class="p-2 border">{{ $transaction->description }}</td>
                <td class="p-2 border">{{ $transaction->quantity }}</td>
                <td class="p-2 border">{{ $transaction->scurrency }}</td>
                <td class="p-2 border">
                    {{ number_format($transaction->samountidr, 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format($transaction->svatgstamount, 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format($transaction->swhtaxamount, 2, ',', '.') }}
                </td>
                <td class="font-bold p-2 border">
                    {{ number_format($transaction->samountidr + $transaction->svatgstamount + $transaction->swhtaxamount, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td></td>
                <td colspan="3" class="p-2 border text-right">Total</td>
                <td class="p-2 border">
                    {{ number_format($transactions->sum('samountidr'), 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format($transactions->sum('svatgstamount'), 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format($transactions->sum('swhtaxamount'), 2, ',', '.') }}
                </td>
                <td class="font-bold p-2 border">
                    {{ number_format(
                        $transactions->sum('samountidr') +
                        $transactions->sum('svatgstamount') +
                        $transactions->sum('swhtaxamount'),
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
    <table class="w-full border text-sm mb-6 mt-4">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">No</th>
                <th class="p-2 border">Invoice Number</th>
                <th class="p-2 border">Client</th>
                <th class="p-2 border">Invoice Date</th>
                <th class="p-2 border">Due Date</th>
                <th class="p-2 border">Currency</th>
                <th class="p-2 border">Total Amount</th>
                <th class="p-2 border">Status</th>

            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $inv)
            <tr>
                <td class="p-2 border text-center">
                    {{$loop->iteration}}
                </td>
                <td class="p-2 border">{{ $inv->invoice_number }}</td>
                <td class="p-2 border">{{ $inv->client->name }}</td>
                <td class="p-2 border">
                    {{$inv->invoice_date}}
                </td>
                <td class="p-2 border">
                    {{$inv->due_date}}
                </td>
                <td class="p-2 border">
                    {{$inv->currency}}
                </td>
                <td class="font-bold p-2 border">
                    {{number_format($inv->total_amount, 2, ',', '.')}}
                </td>
                <td class="font-bold p-2 border uppercase {{
                    $inv->status === 'draft' ? 'text-gray-500' :
                    ($inv->status === 'void' ? 'text-red-500' : 'text-green-500')
                }}">
                    {{$inv->status}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div x-data="{ open: false, pdfSrc: '', loading: false }" x-cloak
        @open-pdf-preview.window="loading = true; open = true; pdfSrc = $event.detail.pdf; console.log('PDF Loaded:', pdfSrc); setTimeout(() => loading = false, 1000);">
        <div x-show="open" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50">
            <div class="bg-white p-4 rounded-lg max-w-4xl w-full">
                <div class="flex justify-end p-2">
                    <button @click="open = false" class="bg-red-600 text-white m-1 px-4 py-2 rounded">Close</button>
                </div>
                <iframe src="data:application/pdf;base64,{{ $pdfData }}" class="w-full h-[600px]"></iframe>
            </div>
        </div>
    </div>
    <div class="flex justify-end p-3">
        <a wire:navigate href="/view-shipment/{{ $shipmentId }}" class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg 
               transform transition duration-200 ease-in-out shadow:hover-cyan-200
               hover:bg-cyan-400 hover:scale-100  ">
            Back
        </a>
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