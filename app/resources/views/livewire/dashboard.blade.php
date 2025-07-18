<div class="p-6 text-dark-900 dark:text-gray-100">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        {{-- Shipments Section --}}
        <div class="shadow-md rounded-md h-full">
            <div class="overflow-x-auto rounded-md">
                <div class="bg-yellow-900 flex between items-center justify-between p-2">
                    <h1 class="font-semibold text-neutral-100">Shipments</h1>
                    <div class="flex justify-end">
                        <a href="{{ url('/list-shipment') }}" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        <a href="{{ url('create-shipment')}}" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md">
                    <thead class="bg-gray-50 dark:bg-neutral-800 rounded-t-xl">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Shipment ID</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Shipper</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900 rounded-xl">
                        @forelse($shipments as $shipment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 dark:text-neutral-300 hover:underline hover:text-blue-900 ">
                                <a wire:navigate href="/view-shipment/{{ $shipment->id }}">{{ $shipment->shipment_id }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-neutral-300 font-semibold">
                                {{ $shipment->shipper?->name }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <p class="text-gray-600 dark:text-neutral-300 text-lg font-medium mb-2">No shipments found!</p>
                                <p class="text-sm text-gray-500 dark:text-neutral-500">Start by adding shipments.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Job Section --}}
        <div class="shadow-md rounded-md h-full">
            <div class="overflow-x-auto rounded-md">
                <div class="bg-yellow-500 flex between items-center justify-between p-2">
                    <h1 class="font-semibold text-neutral-100">Job </h1>
                    <div class="flex justify-end">
                        <a href="{{ url('/list-job') }}" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        <a href="{{ url('shipment')}}" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md">
                    <thead class="bg-gray-50 dark:bg-neutral-800 rounded-t-xl">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Job ID</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-W500 uppercase dark:text-neutral-400">Client</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900 rounded-xl">
                        @forelse($jobs as $j)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 dark:text-neutral-300 hover:underline hover:text-blue-900 ">
                                <a wire:navigate href="/view-job/{{ $j->id }}">{{ $j->job_id }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-neutral-300 font-semibold">
                                {{ $j->client?->name }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <p class="text-gray-600 dark:text-neutral-300 text-lg font-medium mb-2">No shipments found!</p>
                                <p class="text-sm text-gray-500 dark:text-neutral-500">Start by adding shipments.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Customers Section --}}
        <div class="shadow-md rounded-md  h-full">
            <div class="overflow-x-auto rounded-md">
                <div class="bg-orange-400 flex between items-center justify-between p-2">
                    <h1 class="font-semibold text-neutral-100">Customers</h1>
                    <div class="flex justify-end">
                        <a href="{{ url('/customers') }}" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        <a href="" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md">
                    <thead class="bg-gray-50 dark:bg-neutral-800 rounded-t-xl">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Name</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Roles</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Country</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900 rounded-xl">
                        @forelse($customers as $c)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 dark:text-neutral-300 hover:underline hover:text-blue-900 ">
                                <a wire:navigate href="/view-customers/{{$c->id}}">{{ $c->name }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 dark:text-neutral-300 font-semibold uppercase">
                                {{ implode(', ', $c->roles) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 dark:text-neutral-300  ">
                                {{ $c->country }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-12 text-center">
                                <p class="text-gray-600 dark:text-neutral-300 text-lg font-medium mb-2">No customers found!</p>
                                <p class="text-sm text-gray-500 dark:text-neutral-500">Start by adding customers.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Invoice Section --}}
        <div class="shadow-md rounded-md  h-full">
            <div class="overflow-x-auto rounded-md">
                <div class="bg-green-700 flex between items-center justify-between p-2">
                    <h1 class="font-semibold text-neutral-100">Invoice issued</h1>
                    <div class="flex justify-end">
                        <a href="{{ url('userList') }}" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        <a href="" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md">
                    <thead class="bg-gray-50 dark:bg-neutral-800 rounded-t-xl">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Invoice Number</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Total</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900 rounded-xl">
                        @forelse($invoices as $inv)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 dark:text-neutral-300 hover:underline hover:text-blue-900 text-center">
                                {{ $inv->invoice_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 dark:text-neutral-300 hover:underline hover:text-blue-900 text-center">
                                Rp. {{ number_format($inv->total_amount , 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{$inv->status === 'issued' ? 'text-green-500' : 'text-dark'}} dark:text-neutral-300 hover:underline hover:text-blue-900 text-center uppercase">
                                {{ $inv->status }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center">
                                <p class="text-gray-600 dark:text-neutral-300 text-lg font-medium mb-2">No Invoice found!</p>
                                <p class="text-sm text-gray-500 dark:text-neutral-500">Start by adding Invoice.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{-- Employee Section --}}
        <div class="shadow-md rounded-md  h-full">
            <div class="overflow-x-auto rounded-md">
                <div class="bg-green-400 flex between items-center justify-between p-2">
                    <h1 class="font-semibold text-neutral-100">Employee</h1>
                    <div class="flex justify-end">
                        <a href="{{ url('userList') }}" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                        <a href="" class="px-4 py-1 text-white">
                            <i class="fa-solid fa-plus"></i>
                        </a>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700 rounded-md">
                    <thead class="bg-gray-50 dark:bg-neutral-800 rounded-t-xl">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Name</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase dark:text-neutral-400">Email</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900 rounded-xl">
                        @forelse($users as $s)
                        <tr class="hover:bg-gray-50 dark:hover:bg-neutral-600 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 dark:text-neutral-300 hover:underline hover:text-blue-900 text-center">
                                {{ $s->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600 dark:text-neutral-300 hover:underline hover:text-blue-900 text-center">
                                {{ $s->email }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="py-12 text-center">
                                <p class="text-gray-600 dark:text-neutral-300 text-lg font-medium mb-2">No customers found!</p>
                                <p class="text-sm text-gray-500 dark:text-neutral-500">Start by adding customers.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>