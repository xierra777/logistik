<div class="p-2">
    <div class="flex justify-end mb-4 gap-4">
        <button class="px-4 py-2 bg-red-200 rounded-md hover:scale-105 transition-transform duration-400 ease-in-out hover:bg-red-400 hover:text-white">Make a Payment!</button>
        <div x-data="{ openPayment : false}" @close-modal.window="openPayment = false" @keydown.escape.window="openPayment = false" @click.away="openPayment = false">
            <button @click="openPayment = true" x-init="initContainerSelect2" class="px-4 py-2 bg-blue-200 rounded-md hover:scale-105 transition-transform duration-400 ease-in-out hover:bg-blue-400 hover:text-white">Make a Payment!</button>

            <div x-cloak x-show="openPayment"
                x-transition:enter="transition ease-out duration-300 delay-150"
                x-transition:leave="transition ease-in duration-200"
                class="fixed inset-0 bg-gray-500 bg-opacity-50 z-40">
            </div>
            <div x-cloak x-show="openPayment"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="scale-90 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-90 opacity-0"
                class="fixed inset-0 flex items-center justify-center z-50 px-4">
                <div class="bg-white rounded-lg shadow-md w-full max-w-4xl">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center p-4 border-b">
                        <h1 class="text-2xl font-bold">Payment</h1>

                        <button @click="openPayment = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                    </div>
                    <form action="POST" wire:submit="savePayment" class="p-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div class="flex flex-col">
                                <label for="date">Date</label>
                                <input type="date" wire:model="payment_date" class="w-full rounded-md border-gray-300 cursor-pointer">
                            </div>
                            <div class="flex flex-col">
                                <label for="paymentNo">Payment No</label>
                                <input type="text" wire:model="payment_no" class="w-full rounded-md border-gray-300">
                            </div>

                            <div class="flex flex-col" wire:ignore>
                                <label for="customerVendor_id">customer_id / vendor_id </label>
                                <select name="customerVendor_id" id="customerVendor_id" multiple wire:model.live="selectedInvoice" class="border-gray-300 w-full rounded-md">
                                    <option value=""></option>
                                    @foreach($invoices as $invoice)
                                    <option value="{{ $invoice->id }}">
                                        {{ $invoice->invoice_number }} -
                                        @if($invoice->job)
                                        Job: {{ $invoice->job->job_id }}
                                        @elseif($invoice->shipment)
                                        Shipment: {{ $invoice->shipment->shipment_id }}
                                        @endif
                                        {{$invoice->client->name}}
                                        - Rp. {{ number_format($invoice->total_amount, 2, '.', ',') }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col">
                                <label for="bank_account">Bank Account</label>
                                <select name="" id="" wire:model="bank_coa" class="w-full rounded-md border-gray-300 ">
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
                                            <th>Invoice</th>
                                            <th>Customer / Vendor</th>
                                            <th>Amount Allocation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($invoiceForeach as $invFrch)
                                        <tr>
                                            <td class="px-1 py-1">{{$loop->iteration}}</td>
                                            <td class="px-1 py-1">{{$invFrch->invoice_number}}</td>
                                            <td class="px-1 py-1">{{$invFrch->client->name ?? ''}}</td>
                                            <td class="px-1 py-1">
                                                <input type="text"
                                                    wire:model="allocations.{{ $invFrch->id }}"
                                                    class="form-input w-full"
                                                    placeholder="0.00"
                                                    step="0.01"
                                                    min="0">
                                            </td>
                                        </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class=" overflow-x-auto">
        <table class="w-full min-w-max border-collapse">
            <thead>
                <tr>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">No. </th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">Payment No</th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">Payment Date</th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">Customer / Vendor</th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">Amount</th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">Status</th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">Action</th>


                </tr>
            </thead>
            <tbody>
                @foreach($payment as $pym)
                <tr>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">{{$loop->iteration}}</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">{{$pym->payment_no}}</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">{{$pym->payment_date}}</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">{{$pym->customer->name ?? ''}}</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">{{number_format($pym->amount,2,',','.')}}</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">{{$pym->status}}</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap"><button class="py-2 px-3 bg-blue-200 rounded-full hover:text-gray-200 hover:bg-blue-400 hover:scale-105 transform">Add</button></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@script()
<script>
    window.initContainerSelect2 = () => {
        // Configuration for all select elements
        const selectConfigs = [{
            sel: '#customerVendor_id',
            model: 'selectedCustVendor',
            placeholder: 'Select Customer'
        }, ];

        selectConfigs.forEach(({
            sel,
            model,
            placeholder
        }) => {
            const $el = $(sel);
            if (!$el.length) return;

            // Destroy existing Select2 if it exists
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }

            // Initialize Select2 with modal-friendly settings
            $el.select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%',
                theme: 'tailwindcss-3',
                dropdownParent: $el.closest('.fixed'),
                dropdownAutoWidth: false,
                escapeMarkup: function(markup) {
                    return markup;
                },
                // Prevent Select2 from focusing on search input
                selectOnClose: false,
                // Prevent dropdown from closing modal
                closeOnSelect: true
            });

            // IMPORTANT: Remove all previous event handlers to prevent duplicates
            $el.off('select2:select.container select2:unselect.container select2:open.container select2:close.container');

            // Handle Select2 events with debouncing to prevent multiple triggers
            let updateTimeout;
            $el.on('select2:select.container select2:unselect.container', function(e) {
                e.stopPropagation(); // Prevent event bubbling

                clearTimeout(updateTimeout);
                updateTimeout = setTimeout(() => {
                    const value = $(this).val();
                    if (typeof $wire !== 'undefined' && $wire[model] !== undefined) {
                        // Use Livewire's set method without triggering full refresh
                        $wire.set(model, value || []); // false = don't trigger refresh
                    }
                    console.log(`${model} changed to:`, value);
                }, 100);
            });

            // Prevent modal from closing when dropdown opens
            $el.on('select2:open.container', function(e) {
                e.stopPropagation();
                // Ensure dropdown is positioned correctly
                const dropdown = $('.select2-dropdown');
                // dropdown.css('z-index', '9999');
            });

            // Handle dropdown close
            $el.on('select2:close.container', function(e) {
                e.stopPropagation();
            });

            // Sync with Livewire property if it exists (without triggering events)
            if (typeof $wire !== 'undefined' && $wire[model] !== undefined) {
                $el.val($wire[model]).trigger('change.select2');
            }
        });
    };
</script>
@endscript