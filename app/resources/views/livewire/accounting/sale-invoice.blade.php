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
            <td class="font-bold">MBL:</td>
            <td>{{ $shipmentId }}</td>
            <td class="font-bold">Currency:</td>
            <td>
                <select wire:model="finalCurrency" class="w-full px-3 py-2 border rounded-lg">
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
                <td class="p-2 border">{{ $transaction->description }}</td>
                <td class="p-2 border">{{ $transaction->quantity }}</td>
                <td class="p-2 border">{{ $transaction->scurrency }}</td>
                <td class="p-2 border">
                    {{ number_format(floatval(str_replace(',', '.', str_replace('.', '', $transaction->samountidr))), 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format(floatval(str_replace(',', '.', str_replace('.', '', $transaction->svatgstamount))), 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format(floatval(str_replace(',', '.', str_replace('.', '', $transaction->swhtaxamount))), 2, ',', '.') }}
                </td>
                <td class="font-bold p-2 border">
                    {{ number_format(
                        floatval(str_replace(',', '.', str_replace('.', '', $transaction->samountidr))) +
                        floatval(str_replace(',', '.', str_replace('.', '', $transaction->svatgstamount))),
                        2, ',', '.'
                    ) }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="font-bold">
                <td colspan="3" class="p-2 border text-right">Total</td>
                <td class="p-2 border">
                    {{ number_format(
                $transactions->sum(fn($t) => floatval(str_replace(',', '.', str_replace('.', '', $t->samountidr)))),
                2, ',', '.'
            ) }}
                </td>
                <td class="p-2 border">
                    {{ number_format(
                $transactions->sum(fn($t) => floatval(str_replace(',', '.', str_replace('.', '', $t->svatgstamount)))),
                2, ',', '.'
            ) }}
                </td>
                <td class="p-2 border"></td>
                <td class="p-2 border">
                    {{ number_format(
                $transactions->sum(fn($t) => 
                    floatval(str_replace(',', '.', str_replace('.', '', $t->samountidr))) + 
                    floatval(str_replace(',', '.', str_replace('.', '', $t->svatgstamount)))
                ),
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
    <div x-data="{ open: false, pdfSrc: '', loading: false }" x-cloak
        @open-pdf-preview.window="loading = true; open = true; pdfSrc = $event.detail.pdf; console.log('PDF Loaded:', pdfSrc); setTimeout(() => loading = false, 1000);">
        <div x-show="open" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50">
            <div class="bg-white p-4 rounded-lg max-w-4xl w-full">
                <div class="flex justify-end">
                    <button @click="open = false" class="bg-red-600 text-white px-4 py-2 rounded">Close</button>
                </div>
                <iframe src="data:application/pdf;base64,{{ $pdfData }}" class="w-full h-[600px]"></iframe>
            </div>
        </div>
    </div>
    <div class="flex justify-end p-3">
        <a wire:navigate href="/view-shipments/{{ $shipmentId }}" class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg 
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