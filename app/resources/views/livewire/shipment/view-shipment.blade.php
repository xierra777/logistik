@section('title', 'View Shipment')

<div class="p-3 bg-white shadow sm:rounded-lg">
    <div class="text-center p-3 bg-gray-100 border border-gray-40 rounded-t-lg font-bold">
        <p class="">Details Shipments</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 shadow-lg">
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Shipment No </p>
            <p class="text-center px-4 py-2"> {{ $shipments->shipment_id }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Shipment Date</p>
            <p class="text-center font-bold px-4 py-2"> {{ $shipments->dataShipments['shipmentBillLadingDate'] ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Client </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipments->client->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Type Job </p>
            <p class="text-center px-4 py-2"> {{ strtoupper(str_replace('_', ' ', $shipments->shipmentsTypeJob)) }}</p>
        </div>
        <div class="flex flex-col"> <!-- Disini Custome  -->
            <p class="text-center bg-gray-300 px-3 py-1">Customer Code Job </p>
            <p class="text-center font-bold px-4 py-2"> {{ $shipments->dataShipments['customerCodeJob'] ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Freight </p>
            <p class="text-center px-4 py-2"> {{ strtoupper($shipments->dataShipments['shipmentFreightTypeJob'] ?? '-') }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Shipper </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipments->shipper->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Consignee </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipments->consignee->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Notify </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipments->notify->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Carrier </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipments->carrierModel->name ?? '-' }}</p>
        </div>

        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Port of Loading </p>
            <p class="text-center px-4 py-2">
                {{$shipments->dataShipments['shipmentPort_of_loading'] ?? 'No'}}
            </p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Place of Receipt </p>
            <p class="text-center px-4 py-2">
                {{$shipments->dataShipments['shipmentPlace_of_receipt'] ?? ''}}
            </p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Place of delivery </p>
            <p class="text-center px-4 py-2">
                {{$shipments->dataShipments['shipmentPlace_of_delivery'] ?? ''}}
            </p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Port of Receipt </p>
            <p class="text-center px-4 py-2">
                {{$shipments->dataShipments['shipmentPort_of_loading'] ?? ''}}
            </p>
        </div>

        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Port of Discharge </p>
            <p class="text-center px-4 py-2">
                {{$shipments->dataShipments['shipmentPort_of_discharge'] ?? ''}}
            </p>
        </div>
        <div class=<div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Port of Final </p>
            <p class="text-center px-4 py-2">
                {{$shipments->dataShipments['shipmentPort_of_final'] ?? ''}}
            </p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Remarks </p>
            <p class="text-center text-red-500 px-4 py-2">
                {{$shipments->dataShipments['shipmentRemarksJobDetailJobs'] ?? ''}}
            </p>
        </div>
        @if($shipments->job)
        <div class="col-span-3 border bg-sky-300 border-gray-300 mt-3">
            <div class="grid grid-cols-1 md:grid-cols-3 ">
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">Job No </p>
                    <p class="text-center text-red-500 border font-bold text-md border-gray-900 px-4 py-2">
                        <a href="{{  $shipments->job ? route('viewJob', ['id' => $shipments->job->id]) : '#' }}"> {{$shipments->job->job_id ?? '-'}}</a>
                    </p>
                </div>
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">POL </p>
                    <p class="text-center border border-gray-900 px-4 py-2">
                        {{$shipments->dataShipments['shipmentPort_of_loading'] ?? '-'}}.
                    </p>
                </div>
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">POD </p>
                    <p class="text-center text-gray-500 border  border-gray-900 px-4 py-2">
                        {{$shipments->job->data['place_of_delivery'] ?? '-'}}.
                    </p>
                </div>
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">Job No </p>
                    <p class="text-center text-red-500 border font-bold text-md border-gray-900 px-4 py-2">
                        @isset($shipments->job)
                        <a href="{{ route('viewJob', ['id' => $shipments->job->id]) }}"> {{$shipments->job->job_id ?? '-'}}</a>
                        @else
                        @endisset
                    </p>
                </div>
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">POL </p>
                    <p class="text-center border border-gray-900 px-4 py-2">
                        {{$shipments->job->data['port_of_loading'] ?? '-'}}.
                    </p>
                </div>
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">POD </p>
                    <p class="text-center text-gray-500 border  border-gray-900 px-4 py-2">
                        {{$shipments->job->data['place_of_delivery'] ?? '-'}}.
                    </p>
                </div>
            </div>
        </div>
        @else
        @endif
    </div>
    <div class="flex justify-start p-1">
        <button class="bg-green-500 rounded-lg p-1  transform transition duration-200 ease-in-out hover:bg-green-400 text-white hover:text-gray-400 hover:scale-105 text-sm transform-transition">Create BL</button>
    </div>

    <div class="text-center p-3 bg-orange-500 rounded-t-lg font-bold mt-4">
        <p class="">Info Details</p>
    </div>
    <div>
        <div class="grid grid-cols-1 md:grid-cols-3 shadow-lg">
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Vessel Name </p>
                <p class="text-center px-4 py-2">{{ $shipments->dataShipments['shipmentFlightVesselName'] ?? '-' }}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Voyage </p>
                <p class="text-center px-4 py-2"> {{ $shipments->dataShipments['shipmentFlightVesselNo'] ?? '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Carrier </p>
                <p class="text-center px-4 py-2"> {{ $shipments->carrierModel->name ?? '-' }}</p>
            </div>
            <!-- i -->
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">MBL </p>
                <p class="text-center px-4 py-2"> {{ $shipments->job->data['jobBillLadingNo'] ?? '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">MBL Date </p>
                <p class="text-center px-4 py-2"> {{ $shipments->job->data['jobBillLadingDate'] ?? '-' }}</p>
            </div>
            <div class="flex flex-col">

            </div>
            <!-- Ini khusus HBL atau HAWB luar  -->
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">HBL </p>
                <p class="text-center px-4 py-2"> {{ $shipments->job->job_id ?? '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">HBL Date </p>
                <p class="text-center px-4 py-2"> {{ $shipments->job->d ?? '-' }}</p>
            </div>
            <div class="flex flex-col w-40">

            </div>
            <!-- End disini -->

            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">ETA / Estimate Time Arrival </p>
                <p class="text-center px-4 py-2"> {{ isset($shipments->dataShipments['shipmentEstimearrival']) ? \Carbon\Carbon::parse($shipments->dataShipments['shipmentEstimearrival'])->format('l, d F Y H:i'	) : '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">ETD / Estimate Time Departure </p>
                <p class="text-center px-4 py-2">
                    {{ isset($shipments->dataShipments['shipmentEstimedelivery']) ? \Carbon\Carbon::parse($shipments->dataShipments['shipmentEstimedelivery'])->format('l, d F Y H:i'	) : '-' }}
                </p>
            </div>

            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Services Type </p>
                <p class="text-center uppercase px-4 py-2"> {{ strtoupper($shipments->dataShipments['shipmentServices_type'] ?? '-') }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Cross Trade </p>
                <p class="text-center px-4 py-2 uppercase"> {{ $shipments->dataShipments['shipmentCross_trade'] }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Inco Terms </p>
                <p class="text-center uppercase px-4 py-2"> {{ strtoupper($shipments->dataShipments['shipmentIncoTerms'] ?? '-') }}</p>
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
                        <td scope="col" class="px-6 py-4  text-left text-xs font-medium text-gray-800 dark:text-neutral-200">
                            {{$org['dataShipments']->name ?? '-'}}
                        </td>
                        <td scope="col" class="px-6 py-4  text-xs font-medium text-gray-800 dark:text-neutral-200">
                            {{ $org['dataShipments']->address ?? '-' }}
                        </td>
                        <td scope="col" class="px-6 py-4  text-xs font-medium text-gray-800 dark:text-neutral-200">
                            {{$org['dataShipments']->email ?? '-'}}
                        </td>
                        <td scope="col" class="px-6 py-4  text-xs font-medium text-gray-800 dark:text-neutral-200">
                            {{$org['dataShipments']->contact ?? '-'}}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            <a href="{{ url('view-customers/' . $org['dataShipments']->id) }}"
                                class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg
                      transform transition duration-200 ease-in-out hover:bg-cyan-400 hover:scale-105">
                                <i class="fa-regular fa-user"></i> View Customer
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr wire:loading.remove>
                        <td colspan=" 7" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <img src="{{ asset('images/nodataShipments.svg') }}"
                                    alt="No dataShipments illustration"
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
                            Retrieving dataShipments…
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    <div class="mt-3  shadow-lg">
        <div class="bg-blue-600 rounded-t-lg mt-4 ">
            <p class="text-center mt-4 p-3  font-bold">Containers</p>
        </div>
        <div x-data="{ openContainer: false }"
            @close-create-container.window="openContainer = false">
            <div class="flex justify-end p-3">
                <button @click="openContainer = true" class="py-3 px-4 bg-green-600 text-white rounded-lg">
                    Add Container
                </button>
            </div>
            <div x-cloak x-show="openContainer"
                x-transition:enter="transition ease-out duration-300 delay-150"
                x-transition:enter-start=" opacity-0"
                x-transition:enter-end="scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="transition ease-in duration-100 scale-100 opacity-100"
                x-transition:leave-end="opacity-0"

                class="fixed inset-0 bg-gray-500 bg-opacity-50 pointer-events-none z-40">
            </div>
            <div x-cloak x-show="openContainer"
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
                        <button @click="openContainer = false" class="text-gray-500 hover:text-gray-700">
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
                            <button type="button" @click="openContainer = false" class="px-4 py-2 bg-gray-300 text-gray-800 rounded">
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
                            No Activity / Container No
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
                    @forelse($shipments->container as $c)
                    <tr>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $loop->iteration  * 10 }}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$c->jobContainer->containers['containerNo'] ?? ''}}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$c->containersData['shipmentNoOfPackages'] ?? ''}}
                        </td>

                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            <a href="{{ url('view-shipment/' . $shipments->id . '/container-shipment/' . $c->id) }}"
                                class="inline-block py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg
                                         transition duration-200 ease-in-out hover:bg-cyan-400 hover:scale-110">
                                <i class="fa-regular fa-file"></i> See Attachment
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr wire:loading.remove>
                        <td colspan=" 7" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <img src="{{ asset('images/nodataShipments.svg') }}"
                                    alt="No dataShipments illustration"
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
                            Retrieving dataShipments…
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
    <div class="mt-4 shadow-lg ">
        <div>
            <p class="text-center p-3 bg-gray-400 rounded-t-lg font-bold italic border">Transaction still under Construction <br>
                <span class="text-red-700">...</span>
            </p>
            <div class="flex justify-end p-1 ">
                <div x-data="{ open: false }" @close-modal.window="open = false"
                    x-ref="modalContent">
                    <div class=" flex justify-end mb-4 p-4">
                        <button
                            wire:click="refreshTransaction({{ $shipments->id }})" @click="open = true"
                            class="py-3 px-4 bg-blue-600 text-white rounded-lg">
                            Add Cost
                        </button>
                    </div>

                    <!-- Background Overlay -->
                    <div x-cloak x-show="open"
                        x-transition:enter="transition ease-out duration-300 delay-150"
                        x-transition:leave="transition ease-in duration-200"
                        class="fixed inset-0 bg-gray-500 bg-opacity-50 z-40">
                    </div>

                    <!-- Modal Container -->
                    <div x-cloak x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="scale-90 opacity-0"
                        x-transition:enter-end="scale-100 opacity-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="scale-100 opacity-100"
                        x-transition:leave-end="scale-90 opacity-0"
                        class="fixed inset-0 flex items-center justify-center z-50 px-4">
                        <div class="bg-white rounded-lg shadow-md w-full max-w-7.5xl">
                            <!-- Modal Header -->
                            <div class="flex justify-between items-center p-4 border-b">
                                <h2 class="text-lg font-semibold text-gray-800">Costing</h2>
                                <button @click="open = false" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                            <!-- Form -->
                            <livewire:shipment.transaction.create-transaction
                                :id="$shipments->id"
                                :key="'transaction' . $shipments->id . '-' . now()->timestamp" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table-hover min-w-full divide-y divide-gray-200 dark:divide-neutral-700 text-center">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            No
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Unit
                        </th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Client
                        </th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Sale
                        </th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Amount (IDR)
                        </th>
                        <th scope="col" class="px-6 py-3 bg-orange-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Dr/Cr
                        </th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Vendor
                        </th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Cost
                        </th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Amount (IDR)
                        </th>
                        <th scope="col" class="px-6 py-3 bg-blue-500 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Dr/Cr
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Freight
                        </th>
                        <th scope="col" class="px-6 py-3 text-xs font-bold text-gray-700 uppercase dark:text-neutral-400">
                            Gross Profit
                        </th>
                    </tr>
                </thead>
                <tbody class="">
                    @forelse($shipments->shipmentTransaction as $transaction)
                    <tr>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $loop->iteration }}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $transaction->description }}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        </td>

                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">

                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">

                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">

                        </td>
                    </tr>
                    @empty
                    <tr wire:loading.remove>
                        <td colspan="13" class="py-12 text-center">
                            <div class="flex flex-col text-center items-center justify-center">
                                <img src="{{ asset('images/nodata.svg') }}"
                                    alt="No dataShipments illustration"
                                    class="w-64 h-48 mb-4 opacity-75  dark:opacity-50">
                                <p class="text-md font-medium text-gray-600 dark:text-neutral-300">
                                    Mohon Kesediann Menunggu, Modul Masih dalam Pengerjaan
                                </p>
                            </div>
                        </td>
                    </tr>
                    <tr wire:loading class="animate-pulse">
                        <td colspan="6" class="py-12 text-center text-gray-500 dark:text-neutral-400">
                            Retrieving dataShipments…
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <hr class="border-gray-500 dark:border-neutral-500 mt-5">


    <!-- SIGMA Button -->
    <div class="p-4 flex justify-end">
        <a href="{{ url ('/list-shipment') }}"
            class="py-2 px-6 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg transform transition duration-200 ease-in-out shadow:hover-cyan-200 hover:bg-cyan-400 hover:scale-110">
            Back
        </a>
    </div>
</div>