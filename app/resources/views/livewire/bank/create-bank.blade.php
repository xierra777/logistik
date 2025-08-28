<div x-data x-init="reinitSelect2">
        <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Tambah Banks') }}
                </h2>
        </x-slot>
        <div>
                <div class="p-6">
                        <form wire:submit="createBank" class="space-y-4">
                                <div>
                                        <label for="bank_code" class="block text-sm font-medium text-gray-700">Bank
                                                Code.</label>
                                        <input type="text" id="bank_code" wire:model="bank_code"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                required>
                                        @error('bank_account_number') <span class="text-red-600">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div>
                                        <label for="bank_name" class="block text-sm font-medium text-gray-700">
                                                Bank Name</label>
                                        <input type="text" id="bank_name" wire:model="bank_name"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                required>
                                        @error('bank_name') <span class="text-red-600">{{ $message }}</span> @enderror
                                </div>

                                <div wire:ignore>
                                        <label for="customer_id"
                                                class="block text-sm font-medium text-gray-700">Customers</label>
                                        <select name="customer_id" id="customer_id"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value=""></option>
                                                @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                                @endforeach
                                        </select>
                                </div>
                                <div>
                                        <label for="branch_name" class="block text-sm font-medium text-gray-700">Branch
                                                Name</label>
                                        <input type="text" id="branch_name" wire:model="branch_name"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                required>
                                        @error('bank_account_number') <span class="text-red-600">{{ $message }}</span>
                                        @enderror
                                </div>

                                <div>
                                        <label for="bank_account_number"
                                                class="block text-sm font-medium text-gray-700">Account No.</label>
                                        <input type="text" id="bank_account_number" wire:model="bank_account_number"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                required>
                                        @error('bank_account_number') <span class="text-red-600">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div>
                                        <label for="swift_code" class="block text-sm font-medium text-gray-700">Swift
                                                Code</label>
                                        <input type="text" id="swift_code" wire:model="swift_code"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @error('bank_account_number') <span class="text-red-600">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div>
                                        <label for="currency"
                                                class="block text-sm font-medium text-gray-700">Currency</label>
                                        <input type="text" id="currency" wire:model="currency"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                @input="$event.target.value = $event.target.value.toUpperCase()"
                                                required>
                                        <label class="text-xs text-gray-500">Example (IDR, USD, EUR)</label>
                                        @error('currency')
                                        <span class="text-red-600">{{ $message }}</span>
                                        @enderror
                                </div>
                                <div wire:ignore>
                                        <label for="coa_id" class="block text-sm font-medium text-gray-700">Chart
                                                Account</label>
                                        <select name="coa_id" id="coa_id"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value=""></option>
                                                @foreach($chartAccounts as $chart)
                                                <option value="{{ $chart->id }}">{{ $chart->account_name }}</option>
                                                @endforeach
                                        </select>
                                </div>
                                <div class="flex justify-between items-center">
                                        <a href="{{route('listBank')}}"
                                                class="px-4 py-2 bg-yellow-600 border border-transparent inline-block rounded-md font-semibold text-xs text-white transform hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150 hover:scale-105">
                                                Back </a>
                                        <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                Simpan
                                        </button>
                                </div>

                        </form>
                </div>
        </div>
</div>
@push('script')
@script()
<script>
        window.reinitSelect2 = () => {
        [{
            sel: '#customer_id',
            model: 'customer_id',
            placeholder: 'select account',
        },{
        sel: '#coa_id',
        model: 'coa_id',
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