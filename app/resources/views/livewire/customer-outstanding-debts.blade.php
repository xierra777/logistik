@php
$grandTotalInvoice = collect($customerDebts)->sum('total_invoice');
$grandTotalPaid = collect($customerDebts)->sum('total_paid');
$grandTotalOutstanding = collect($customerDebts)->sum('outstanding');
@endphp
<div class="p-2">
    <x-slot name="header">
        <p class="font-bold">
            {{{__('Customer Debt Total')}}}
        </p>
    </x-slot>
    <div class="py-2">
        <a href="{{ route('accountant.list') }}" class="py-2 px-3 bg-blue-500 rounded-md hover:bg-blue-700 hover:text-white hover:scale-105 transition-transform">Back</a>
    </div>
    <table class="table-auto w-full">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Total Invoice</th>
                <th>Total Paid</th>
                <th>Outstanding</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customerDebts as $debt)
            <tr>
                <td class="border border-gray-200 px-3 py-1">{{ $debt['customer_name'] }}</td>
                <td class="border border-gray-200 px-3 py-1 text-center">{{ number_format($debt['total_invoice'], 2, ',', '.') }}</td>
                <td class="border border-gray-200 px-3 py-1 text-center">{{ number_format($debt['total_paid'], 2, ',', '.') }}</td>
                <td class="border border-gray-200 px-3 py-1 text-center text-red-600">{{ number_format($debt['outstanding'], 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="bg-gray-50 border-t font-semibold">
            <tr>
                <td class="px-4 py-2 text-center">TOTAL</td>
                <td class="px-4 py-2  text-center">{{ number_format($grandTotalInvoice, 2, ',', '.') }}</td>
                <td class="px-4 py-2 text-center">{{ number_format($grandTotalPaid, 2, ',', '.') }}</td>
                <td class="px-4 py-2 text-center text-red-700">{{ number_format($grandTotalOutstanding, 2, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
</div>