<div class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-4">Purchase Invoice</h2>

    <div class="border p-4 rounded-lg mb-6">
        <table class="w-full text-sm border-collapse">
            <tr>
                <td class="font-bold">Invoice No:</td>
                <td><input type="text" wire:model="invoice_number" class="w-full px-3 py-2 border rounded-lg bg-gray-100"></td>
                <td class="font-bold">Vendor:</td>
                <td>
                    <select wire:model.live="vendor_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Select Vendor</option>
                        @foreach($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <select wire:model="finalCurrency" class="w-full px-3 py-2 border rounded-lg">
                        <option value="IDR">Total dalam IDR</option>
                        <option value="USD">Total dalam USD</option>
                    </select>
                </td>
            </tr>
        </table>
    </div>
    @if($shipmentId && $vendor_id && $transactions->isNotEmpty())
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
                <td class="p-2 border">{{ $transaction->pcurrency }}</td>
                <td class="p-2 border">
                    {{ number_format(floatval(str_replace(',', '.', str_replace('.', '', $transaction->camountidr ?? ''))), 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format(floatval(str_replace(',', '.', str_replace('.', '', $transaction->cvatgstamount ?? ''))), 2, ',', '.') }}
                </td>
                <td class="p-2 border">
                    {{ number_format(floatval(str_replace(',', '.', str_replace('.', '', $transaction->cwhtaxamount ?? ''))), 2, ',', '.') }}
                </td>
                <td class="font-bold p-2 border">
                    {{ number_format(
                        floatval(str_replace(',', '.', str_replace('.', '', $transaction->camountidr))) +
                        floatval(str_replace(',', '.', str_replace('.', '', $transaction->cvatgstamount))),
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
                $transactions->sum(fn($t) => floatval(str_replace(',', '.', str_replace('.', '', $t->camountidr)))),
                2, ',', '.'
            ) }}
                </td>
                <td class="p-2 border">
                    {{ number_format(
                $transactions->sum(fn($t) => floatval(str_replace(',', '.', str_replace('.', '', $t->cvatgstamount)))),
                2, ',', '.'
            ) }}
                </td>
                <td class="p-2 border"></td>
                <td class="p-2 border">
                    {{ number_format(
                $transactions->sum(fn($t) => 
                    floatval(str_replace(',', '.', str_replace('.', '', $t->pamountidr))) + 
                    floatval(str_replace(',', '.', str_replace('.', '', $t->cvatgstamount)))
                ),
                2, ',', '.'
            ) }}
                </td>
            </tr>
        </tfoot>
    </table>
    @endif

    <button wire:click="previewPDF" class="bg-yellow-500 text-white p-2 rounded mt-4">Preview Invoice</button>
    <button wire:click="generatePDF" class="bg-blue-500 text-white p-2 rounded mt-4">Save Invoice</button>
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
        <div class="flex justify-end p-3">
            <a wire:navigate href="/view-shipment/{{ $shipmentId }}" class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg 
                   transform transition duration-200 ease-in-out shadow:hover-cyan-200
                   hover:bg-cyan-400 hover:scale-100  ">
                Back
            </a>
        </div>
    </div>
</div>