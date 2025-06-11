<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Journal Entries Summary</h1>

    <table class="w-full table-auto border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-4 py-2 text-left">Date</th>
                <th class="border px-4 py-2 text-left">Description</th>
                <th class="border px-4 py-2 text-left">Account</th>
                <th class="border px-4 py-2 text-right">Debit (IDR)</th>
                <th class="border px-4 py-2 text-right">Credit (IDR)</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($journalEntries as $entry)

            <tr>
                <td class="border px-4 py-2">{{ $entry->created_at->format('Y-m-d') }}</td>
                <td class="border px-4 py-2">{{ $entry->description }}</td>
                <td class="border px-4 py-2">{{ $entry->chartOfAccount->account_name ?? '-' }}</td>
                <td class="border px-4 py-2 text-right">{{ $entry->debit }}</td>
                <td class="border px-4 py-2 text-right">{{$entry->credit,}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>