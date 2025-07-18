<div x-data class=" p-4" x-init="reinitSelect2">
    <h1 class="text-2xl font-bold ">Journal Entries Summary</h1>

    <!-- Liar -->
    <div class="mb-2 mt-2" wire:ignore>
        <select name="sortJournal" id="sortJournal" wire:model.live="sortJournalEntries">
            <option value="all">All</option>
            <option value="true">Reverse</option>
            <option value="false">Sales</option>
        </select>
    </div>
    <!-- {{$sortJournalEntries}} -->
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
                <td class="border px-4 py-2 text-right {{ $totalDebit != $totalCredit ? 'bg-red-200 text-red-700' : '' }}">
                    {{ number_format($totalDebit, 2, ',', '.') }}
                </td>
                <td class="border px-4 py-2 text-right {{ $totalDebit != $totalCredit ? 'bg-red-200 text-red-700' : '' }}">
                    {{ number_format($totalCredit, 2, ',', '.') }}
                </td>
                @if($totalDebit != $totalCredit)
                <td class="border px-4 py-2 text-red-700 font-semibold">Tidak Imbang</td>
                @else
                <td class="border px-4 py-2"></td>
                @endif
            </tr>
        </tbody>
    </table>

    <div class="mt-4 p-2 flex justify-end">
        <a href="{{ route('accountant.list') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:scale-105 transition-transform hover:shadow-lg hover:shadow-blue-500 hover:shadow-lg">Back</a>
    </div>
</div>
@push('script')
<script>
    window.reinitSelect2 = () => {
        [{
            sel: '#vendorPurchasingInvoicing',
            model: 'selectedVendor',
            placeholder: 'Select Vendor  '
        }, ].forEach(({
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
</script>
@endpush