<div class="p-4">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Payment') }}
        </h2>
    </x-slot>
    <div class="overflow-x-auto rounded-md">
        <table class="min-w-full divide-y divide-gray-200 border border-gray-300 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">No</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Invoice Number</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Payment No</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Total Invoice</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Total Payment</th>
                    <th class="px-4 py-2 text-left font-semibold text-gray-700">Sisa Hutang</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($payment->allocations as $allocation)
                <tr>
                    <td class="px-4 py-2 text-gray-800">{{ $loop->iteration }}</td>
                    <td class="px-4 py-2 text-gray-800">
                        {{ $allocation->invoices->invoice_number ?? '-' }}
                    </td>
                    <td class="px-4 py-2 text-gray-800">
                        {{ $payment->payment_no }}
                    </td>
                    <td class="px-4 py-2 text-gray-800">
                        {{ number_format($allocation->invoices->total_amount ?? '' ,2,'.',',') }}
                    </td>
                    <td class="px-4 py-2 text-gray-800">
                        {{ number_format($allocation->amount_allocated ?? '' ,2,'.',',') }}
                    </td>
                    <td class="px-4 py-2">
                        @if($allocation->kurang <= 0)
                            <span class="text-green-600 font-semibold">LUNAS</span>
                            @else
                            <span class="text-red-600">
                                {{ number_format($allocation->kurang, 2, '.', ',') }}
                            </span>
                            @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="flex justify-end mt-2">
        <a href="{{route('paymentTrans')}}" class="px-2 py-1 hover:scale-105 hover:text-white transform rounded-md bg-blue-200 hover:bg-blue-400">Back</a>
    </div>
</div>