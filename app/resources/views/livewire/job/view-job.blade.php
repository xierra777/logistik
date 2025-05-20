<div class="p-3 bg-white shadow sm:rounded-lg">
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
        @if($type_job === 'ocean_fcl_export' ||$type_job === 'air_outbound' )
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Delivery Agent </p>
            <p class="text-center  px-4 py-2 font-bold"> {{$job->dagents->name}}</p>
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
            <p class="text-center px-4 py-2 font-bold"> {{ $job->client->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col"> <!-- Disini Custome  -->
            <p class="text-center bg-gray-300 px-3 py-1">Customer Code Job </p>
            <p class="text-center font-bold px-4 py-2"> {{ $job->data['customerCodeJob'] }}</p>
        </div>
        @if($type_job === 'ocean_fcl_export')
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">MBL No </p>
            <p class="text-center font-bold px-4 py-2"> {{ $job->data['jobBillLadingNo'] }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">MBL Date </p>
            <p class="text-center px-4 py-2"> {{ $job->data['jobBillLadingDate'] }}</p>
        </div>
        @elseif($type_job === 'air_outbound')
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">MAWB No </p>
            <p class="text-center font-bold px-4 py-2"> {{ $job->data['jobBillLadingNo'] }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">MAWB Date </p>
            <p class="text-center px-4 py-2"> {{ $job->data['jobBillLadingDate'] }}</p>
        </div>
        @endif

        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Cross Trade </p>
            <p class="text-center px-4 py-2 uppercase"> {{ $job->data['cross_trade'] }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Inco Terms </p>
            <p class="text-center uppercase px-4 py-2"> {{ strtoupper($job->data['incoTerms'] ?? '-') }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Freight </p>
            <p class="text-center px-4 py-2"> {{ strtoupper($job->data['freightTypeJob'] ?? '-') }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1"> Employee </p>
            <p class="text-center px-4 py-2"> {{ $job->employee->name }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Remarks </p>
            <p class="text-center text-red-500 px-4 py-2">
                {{$job->data['remarksJobDetailJobs'] ?? ''}}
            </p>
        </div>
    </div>
    <div class="mt-2 mb-2">
        <button class="bg-blue-500 px-3 py-1 text-xs text-white rounded-md">BL</button>
        <button class="bg-green-500 px-3 py-1 text-xs text-white rounded-md">Create Bl</button>

    </div>
    <div class="text-center p-3 bg-orange-500 rounded-t-lg font-bold mt-4">
        <p class="">Details Vessel / FLight</p>
    </div>
    <div>
        <div class="grid grid-cols-1 md:grid-cols-3 shadow-lg">
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Carrier </p>
                <p class="text-center px-4 py-2"> {{ $job->carrierModel->name ?? '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Vessel Name </p>
                <p class="text-center px-4 py-2"> {{ $job->data['flightVesselName'] }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Voyage </p>
                <p class="text-center px-4 py-2"> {{ $job->data['flightVesselNo'] }}</p>
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
                <p class="text-center bg-gray-300 px-3 py-1">Port of Loading </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['port_of_loading'] ?? 'No'}}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Place of Receipt </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['place_of_receipt'] ?? ''}}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Place of delivery </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['place_of_delivery'] ?? ''}}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Port of Receipt </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['port_of_receipt'] ?? ''}}
                </p>
            </div>

            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Port of Discharge </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['port_of_discharge'] ?? ''}}
                </p>
            </div>
            <div class=<div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Port of Final </p>
                <p class="text-center px-4 py-2">
                    {{$job->data['port_of_final'] ?? ''}}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Services Type </p>
                <p class="text-center uppercase px-4 py-2"> {{ strtoupper($job->data['servicesType'] ?? '-') }}</p>
            </div>

        </div>
    </div>
    <div class="mt-3 rounded-lg shadow-lg">
        <div class="bg-blue-500 rounded-t-lg p-3 w-full block">
            <p class="text-center ">Organization </p>
        </div>
        <div class="overflow-x-auto">
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

    </div>
    <div class="mt-3 shadow-xl">
        <div class="bg-blue-600 rounded-t-lg mt-4 ">
            <p class="text-center mt-4 p-3  font-bold">Containers</p>
        </div>
        <div x-data="{ openCreateContainer: false }"
            @close-create-container.window="openCreateContainer = false">

            <div class="flex justify-end p-3">
                <button @click="openCreateContainer = true" class="py-3 px-4 bg-green-600 text-white rounded-lg">
                    Add Container
                </button>
            </div>
            <div x-cloak x-show="openCreateContainer"
                x-transition:enter="transition ease-out duration-300 delay-150"
                x-transition:enter-start=" opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="transition ease-in duration-100 scale-100 opacity-100"
                x-transition:leave-end="opacity-0"

                class="fixed inset-0 bg-gray-500 bg-opacity-50 pointer-events-none z-40">
            </div>
            <div x-cloak x-show="openCreateContainer"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="scale-90 opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="scale-100 opacity-100"
                x-transition:leave-end="scale-90 opacity-0"
                class="fixed inset-0 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-5xl">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Create Container</h2>
                        <button @click="openCreateContainer = false" class="text-gray-500 hover:text-gray-700">
                            &times;
                        </button>
                    </div>
                    <form wire:submit.prevent="createContainer">
                        <div class="p-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                                <!-- Left Column -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="flex flex-col space-y-2 col-span-2" wire:ignore>
                                        <label>Container Type</label>
                                        <select name="" id="containerType" class="w-full block rounded-md border border-gray-300">
                                            <option value=""></option>
                                            <option value="20'DG">20'DG</option>
                                            <option value="20'FT">20'FT</option>
                                            <option value="20'HQ">20'HQ</option>
                                            <option value="40'Standard">40'Standard</option>
                                        </select>
                                        @error('container_type')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <label>Container Release No.</label>
                                        <input type="text" placeholder="Enter Container No  " wire:model="containerReleaseNo"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                        @error('container_size')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <label>Release date</label>
                                        <input type="date" wire:model="containerReleaseDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                        @error('')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                                    </div>

                                    <!-- Package Info -->
                                    <div class="flex flex-col space-y-2">
                                        <label>No Of Packages</label>
                                        <input type="text" wire:model="noOfPackages" placeholder="Enter No Of Packages"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    <div class="flex flex-col space-y-2" wire:ignore>
                                        <label>Type Of Packages</label>
                                        <select id="typeOfPackages" class="block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value=""></option>
                                            <option value="packages">Packages</option>
                                            <option value="cartoon">Cartoon</option>
                                            <option value="roll">Roll</option>
                                            <option value="pallet">Pallet</option>
                                        </select>
                                    </div>

                                    <!-- Weight Info -->
                                    <div class="flex flex-col space-y-2">
                                        <label>Gross Weight</label>
                                        <input type="text" placeholder="Enter Gross weight" wire:model="grossWeight"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    <div class="flex flex-col space-y-2" wire:ignore>
                                        <label>Type Of Gross Weight</label>
                                        <select id="typeOfGrossWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value=""></option>
                                            <option value="KGS">KGS</option>
                                        </select>
                                    </div>

                                    <!-- Volume Weight Info -->
                                    <div class="flex flex-col space-y-2">
                                        <label>Volume Weight</label>
                                        <input type="text" wire:model="volumeWeight" placeholder="Enter Gross weight"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    <div class="flex flex-col space-y-2" wire:ignore>
                                        <label>Type Of Volume Weight</label>
                                        <select id="typeOfVolumeWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value=""></option>
                                            <option value="KGS">KGS</option>
                                        </select>
                                    </div>

                                    <!-- Volume Info -->
                                    <div class="flex flex-col space-y-2">
                                        <label>Volume</label>
                                        <div class="flex">
                                            <input type="text" wire:model="volume" placeholder="Enter volume"
                                                class="block w-full rounded-l-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                            <span class="inline-flex items-center px-3 border border-l-0 border-gray-300 bg-gray-100 text-gray-600 rounded-r-md">
                                                CBM
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <label>Chargeable Weight</label>
                                        <input type="text" placeholder="Enter Chargeable Weight" wire:model="chargableWeight"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>

                                    <!-- Remarks -->
                                    <div class="flex flex-col space-y-2 col-span-2">
                                        <label>Remarks</label>
                                        <textarea placeholder="Enter remarks" rows="3" wire:model="containerRemarks"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="flex flex-col space-y-2">
                                        <label>Container No.</label>
                                        <input type="text" placeholder="Enter Container No" wire:model="containerNo"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <label>Seal No.</label>
                                        <input type="text" placeholder="Enter Seal No" wire:model="containerSealNo"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <label>No of Pallet</label>
                                        <input type="text" placeholder="Enter No Of Pallet" wire:model="noOfPallet"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    <div></div>
                                    <div class="flex flex-col space-y-2">
                                        <label>Net Of Weight</label>
                                        <input type="text" placeholder="Enter Net Of Weight" wire:model="netOfWeight"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    <div class="flex flex-col space-y-2" wire:ignore>
                                        <label>Type Of Packages</label>
                                        <select id="typeNetOfWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value=""></option>
                                            <option value="KGS">KGS</option>
                                        </select>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <label>Total Weight</label>
                                        <input type="text" placeholder="Enter Net Of Weight" wire:model="totalWeight"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    <div class="flex flex-col space-y-2" wire:ignore>
                                        <label>Type Of Weight</label>
                                        <select id="typeOfTotalWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                            <option value=""></option>
                                            <option value="KGS">KGS</option>
                                        </select>
                                    </div>
                                    <div class="flex flex-col space-y-2">
                                        <label>HS Code</label>
                                        <input type="text" placeholder="Enter HS Code" wire:model="hsCode"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    <div></div>
                                    <div class="flex flex-col space-y-2 col-span-2">
                                        <label>HS Description</label>
                                        <textarea placeholder="Enter Hs Description" rows="3" wire:model="hsCodeDesc"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end gap-2">
                            <button type="button" @click="openCreateContainer = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                                Save Container
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
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
                        <td scope=" col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
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

    </div>
    <div class="mt-3 mb-4 shadow-xl">
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
    <hr class="border-gray-500 dark:border-neutral-500 mt-5">


    <!-- SIGMA Button -->
    <div class="p-4 flex justify-end">
        <a href="{{ url ('/list-job') }}"
            class="py-2 px-6 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg transform transition duration-200 ease-in-out shadow:hover-cyan-200 hover:bg-cyan-400 hover:scale-110">
            Back
        </a>
    </div>
</div>