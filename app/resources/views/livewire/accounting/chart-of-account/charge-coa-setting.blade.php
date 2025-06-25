<div class="p-4">
    <div class="p-3 justify-end flex">
        <div x-data="{ open: false }" @keydown.escape.window="open = false" x-init="
        window.addEventListener('closeModal', () => { open = false });
        $watch('open', value => {
            if (value) {
                $nextTick(() => {
                    window.reinitSelect2();
                });
            }
        });
     " @window.close-modal="open = false">
            <!-- Button to open modal -->
            <div class="flex justify-end gap-2 mb-4 justify-end">
                <button @click="open = true" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 hover:scale-105 rounded-md hover:text-white  transform transition duration-300 ease-in-out hover:shadow-cyan-200">Add Charge For COA</button>
            </div>
            <!-- Background Overlay with Transparent Gray -->
            <div x-cloak x-show="open"
                x-transition:enter="transition ease-out duration-300 delay-150"
                x-transition:leave="transition ease-in duration-200"
                class="fixed inset-0 bg-gray-500 bg-opacity-50 pointer-events-none z-40">
            </div>

            <!-- Modal Container -->
            <div x-cloak x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="scale-90 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-90 opacity-0"
                class="fixed inset-0 flex items-center justify-center z-50 pointer-events-auto">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                    <h3 class="text-xl font-bold mb-4">Add Employee</h3>

                    <form wire:submit.prevent="save">
                        <div class="mb-4">
                            <label for="charge_code" class="block text-sm font-medium text-gray-700 dark:text-white">Charge Code</label>
                            <input type="text" id="charge_code" wire:model="charge_code" placeholder="Enter Charge Code" class="w-full rounded-md border border-gray-300">
                            @error('charge_code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="charge_name" class="block text-sm font-medium text-gray-700 dark:text-white">Charge Name</label>
                            <input type="text" id="charge_name" wire:model="charge_name" placeholder="Enter Charge Name" class="w-full rounded-md border border-gray-300">
                            @error('charge_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="coa_sale_id" class="block text-sm font-medium text-gray-700 dark:text-white">Coa Sale ID</label>
                            <select name="coa_sale_id" id="coa_sale_id" wire:model="coa_sale_id">
                                <option value=""></option>
                                @foreach($revenueAccounts as $csi)
                                <option value="{{$csi->id}}">{{$csi->account_code}} - {{ $csi->account_name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="coa_cost_id" class="block text-sm font-medium text-gray-700 dark:text-white">Coa Cost ID</label>
                            <select name="coa_cost_id" id="coa_cost_id" wire:model="coa_cost_id">
                                <option value=""></option>
                                @foreach($expenseAccounts as $cci)
                                <option value="{{$cci->id}}">{{$cci->account_code}} - {{ $cci->account_name }}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="flex justify-end gap-2 pt-5">
                            <button type="button" @click="close-modal" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-auto max-w-full p-3 border border-gray-300 rounded-t-lg">
        <table class="min-w-max w-full table-auto divide-y divide-gray-200 dark:divide-neutral-700">
            <thead class="bg-gray-50 dark:bg-neutral-800">
                <tr>
                    @foreach (['No', 'Charge Code', 'Charge Name','COA SALE ID','COA COST ID','Created By' ] as $th)
                    <th scope="col" class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase text-center dark:text-neutral-400 text-left">
                        {{ $th }}
                    </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-700">
                <tr class="hover:bg-gray-50 dark:hover:bg-neutral-800 transition-colors ">
                    @forelse($accounts as $acc)
                    <td class="px-4 py-4 text-sm font-medium text-gray-800 text-center dark:text-neutral-200">
                        {{$loop->iteration}}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-800 text-center dark:text-neutral-300">
                        {{$acc->charge_code}}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-800 text-center dark:text-neutral-300 ">
                        {{$acc->charge_name}}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-800 text-center dark:text-neutral-300 font-bold">
                        {{$acc->coaSale->account_code}} - {{$acc->coaSale->account_name}}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-800 text-center dark:text-neutral-300 font-bold">
                        {{$acc->coaCost->account_code}} - {{$acc->coaCost->account_name}}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-800 text-center dark:text-neutral-300 font-bold">
                        {{$acc->user->name ?? ''}}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <img src="{{ asset('images/nodata.svg') }}"
                                alt="No data illustration"
                                class="w-64 h-48 mb-4 opacity-75 dark:opacity-50">
                            <p class="text-lg font-medium text-gray-600 dark:text-neutral-300">
                                No shipments found!
                            </p>
                            <p class="text-sm text-gray-500 dark:text-neutral-500">
                                Start by adding shipments or importing data.
                            </p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <x-confirm-delete />
    </div>
    <div class="flex justify-end p-3">
        <a href="{{route('chartOfAccount')}}" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 hover:scale-105 rounded-md hover:text-white  transform transition duration-300 ease-in-out hover:shadow-cyan-200"> Back</a>
    </div>
</div>
@push('scripts')
@script()
<script>
    window.reinitSelect2 = () => {
        [{
                sel: '#coa_sale_id',
                model: 'coa_sale_id',
                placeholder: 'Select COA '
            },

            {
                sel: '#coa_cost_id',
                model: 'coa_cost_id',
                placeholder: 'Select COA '
            },
        ].forEach(({
            sel,
            model,
            placeholder
        }) => {
            const $el = $(sel);
            if (!$el.length) return;

            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }

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
                console.log(value);
            });
        });
    };





    document.addEventListener('livewire:init', () => {
        window.reinitSelect2();
        window.PortSelect2.init();
        Livewire.hook('message.processed', () => {
            window.reinitSelect2();
        });
    });
</script>

@endscript
@endpush