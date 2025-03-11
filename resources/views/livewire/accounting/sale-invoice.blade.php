<div class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-bold mb-4">Sale Invoice</h2>

    <!-- Invoice Header -->
    <div class="border p-4 rounded-lg mb-6">
        <table class="w-full text-sm border-collapse">
            <tr>
                <td class="font-bold">Invoice No:</td>
                <td>
                    <input type="text" wire:model="invoice_number" class="w-full px-3 py-2 border rounded-lg" placeholder="Invoice Number">
                </td>
                <td class="font-bold">Client:</td>
                <td>
                    <!-- Dropdown untuk memilih client (data diambil dari $clients) -->
                    <select wire:model.live="customer_id" class="w-full px-3 py-2 border rounded-lg">
                        <option value="">Select Client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td class="font-bold">MBL:</td>
                <td>{{ $shipmentId }}</td>
                <td class="font-bold">Currency:</td>
                <td>{{ $currency }}</td>
            </tr>
        </table>
    </div>

    <!-- Transaction Summary -->
    @if($shipmentId && $customer_id && $transactions->isNotEmpty())
    <h3 class="text-lg font-bold mb-2">Transaction Details</h3>
    <table class="w-full border text-sm mb-6">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2 border">Charge</th>
                <th class="p-2 border">Qty</th>
                <th class="p-2 border">Currency</th>
                <th class="p-2 border">harganya</th>
                <th class="p-2 border">VAT</th>
                <th class="p-2 border">WHT</th>
                <th class="p-2 border">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td class="p-2 border">{{ $transaction->description }}</td>
                <td class="p-2 border">{{ $transaction->quantity}}</td>
                <td class="p-2 border">{{ $transaction->scurrency }}</td>
                <td class="p-2 border">{{ $transaction->samountidr }}</td>
                <td class="p-2 border">{{ $transaction->svatgstamount }}</td>
                <td class="p-2 border">{{$transaction->swhtaxrate }}</td>
                <td class="font-bold p-2 border">
                    {{ $transaction->samountidr }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mb-4">
        <strong>Total Amount: </strong> {{$total_amount}}
    </div>
    @endif

    <!-- Submit Button -->
    <div class="mt-4">
        <button wire:click="save" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            Save Invoice
        </button>
    </div>

    @if(session()->has('message'))
    <div class="mt-4 p-2 bg-green-200 text-green-800 rounded-lg">
        {{ session('message') }}
    </div>
    @endif
</div>