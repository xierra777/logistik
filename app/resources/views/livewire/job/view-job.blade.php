<div class="p-3">
    <div class="text-center p-3 bg-blue-500 rounded-t-lg font-bold">
        <p class="">Details Job</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 shadow-lg">
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Type Job </p>
            <p class="text-center px-4 py-2"> {{ strtoupper(str_replace('_', ' ', $job->type_job)) }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Job No </p>
            <p class="text-center px-4 py-2"> {{ $job->job_id }}</p>
        </div>
        @if($type_job === 'ocean_fcl_export')
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Delivery Agent </p>
            <p class="text-center  px-4 py-2"> {{$job->dagents->name}}</p>
        </div>
        @elseif($type_job === 'ocean_fcl_import')
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Origin Agent </p>
            <p class="text-center px-4 py-2"> {{$job->oagents->name}}</p>
        </div>
        @else
        Default Agent
        @endif
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Client </p>
            <p class="text-center px-4 py-2"> {{ $job->client->name }}</p>
        </div>
        <div class="flex flex-col"> <!-- Disini Custome  -->
            <p class="text-center bg-gray-300 px-3 py-1">Customer Code Job </p>
            <p class="text-center font-bold px-4 py-2"> {{ $job->data['customerCodeJob'] }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">MBL No </p>
            <p class="text-center font-bold px-4 py-2"> {{ $job->data['mbl_no'] }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">MBL Date </p>
            <p class="text-center px-4 py-2"> {{ $job->data['mbl_date'] }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Cross Trade </p>
            <p class="text-center px-4 py-2"> {{ $job->data['cross_trade'] }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Inco Terms </p>
            <p class="text-center uppercase px-4 py-2"> {{ strtoupper($job->data['incoTerms'] ?? '-') }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Freight </p>
            <p class="text-center px-4 py-2"> {{ strtoupper($job->data['freightTypeJob'] ?? '-') }}</p>
        </div>
    </div>
    <div class="mt-2 mb-2">
        <button class="bg-blue-500 px-3 py-1 text-xs text-white rounded-md">BL</button>
        <button class="bg-green-500 px-3 py-1 text-xs text-white rounded-md">Create Bl</button>

    </div>
    <div class="text-center p-3 bg-orange-500 rounded-t-lg font-bold mt-4">
        <p class="">Details Vessel</p>
    </div>
    <div>
        <div class="grid grid-cols-1 md:grid-cols-3 shadow-lg">
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Carrier </p>
                <p class="text-center px-4 py-2"> {{ $job->carrierModel->name ?? '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Vessel Name </p>
                <p class="text-center px-4 py-2"> {{ $job->data['vessel_name'] }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Voyage </p>
                <p class="text-center px-4 py-2"> {{ $job->data['voyage'] }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">ETA / Estimate Time Arrival </p>
                <p class="text-center px-4 py-2"> {{ isset($job->data['estimedelivery']) ? \Carbon\Carbon::parse($job->data['estimearrival'])->format('l, d F Y H:i'	) : '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">ETD / Estimate Time Departure </p>
                <p class="text-center px-4 py-2">
                    {{ isset($job->data['estimedelivery']) ? \Carbon\Carbon::parse($job->data['estimedelivery'])->format('l, d F Y H:i'	) : '-' }}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Hazardous </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['hazardousClassType'] ?? 'No'}}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Port Loading </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['port_of_loading'] ?? 'No'}}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Port Loading </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['place_of_receipt'] ?? ''}}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Port Loading </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['port_of_delivery'] ?? ''}}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Port Loading </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['port_of_discharge'] ?? ''}}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Services Type </p>
                <p class="text-center uppercase px-4 py-2"> {{ strtoupper($job->data['servicesType'] ?? '-') }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Remarks </p>
                <p class="text-center text-red-500 px-4 py-2">
                    {{$job->data['remarksJobDetailJobs'] ?? ''}}
                </p>
            </div>
        </div>
    </div>
    <div class="mt-3 rounded-lg shadow-lg ">
        <p class="text-center bg-blue-500 rounded-t-lg p-3">Organization </p>
        <table class="table-hover min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-center">
            <thead>
                <tr>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Group
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Addres
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Contact
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        See Attach
                    </th>
                </tr>
            </thead>
            <tbody class="">
                @forelse($this->organizations as $org)
                <tr>
                    <td scope="col" class="px-6 py-4  text-xs font-medium text-gray-800 dark:text-neutral-200">
                        {{ $loop->iteration   }}
                    </td>
                    <td scope="col" class="px-6 py-4  text-xs font-medium text-gray-800 dark:text-neutral-200">
                        {{ $org['label']  }}
                    </td>
                    <td scope="col" class="px-6 py-4  text-xs font-medium text-gray-800 dark:text-neutral-200">
                        {{$org['data']->name ?? '-'}}
                    </td>
                    <td scope="col" class="px-6 py-4  text-xs font-medium text-gray-800 dark:text-neutral-200">
                        {{$org['data']->address ?? '-'}}
                    </td>
                    <td scope="col" class="px-6 py-4  text-xs font-medium text-gray-800 dark:text-neutral-200">
                        {{$org['data']->email ?? '-'}}
                    </td>
                    <td scope="col" class="px-6 py-4  text-xs font-medium text-gray-800 dark:text-neutral-200">
                        {{$org['data']->contact ?? '-'}}
                    </td>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        <button href="" class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg
                            transform transition duration-200 ease-in-out shadow:hover-cyan-200
                            hover:bg-cyan-400 hover:scale-110 ">
                            <i class="fa-regular fa-file"></i> See Attachment
                        </button>
                    </td>
                </tr>
                @empty
                <tr wire:loading.remove>
                    <td colspan=" 7" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <img src="{{ asset('images/nodata.svg') }}"
                                alt="No data illustration"
                                class="w-64 h-48 mb-4 opacity-75 dark:opacity-50">
                            <p class="text-lg font-medium text-gray-600 dark:text-neutral-300">
                                No Contaner found!
                            </p>
                            <p class="text-sm text-gray-500 dark:text-neutral-500 text-center">
                                Start Add container
                            </p>
                        </div>
                    </td>
                </tr>
                <tr wire:loading class="animate-pulse">
                    <td colspan="6" class="py-12 text-center text-gray-500 dark:text-neutral-400">
                        Retrieving data…
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="shadow-lg">
        <div class="bg-blue-600 rounded-t-lg mt-4 ">
            <p class="text-center mt-4 p-3  font-bold">Containers</p>
        </div>
        <div class="flex justify-end p-2">
            <button class="bg-blue-500 px-4 py-2 rounded-md text-white justify-end"> ADD </button>
        </div>
        <table class="table-hover min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-center">
            <thead>
                <tr>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        No
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        No Activity / No Container
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Type Container
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Gross Weight
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        Volume
                    </th>
                    <th scope="col" class="px-6 py-3 text-sm font-bold text-gray-700 uppercase dark:text-neutral-400">
                        See Attach
                    </th>
                </tr>
            </thead>
            <tbody class="">
                @forelse($job->TjobContainer as $c)
                <tr>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{ $loop->iteration  * 10 }}
                    </td>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{$c->containers['containerNo'] ?? ''}}
                    </td>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{$c->containers['containerType'] ?? ''}}
                    </td>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{$c->containers['grossWeight'] ?? ''}}
                    </td>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{$c->containers['netOfWeight'] ?? ''}}
                    </td>
                    <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        <button href="" class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg
                            transform transition duration-200 ease-in-out shadow:hover-cyan-200
                            hover:bg-cyan-400 hover:scale-110 ">
                            <i class="fa-regular fa-file"></i> See Attachment
                        </button>
                    </td>
                </tr>
                @empty
                <tr wire:loading.remove>
                    <td colspan=" 7" class="py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <img src="{{ asset('images/nodata.svg') }}"
                                alt="No data illustration"
                                class="w-64 h-48 mb-4 opacity-75 dark:opacity-50">
                            <p class="text-lg font-medium text-gray-600 dark:text-neutral-300">
                                No Contaner found!
                            </p>
                            <p class="text-sm text-gray-500 dark:text-neutral-500 text-center">
                                Start Add container
                            </p>
                        </div>
                    </td>
                </tr>
                <tr wire:loading class="animate-pulse">
                    <td colspan="6" class="py-12 text-center text-gray-500 dark:text-neutral-400">
                        Retrieving data…
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-2">
        <div class="bg-cyan-500 rounded-t-lg p-3 ">
            <p class="text-lg font-bold text-center ">Shipments ( Under Construction )</p>
        </div>
        <div class=" grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>Ini kotak</div>
            <div>Ini kotak</div>
            <div>Ini kotak</div>
            <div>Ini kotak</div>
            <div>Ini kotak</div>
            <div>Ini kotak</div>
            <div>Ini kotak</div>
            <div>Ini kotak</div>
            <div>Ini kotak</div>

        </div>
    </div>

</div>