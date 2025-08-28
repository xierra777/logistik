@section('title', 'Bank Details')
<div class="p-4">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Details Bank') }}
        </h2>
    </x-slot>

    <div class="bg-stone-350 ">
        <div class="font-bold border border-gray-300 p-2 bg-gray-100">
            Bank Details
        </div>
        <table class="w-full border border-gray-300 border-collapse">
            <tbody class="divide-y divide-gray-300">
                <tr class="bg-gray-50">
                    <td class="border px-4 py-2 font-semibold w-1/4">Name</td>
                    <td class="border px-4 py-2 w-1/4">{{ $bank->bank_name }}</td>
                    <td class="border px-4 py-2 font-semibold w-1/4">Customer </td>
                    <td class="border px-4 py-2 w-1/4">{{$bank->Customer->name ?? '' }}</td>

                </tr>
            </tbody>
        </table>



        <div class="mt-3" x-data="{ openCreateContainer: false }" x-init="reinitSelect2">
            <div class="border border-1 border-gray-300 rounded-md mb-2">
                <div class="flex justify-between border border-1 p-2 border-cyan-300 bg-cyan-400 rounded-t-md">
                    <h2 class="text-lg font-semibold border-collapse ">Account</h2>
                    <button @click="openCreateContainer = true;$wire.openCreateForm()"
                        class="py-1 px-2 bg-green-600 text-white rounded-lg">
                        Add Account
                    </button>
                </div>
                <div @close-create-container.window="openCreateContainer = false">
                    <div x-cloak x-show="openCreateContainer"
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:enter-start=" opacity-0" x-transition:enter-end="scale-100 opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="transition ease-in duration-100 scale-100 opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-gray-500 bg-opacity-50 pointer-events-none z-40">
                    </div>
                    <div x-cloak x-show="openCreateContainer" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="scale-90 opacity-0" x-transition:enter-end="scale-100 opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="scale-100 opacity-100" x-transition:leave-end="scale-90 opacity-0"
                        class="fixed inset-0 flex items-center justify-center z-50">
                        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-5xl">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-xl font-semibold">Create Address</h2>
                                <button @click="openCreateContainer = false" class="text-gray-500 hover:text-gray-700">
                                    &times;
                                </button>
                            </div>
                            <form wire:submit="createViewBank" class="space-y-4">
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
                                        @input="$event.target.value = $event.target.value.toUpperCase()" required>
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
                                    <button type="button" @click="openCreateContainer = false"
                                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <table class="w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">No</th>
                            <th class="border border-gray-300 px-4 py-2">Bank Code</th>
                            <th class="border border-gray-300 px-4 py-2">Swift Code</th>
                            <th class="border border-gray-300 px-4 py-2">Account No</th>
                            <th class="border border-gray-300 px-4 py-2">Currency</th>
                            <th class="border border-gray-300 px-4 py-2">Branch Name</th>
                            <th class="border border-gray-300 px-4 py-2">Chart of Account</th>
                            <th class="border border-gray-300 px-4 py-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bank->accounts as $b)
                        <tr class="text-center bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $b->bank_code }}</td>
                            <td class="border border-gray-300 px-4 py-2 ">{{ $b->swift_code }}</td>
                            <td class="border border-gray-300 px-4 py-2 ">{{ $b->bank_account_number }}</td>
                            <td class="border border-gray-300 px-4 py-2 ">{{ $b->currency }}</td>
                            <td class="border border-gray-300 px-4 py-2 ">{{ $b->branch_name }}</td>
                            <td class="border border-gray-300 px-4 py-2 ">{{ $b->chartOfAccount->account_code ?? ''}}
                            <td class="border border-gray-300 px-4 py-2 ">
                                <button type="button" @click="$dispatch('confirm-delete', { get_id: {{ $b->id }} })"
                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Delete</button>
                            </td>
                        </tr>
                        @empty
                        <tr class="text-center bg-gray-50">
                            <td colspan="8" class="border border-gray-300 px-4 py-2">No accounts found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <x-confirm-delete />
            </div>
        </div>

        <hr class="border border-gray-300 m-5">
        <div class="mt-5 flex  justify-end m-2">
            <a href="{{ route('listBank') }}" class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg 
               transform transition duration-200 ease-in-out shadow:hover-cyan-200
               hover:bg-cyan-400 hover:scale-110  ">
                Back
            </a>
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