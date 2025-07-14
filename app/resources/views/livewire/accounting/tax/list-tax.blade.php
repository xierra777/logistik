<div class="p-4">
    <div class="p-3 justify-end flex">
        <div x-data="{ open: false }" @window.close-modal="open = false" @keydown.escape.window="open = false" x-init="
        window.addEventListener('closeModal', () => { open = false });
        $watch('open', value => {
            if (value) {
                $nextTick(() => {
                    window.reinitSelect2();
                });
            }
        });
     ">
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
                    <h3 class="text-xl font-bold mb-4">Add Tax</h3>

                    <form wire:submit.prevent="save">
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-white">Name</label>
                            <input type="text" id="name" wire:model="name" placeholder="Enter name" class="w-full rounded-md border border-gray-300">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-white">Name</label>
                            <input type="text" id="description" wire:model="description" placeholder="Enter description" class="w-full rounded-md border border-gray-300">
                            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-white">Type</label>
                            <select name="type" id="type" wire:model="type">
                                <option value=""></option>
                                <option value="vat">VAT</option>
                                <option value="wht">WHT</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="rate" class="block text-sm font-medium text-gray-700 dark:text-white">Rate</label>
                            <input type="text" id="rate" wire:model="rate" placeholder="Enter rate" class="w-full rounded-md border border-gray-300">
                            @error('rate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="context" class="block text-sm font-medium text-gray-700 dark:text-white">Context</label>
                            <select name="context" id="context" wire:model="context">
                                <option value=""></option>
                                <option value="sales">Sales</option>
                                <option value="cost">Cost</option>
                            </select>

                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="coa_id" class="block text-sm font-medium text-gray-700 dark:text-white">Coa ID</label>
                            <select name="coa_id" id="coa_id" wire:model="coa_id">
                                @foreach($taxAccount as $tax)
                                <option value="{{$tax->id}}">{{$tax->account_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-white">Coa ID</label>
                            <div class="flex items-center">
                                <label for="hs-small-switch-with-icons" class="relative inline-block w-11 h-6 cursor-pointer">
                                    <input type="checkbox" wire:model="is_active" id="hs-small-switch-with-icons" class="peer sr-only">
                                    <span class="absolute inset-0 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-blue-600 dark:bg-neutral-700 dark:peer-checked:bg-blue-500 peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                                    <span class="absolute top-1/2 start-0.5 -translate-y-1/2 size-5 bg-white rounded-full shadow-xs transition-transform duration-200 ease-in-out peer-checked:translate-x-full dark:bg-neutral-400 dark:peer-checked:bg-white"></span>
                                    <!-- Left Icon (Off) -->
                                    <span class="absolute top-1/2 start-0.5 -translate-y-1/2 flex justify-center items-center size-5 text-gray-500 peer-checked:text-white transition-colors duration-200 dark:text-neutral-500">
                                        <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 6 6 18"></path>
                                            <path d="m6 6 12 12"></path>
                                        </svg>
                                    </span>
                                    <!-- Right Icon (On) -->
                                    <span class="absolute top-1/2 end-0.5 -translate-y-1/2 flex justify-center items-center size-5 text-gray-500 peer-checked:text-blue-600 transition-colors duration-200 dark:text-neutral-500">
                                        <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2 pt-5">
                            <button type="button" @click="open = false" class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
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
                    @foreach (['No', 'Name','type', 'Context','Rate','Account name','is Active','created_by' ] as $th)
                    <th scope="col" class="px-4 py-3 text-xs font-semibold text-gray-500 uppercase text-center dark:text-neutral-400 text-left">
                        {{ $th }}
                    </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="bg-white dark:bg-neutral-900 divide-y divide-gray-200 dark:divide-neutral-700">
                @forelse($accounts as $ac)
                <tr>
                <tr class="hover:bg-gray-50 text-center dark:hover:bg-neutral-800 transition-colors">
                    <td class="px-4 py-4 text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{$loop->iteration}}
                    </td>
                    <td class="px-4 py-4 text-center text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{$ac->name}}
                    </td>
                    <td class="px-4 py-4 text-center text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{strtoupper($ac->type)}}
                    </td>
                    <td class="px-4 py-4 text-center text-sm text-gray-800 font-bold dark:text-neutral-300">
                        {{strtoupper($ac->context)}}
                    </td>
                    <td class="px-4 py-4 text-center text-sm text-gray-800 dark:text-neutral-300 font-bold">
                        {{$ac->rate}}
                    </td>
                    <td class="px-4 py-4 text-center text-sm text-gray-800 dark:text-neutral-300">
                        {{$ac->coaAccount->account_name ?? ''}}
                    </td>
                    <td class="px-4 py-4 text-center text-sm text-gray-800 dark:text-neutral-300">
                        <span class="ml-2 text-sm px-2 py-1 rounded-full {{ $ac->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                            {{ $ac->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center text-sm text-gray-800 dark:text-neutral-300">
                        {{$ac->user->name}}
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
                                No Tax found!
                            </p>
                            <p class="text-sm text-gray-500 dark:text-neutral-500">
                                Start by adding Tax
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
        <a href="{{route('accountant.list')}}" class="py-2 px-4 bg-blue-500 hover:bg-blue-600 hover:scale-105 rounded-md hover:text-white  transform transition duration-300 ease-in-out hover:shadow-cyan-200"> Back</a>
    </div>
</div>
@push('scripts')
@script()
<script>
    window.reinitSelect2 = () => {
        [{
                sel: '#context',
                model: 'context',
                placeholder: 'Select Context '
            },
            {
                sel: '#type',
                model: 'type',
                placeholder: 'Select Tax Type '
            },
            {
                sel: '#coa_id',
                model: 'coa_id',
                placeholder: 'Select COA ID '
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