<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Details Customer') }}
        </h2>
    </x-slot>

    <div class="bg-stone-350 p-3">
        <div class="font-bold border border-gray-300 p-2 bg-gray-100">
            Customer Details
        </div>
        <table class="w-full border border-gray-300 border-collapse">
            <tbody class="divide-y divide-gray-300">
                <tr class="bg-gray-50">
                    <td class="border px-4 py-2 font-semibold w-1/4">Name</td>
                    <td class="border px-4 py-2 w-1/4">{{ $customer->name }}</td>
                    <td class="border px-4 py-2 font-semibold w-1/4">Country</td>
                    <td class="border px-4 py-2 w-1/4">{{ $customer->country }}</td>
                </tr>
                <tr>
                    <td class="border px-4 py-2 font-semibold">Email</td>
                    <td class="border px-4 py-2">{{ $customer->email }}</td>
                    <td class="border px-4 py-2 font-semibold">Contact</td>
                    <td class="border px-4 py-2">{{ $customer->contact }}</td>
                </tr>
                <tr class="bg-gray-50">
                    <td class="border px-4 py-2 font-semibold">Web</td>
                    <td class="border px-4 py-2">{{ $customer->web }}</td>
                    <td class="border px-4 py-2 font-semibold">Roles</td>
                    <td class="border px-4 py-2">
                        {{ is_array($customer->roles) ? implode(', ', $customer->roles) : $customer->roles }}
                    </td>
                </tr>
            </tbody>
        </table>



        <div class="mt-5">
            <div class="border border-1 border-gray-300 rounded-md">
                <h2 class="text-lg font-semibold mb-4 border-collapse border border-1 p-2 border-cyan-300 bg-cyan-400 rounded-t-md">Address</h2>
                <div class="p-1">
                    <p class="text-sm text-gray-800 dark:text-gray-200 p-2">{{ $customer->address }}</p>
                </div>
            </div>
            <div class="border border-1 border-gray-300 rounded-md mt-5">
                <h2 class="text-lg font-semibold  border-collapse border border-1 p-2 border-cyan-300 bg-cyan-400 rounded-t-md">Accounting</h2>
                <table class="w-full border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">Account Code</th>
                            <th class="border border-gray-300 px-4 py-2">Account Name</th>
                            <th class="border border-gray-300 px-4 py-2">Term Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-center bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $chartOfAccount->account_code }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $chartOfAccount->account_name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $chartOfAccount->term_type }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <hr class="border border-gray-300 m-5">
        <div class="mt-5 flex  justify-end m-2">
            <a href="{{ route('customers.list') }}"
                class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg 
               transform transition duration-200 ease-in-out shadow:hover-cyan-200
               hover:bg-cyan-400 hover:scale-110  ">
                Back
            </a>
        </div>
    </div>
</div>