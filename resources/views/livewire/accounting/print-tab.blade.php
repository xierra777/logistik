<div class="p-4 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-4">Print Invoice / Purchase Invoice</h2>

    <!-- Dropdown untuk memilih tipe invoice -->
    <div class="mb-4">
        <label class="block mb-2 font-medium">Pilih Tipe Invoice:</label>
        <select wire:model="selectedType" class="border p-2 rounded w-full">
            <option value="sale">Sale Invoice</option>
            <option value="purchase">Purchase Invoice (PI)</option>
        </select>
    </div>

    <!-- Tampilkan daftar invoice berdasarkan pilihan -->
    <div>
        @if($invoices->count())
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">Invoice Number</th>
                    <th class="border px-4 py-2">Invoice Date</th>
                    <th class="border px-4 py-2">Client/Vendor</th>
                    <th class="border px-4 py-2">Grand Total</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td class="border px-4 py-2">{{ $invoice->invoice_number }}</td>
                    <td class="border px-4 py-2">{{ $invoice->invoice_date }}</td>
                    <td class="border px-4 py-2">
                        <!-- Nama client/vendor menjadi link untuk menampilkan rincian -->
                        <a href="#" wire:click.prevent="showExpenses({{ $invoice->id }})" class="text-blue-600 hover:underline">
                            {{ $selectedType == 'sale' ? ($invoice->client->name ?? '-') : ($invoice->vendor->name ?? '-') }}
                        </a>
                    </td>
                    <td class="border px-4 py-2">{{ number_format($invoice->grand_total, 2) }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="printInvoice({{ $invoice->id }})" class="bg-blue-500 text-white px-3 py-1 rounded">
                            Print
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Tidak ada invoice untuk tipe <strong>{{ $selectedType }}</strong>.</p>
        @endif
    </div>

    <!-- Modal untuk menampilkan rincian pengeluaran -->
    @if($selectedInvoice)
    <div class="fixed inset-0 flex items-center justify-center bg-gray-700 bg-opacity-50 z-50">
        <div class="bg-white p-4 rounded shadow-lg w-1/2">
            <h3 class="text-xl font-bold mb-4">Rincian Pengeluaran Invoice {{ $selectedInvoice->invoice_number }}</h3>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Charge</th>
                        <th class="border px-4 py-2">Qty</th>
                        <th class="border px-4 py-2">Amount</th>
                        <th class="border px-4 py-2">VAT</th>
                        <th class="border px-4 py-2">WHT</th>
                        <th class="border px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($selectedInvoice->items as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $item->description }}</td>
                        <td class="border px-4 py-2">{{ $item->quantity }}</td>
                        <td class="border px-4 py-2">{{ number_format($item->amount, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($item->vat_amount, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($item->wht_amount, 2) }}</td>
                        <td class="border px-4 py-2">{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button wire:click="closeModal" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">
                Tutup
            </button>
        </div>
    </div>
    @endif
</div>