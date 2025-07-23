<div x-data x-init="reinitSelect2">
    <form action="POST" wire:submit="savePayment" class="p-2">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div class="flex flex-col">
                <label for="date">Date</label>
                <div>
                    <input type="date" wire:model="payment_date" class="w-full rounded-md border-gray-300 cursor-pointer">
                </div>
                @error('payment_date')
                <p class="bg-yellow-200 text-red-400 p-2 rounded-md mt-2"> {{$message}}
                </p>
                @enderror
            </div>

            <div class="flex flex-col">
                <label for="paymentNo">Payment No</label>
                <input type="text" wire:model="payment_no" class="w-full rounded-md border-gray-300">
            </div>

            <div class="flex flex-col" wire:ignore>
                <label for="selectedCustVendor">customer_id / vendor_id </label>
                <select name="selectedCustVendor" id="selectedCustVendor" wire:model.live="selectedCustVendor" class="border-gray-300 w-full rounded-md">
                    <option value=""></option>
                    @foreach($customers as $cust)
                    <option value="{{ $cust->id }}">
                        {{ $cust->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col" wire:ignore>
                <label for="bank_coa">Bank Account</label>
                <select name="bank_coa" id="bank_coa" wire:model="bank_coa" class="w-full rounded-md border-gray-300 ">
                    <option value=""></option>
                    @foreach($coa as $c)
                    <option value="{{$c->id}}">{{$c->account_code}} - {{$c->account_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label for="currency">Currency</label>
                <input type="text" wire:model="currency" class="w-full rounded-md border-gray-300">
            </div>
            <div class="flex flex-col">
                <label for="exchange_rate">Exchange Rate</label>
                <input type="text" wire:model="exchange_rate" class="w-full rounded-md border-gray-300">
            </div>
            <div class="flex flex-col">
                <label for="amount">Amount</label>
                <input type="text" wire:model="amount" class="w-full rounded-md border-gray-300">
            </div>
            <div class="flex flex-col">
                <label for="remarks">Remarks</label>
                <input type="text" class="w-full rounded-md border-gray-300">
            </div>
            <div class="flex flex-col">
                <label for="status">status</label>
                <select name="status" id="status" class="border-gray-300 w-full rounded-md">
                    <option value=""></option>
                </select>
            </div>
            <div class="flex justify-end col-span-2">
                <button class="py-2 px-3 bg-blue-200 rounded-md hover:bg-blue-400 hover:text-gray-300 hover:scale-105 transform">Save</button>
            </div>
            <div class="col-span-2">
                <table class="w-full">
                    <thead class="py-1 px-2">
                        <tr>
                            <th>No</th>
                            <th></th>
                            <th>Invoice</th>
                            <th>Customer / Vendor</th>
                            <th>Total Invoice</th>
                            <th>Total Payment</th>
                            <th>Amount Allocation</th>
                            <th>Sisa hutang</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoiceForeach as $invFrch)

                        <tr class="border border-gray-500">
                            <td class="px-1 py-1 text-center border">{{$loop->iteration}}</td>
                            <td class="px-1 py-1 text-center border">
                                <input type="checkbox"
                                    wire:click="selectedInvoice({{$invFrch->id}})"
                                    class="border-blue-400 rounded-md cursor-pointer">
                            </td>
                            <td class="px-1 py-1 text-center border">{{$invFrch->invoice_number}} - {{$invFrch->job->job_id ?? ''}} {{$invFrch->shipment->shipment_id ?? ''}}</td>
                            <td class="px-1 py-1 text-center border">{{$invFrch->client->name ?? ''}}</td>
                            <td class="px-1 py-1 text-center border">Rp. {{ number_format($invFrch->total_amount, 2, '.', ',') }}
                            </td>
                            <td class="px-1 py-1 text-center border">Rp.{{number_format($invFrch->paid ?? '',2,'.',',')}}</td>
                            <td class="px-1 py-1 border">
                                <div class="relative w-full">
                                    <input type="text"
                                        wire:model="allocations.{{ $invFrch->id }}"
                                        class="pl-8 w-full rounded-md border-gray-400 text-sm"
                                        step="0.01"
                                        min="0">
                                </div>
                            </td>
                            <td class="px-1 py-1 text-center border">Rp.{{number_format($invFrch->kurang ?? '',2,'.',',')}}</td>
                            <td class="px-1 py-1 text-center border"> @if($invFrch->status_text === 'Lunas')
                                <span class="text-green-600 font-semibold">Lunas</span>
                                @else
                                <span class="text-yellow-600 font-semibold">Belum Lunas</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
                @if($selectedInvoiceId)
                {{ implode(', ', $selectedInvoiceId) }}
                @else
                @endif
            </div>

        </div>

    </form>
</div>
@push('script')
@script()
<script>
    window.reinitSelect2 = () => {
        [{
            sel: '#selectedCustVendor',
            model: 'selectedCustVendor',
            placeholder: 'Select Client '
        }, {
            sel: '#bank_coa',
            model: 'bank_coa',
            placeholder: 'select account',
        }].forEach(({
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
                // console.log(model, value);
            });
        });
    };
</script>
@endscript
@endpush