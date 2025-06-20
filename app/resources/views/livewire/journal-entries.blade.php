<div class=" p-4">
    <h1 class="text-2xl font-bold mb-4">Journal Entries Summary</h1>

    <table class="w-full table-auto border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">Date</th>
                <th class="border px-4 py-2 text-left">Description</th>
                <th class="border px-4 py-2 text-left">Account</th>
                <th class="border px-4 py-2 text-right">Debit (IDR)</th>
                <th class="border px-4 py-2 text-right">Credit (IDR)</th>
                <th class="border px-4 py-2 text-right">Created At</th>
                <th class="border px-4 py-2 text-right">Created By</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($journalEntries as $entry)
            <tr>
                <td class="border whitespace-nowrap px-4 py-2">{{ $entry->created_at->format('Y-m-d') }}</td>
                <td class="border px-4 py-2">{{ $entry->description }}</td>
                <td class="border px-4 py-2">{{ $entry->chartOfAccount->account_name ?? '-' }}</td>
                <td class="border px-4 py-2 text-right">{{ number_format($entry->debit, 2, ',', '.') }}</td>
                <td class="border px-4 py-2 text-right">{{ number_format($entry->credit, 2, ',', '.') }}</td>
                <td class="border px-4 py-2 text-right">{{ $entry->created_at->format('Y-m-d H:i:s') }}</td>
                <td class="border px-4 py-2 text-right">{{ $entry->user->name ?? '-' }}</td>
            </tr>
            @endforeach
            <tr class="font-bold bg-gray-200">
                <td class="border px-4 py-2 text-right" colspan="3">Total</td>
                <td class="border px-4 py-2 text-right">{{ number_format($totalDebit, 2, ',', '.') }}</td>
                <td class="border px-4 py-2 text-right">{{ number_format($totalCredit, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="mt-4 p-2 flex justify-end">
        <a href="{{ route('accountant.list') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:scale-105 transition-transform hover:shadow-lg hover:shadow-blue-500 hover:shadow-lg">Back</a>
    </div>
</div>