<div class="p-2">
    <div class="flex justify-end mb-4 gap-4">
        <button class="px-4 py-2 bg-red-200 rounded-md hover:scale-105 transition-transform duration-400 ease-in-out hover:bg-red-400 hover:text-white">Make a Payment!</button>
        <div x-data="{ openPayment : false}" @close-modal.window="openPayment = false" @keydown.escape.window="openPayment = false" @click.away="openPayment = false">
            <button @click="openPayment = true" class="px-4 py-2 bg-blue-200 rounded-md hover:scale-105 transition-transform duration-400 ease-in-out hover:bg-blue-400 hover:text-white">Make a Payment!</button>

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
                    <form action="POST" class="p-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div class="flex flex-col">
                                <label for="date">Date</label>
                                <input type="date" class="w-full rounded-md border-gray-300">
                            </div>
                            <div class="flex flex-col">
                                <label for="">Payment No</label>
                                <input type="text" class="w-full rounded-md border-gray-300">
                            </div>
                            <div class="flex flex-col">
                                <label for="">customer_id / vendor_id </label>
                                <select name="" id="">
                                    <option value=""></option>
                                    @foreach($customers as $customer)
                                    @foreach($customer->shipment->shipmentTransaction as $tx)
                                    <option value="{{ $tx->id }}">[Shipment] {{ $tx->transactionClient->name }}</option>
                                    @endforeach

                                    @foreach($customer->job->jobTransactions ?? [] as $tx)
                                    <option value="{{ $tx->id }}">[Job] {{ $tx->sclient }}</option>
                                    @endforeach
                                    @endforeach


                                </select>
                            </div>
                            <div class="flex flex-col">
                                <label for="">Payment No</label>
                                <input type="text" class="w-full rounded-md border-gray-300">
                            </div>
                            <div class="flex flex-col">
                                <label for="">Payment No</label>
                                <input type="text" class="w-full rounded-md border-gray-300">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full min-w-max border-collapse">
            <thead>
                <tr>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">hello</th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">hello</th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">hello</th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">hello</th>
                    <th class="font-semibold px-2 py-1 border border-gray-300 whitespace-nowrap">hello</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">hello</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">hello</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">hello</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">hello</td>
                    <td class="text-gray-700 text-center border border-gray-300 whitespace-nowrap">hello</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>