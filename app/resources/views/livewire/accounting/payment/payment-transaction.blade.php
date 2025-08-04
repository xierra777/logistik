<div class="p-2">
    <div class="flex justify-end mb-4 gap-4">
        <a href="{{route('createPay')}}" class="px-4 py-2 bg-red-200 rounded-md hover:scale-105 transition-transform duration-400 ease-in-out hover:bg-red-400 hover:text-white">Make a Payment!</a>
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
                {{$pym->allocations}}
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="text-center border border-gray-300 px-2 py-1 text-gray-700">{{ $loop->iteration }}</td>
                    <td class="text-center border border-gray-300 px-2 py-1 text-gray-700">{{ $pym->payment_no }}</td>
                    <td class="text-center border border-gray-300 px-2 py-1 text-gray-700">{{ $pym->date }}</td>
                    <td class="text-center border border-gray-300 px-2 py-1 text-gray-700">{{ $pym->customer->name ?? '-' }}</td>
                    <td class="text-center border border-gray-300 px-2 py-1 text-gray-700">
                        {{$pym->currency}} {{ number_format($pym->amount, 2, ',', '.') }}
                    </td>
                    <td class="text-center border border-gray-300 px-2 py-1 text-gray-700">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold 
                    {{ $pym->status === 'paid' ? 'bg-green-200 text-green-700' : 'bg-yellow-200 text-yellow-700' }}">
                            {{ $pym->status }}
                        </span>
                    </td>
                    <td class="text-center border border-gray-300 px-2 py-1">
                        <a href="{{route('viewPay',['payId'=>$pym->id])}}"
                            class="py-1 px-3 bg-blue-500 text-white text-sm rounded-full hover:bg-blue-600 hover:scale-105 transition-transform">
                            View
                        </a>
                    </td>
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
            sel: '#selectedCustVendor',
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