@section('title', 'View Shipment')

<div class="p-3 bg-white shadow sm:rounded-lg">
    <div class="text-center p-3 bg-gray-100 border border-gray-40 rounded-t-lg font-bold">
        <p class="">Details Shipments</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 shadow-lg">
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Shipment No </p>
            <p class="text-center px-4 py-2"> {{ $shipment->shipment_id }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Shipment Date</p>
            <p class="text-center font-bold px-4 py-2"> {{ $shipment->dataShipments['shipmentBillLadingDate'] ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Client </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipment->client->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Type Job </p>
            <p class="text-center px-4 py-2"> {{ strtoupper(str_replace('_', ' ', $shipment->shipmentsTypeJob)) ?? '' }}</p>
        </div>
        <div class="flex flex-col"> <!-- Disini Custome  -->
            <p class="text-center bg-gray-300 px-3 py-1">Customer Code Job </p>
            <p class="text-center font-bold px-4 py-2"> {{ $shipment->dataShipments['customerCodeJob'] ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Freight </p>
            <p class="text-center px-4 py-2"> {{ strtoupper($shipment->dataShipments['shipmentFreightTypeJob'] ?? '-') }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Shipper </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipment->shipper->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Consignee </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipment->consignee->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Notify </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipment->notify->name ?? '-' }}</p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Carrier </p>
            <p class="text-center px-4 py-2 font-bold"> {{ $shipment->carrierModel->name ?? '-' }}</p>
        </div>

        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Port of Loading </p>
            <p class="text-center px-4 py-2">
                {{$shipment->dataShipments['shipmentPort_of_loading'] ?? 'No'}}
            </p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Place of Receipt </p>
            <p class="text-center px-4 py-2">
                {{$shipment->dataShipments['shipmentPlace_of_receipt'] ?? ''}}
            </p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Place of delivery </p>
            <p class="text-center px-4 py-2">
                {{$shipment->dataShipments['shipmentPlace_of_delivery'] ?? ''}}
            </p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Port of Receipt </p>
            <p class="text-center px-4 py-2">
                {{$shipment->dataShipments['shipmentPort_of_loading'] ?? ''}}
            </p>
        </div>

        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Port of Discharge </p>
            <p class="text-center px-4 py-2">
                {{$shipment->dataShipments['shipmentPort_of_discharge'] ?? ''}}
            </p>
        </div>
        <div class=<div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Port of Final </p>
            <p class="text-center px-4 py-2">
                {{$shipment->dataShipments['shipmentPort_of_final'] ?? ''}}
            </p>
        </div>
        <div class="flex flex-col">
            <p class="text-center bg-gray-300 px-3 py-1">Remarks </p>
            <p class="text-center text-red-500 px-4 py-2">
                {{$shipment->dataShipments['shipmentRemarksJobDetailJobs'] ?? ''}}
            </p>
        </div>
        <!-- Tentuin Jobnya air apa ocean -->
        @if($shipment->job)
        <div class="col-span-3 border bg-sky-300 border-gray-300 mt-3">
            <div class="grid grid-cols-1 md:grid-cols-3 ">
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">Job No </p>
                    <p class="text-center text-red-500 border font-bold text-md border-gray-900 px-4 py-2">
                        <a href="{{  $shipment->job ? route('viewJob', ['id' => $shipment->job->id]) : '#' }}"> {{$shipment->job->job_id ?? '-'}} / {{ $shipment->job->created_at->format('d-M-Y') }}
                        </a>
                    </p>
                </div>
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">Vessel Code </p>
                    <p class="text-center border border-gray-900 px-4 py-2">
                        {{$shipment->job->data['flightVesselName'] ?? '-'}} {{$shipment->job->data['flightVesselNo']}}
                    </p>
                </div>
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">Carrier</p>
                    <p class="text-center border  border-gray-900 px-4 py-2">
                        {{$shipment->job->carrierModel->name ?? '-'}}
                    </p>
                </div>

                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">MBL No</p>
                    <p class="text-center border font-bold text-md border-gray-900 px-4 py-2">
                        {{$shipment->job->jobBillLadingNo ?? '-'}}
                    </p>
                </div>
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">ETD</p>
                    <p class="text-center border border-gray-900 px-4 py-2">
                        {{ optional(optional($shipment->job)->data['estimedelivery'] ? \Carbon\Carbon::parse($shipment->job->data['estimedelivery']) : null)->format('d-M-Y') ?? '-' }}
                    </p>
                </div>
                <div class="flex flex-col">
                    <p class="text-center border border-gray-900 text-gray-900 px-3 py-1">ETA</p>
                    <p class="text-center  border  border-gray-900 px-4 py-2">
                        {{ optional(optional($shipment->job)->data['estimearrival'] ? \Carbon\Carbon::parse($shipment->job->data['estimearrival']) : null)->format('d-M-Y') ?? '-' }}
                    </p>
                </div>
            </div>
        </div>
        @else
        @endif
        <!-- End Tentuin Jobnya air apa ocean -->
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
                <p class="text-center px-4 py-2">{{ $shipment->dataShipments['shipmentFlightVesselName'] ?? '-' }}
                </p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Voyage </p>
                <p class="text-center px-4 py-2"> {{ $shipment->dataShipments['shipmentFlightVesselNo'] ?? '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Carrier </p>
                <p class="text-center px-4 py-2"> {{ $shipment->carrierModel->name ?? '-' }}</p>
            </div>
            <!-- i -->
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">MBL </p>
                <p class="text-center px-4 py-2"> {{ $shipment->job->data['jobBillLadingNo'] ?? '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">MBL Date </p>
                <p class="text-center px-4 py-2"> {{ $shipment->job->data['jobBillLadingDate'] ?? '-' }}</p>
            </div>
            <div class="flex flex-col">

            </div>
            <!-- Ini khusus HBL atau HAWB luar  -->
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">HBL </p>
                <p class="text-center px-4 py-2"> {{ $shipment->job->job_id ?? '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">HBL Date </p>
                <p class="text-center px-4 py-2"> {{ $shipment->job->d ?? '-' }}</p>
            </div>
            <div class="flex flex-col w-40">

            </div>
            <!-- End disini -->

            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">ETA / Estimate Time Arrival </p>
                <p class="text-center px-4 py-2"> {{ isset($shipment->dataShipments['shipmentEstimearrival']) ? \Carbon\Carbon::parse($shipment->dataShipments['shipmentEstimearrival'])->format('l, d F Y H:i'	) : '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">ETD / Estimate Time Departure </p>
                <p class="text-center px-4 py-2">
                    {{ isset($shipment->dataShipments['shipmentEstimedelivery']) ? \Carbon\Carbon::parse($shipment->dataShipments['shipmentEstimedelivery'])->format('l, d F Y H:i'	) : '-' }}
                </p>
            </div>

            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Services Type </p>
                <p class="text-center uppercase px-4 py-2"> {{ strtoupper($shipment->dataShipments['shipmentServices_type'] ?? '-') }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Cross Trade </p>
                <p class="text-center px-4 py-2 uppercase"> {{ $shipment->dataShipments['shipmentCross_trade'] ?? '-' }}</p>
            </div>
            <div class="flex flex-col">
                <p class="text-center bg-gray-300 px-3 py-1">Inco Terms </p>
                <p class="text-center uppercase px-4 py-2"> {{ strtoupper($shipment->dataShipments['shipmentIncoTerms'] ?? '-') }}</p>
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
        <div x-data="{ openContainer: false }" x-init="initContainerSelect2()"
            @close-create-container.window=" openContainer=false">
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
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                               @if(optional($shipment->job)->TjobContainer && $shipment->job->TjobContainer->isNotEmpty())
                                    <div class="flex flex-col space-y-2 col-span-2" wire:ignore>
                                        <label>Container No</label>
                                        <select id="parentContainer" wire:model="parentContainer" class="rounded-md border-gray-300 shadow-sm">
                                            <option value=""></option>
                                            @foreach($shipment->job->TjobContainer as $cont)
                                                <option value="{{ $cont->id }}">{{ $cont->containers['containerNo'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                <div class="flex flex-col space-y-2 col-span-2">
                                    <label>Container No</label>
                                    <input type="text" wire:model="parentContainer" class="mt-1 w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring focus:ring-blue-200">
                                </div>
                                @endif
                                <div class="col-span-2"></div>

                                <!-- Package Info -->
                                <div class="flex flex-col space-y-2">
                                    <label>No Of Packages</label>
                                    <input type="text" wire:model="shipmentNoOfPackages" placeholder="Enter No Of Packages"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                </div>
                                <div class="flex flex-col space-y-2" wire:ignore>
                                    <label>Type Of Packages</label>
                                    <select id="typeOfPackages" class="block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value=""></option>
                                        <option value="PACKAGES">Packages</option>
                                        <option value="CARTONS">Cartons</option>
                                        <option value="ROLLS">Rolls</option>
                                        <option value="PALLETS">Pallets</option>
                                        <option value="CRATES">Crates</option>
                                        <option value="BOXES">Boxes</option>
                                        <option value="DRUMS">Drums</option>
                                        <option value="BAGS">Bags</option>
                                        <option value="BUNDLES">Bundles</option>
                                        <option value="CONTAINERS">Containers</option>
                                        <option value="PIECES">Pieces</option>
                                        <option value="BALES">Bales</option>
                                    </select>
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <label>Gross Weight</label>
                                    <input type="text" placeholder="Enter Gross weight" wire:model="shipmentGrossWeight"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                </div>
                                <div class="flex flex-col space-y-2" wire:ignore>
                                    <label>Type Of Gross Weight</label>
                                    <select id="typeOfGrossWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value=""></option>
                                        <option value="KGS">KGS</option>
                                    </select>
                                </div>

                                <!-- Weight Info -->
                                <div class="flex flex-col space-y-2">
                                    <label>Volume Weight</label>
                                    <input type="text" wire:model="shipmentVolumeWeight" placeholder="Enter Gross weight"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                </div>
                                <div class="flex flex-col space-y-2" wire:ignore>
                                    <label>Type Of Volume Weight</label>
                                    <select id="typeOfVolumeWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value=""></option>
                                        <option value="KGS">KGS</option>
                                    </select>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <label>Volume </label>
                                    <input type="text" wire:model="shipmentVolume" placeholder="Enter Gross weight"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                </div>
                                <div class="flex flex-col space-y-2" wire:ignore>
                                    <label>Type Of Volume </label>
                                    <select id="typeOfVolume" class="block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value=""></option>
                                        <option value="KGS">KGS</option>
                                    </select>
                                </div>
                                <!-- Volume Info -->

                                <div class="flex flex-col space-y-2">
                                    <label>Chargeable Weight</label>
                                    <input type="text" placeholder="Enter Chargeable Weight" wire:model="shipmentChargableWeight"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                </div>

                                <div class="flex flex-col space-y-2">
                                    <label>HS Code</label>
                                    <input type="text" placeholder="Enter HS Code" wire:model="shipmentHsCode"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                </div>
                                <div></div>
                                <div class="flex flex-col space-y-2 col-span-2">
                                    <label>Remarks</label>
                                    <textarea placeholder="Enter remarks" rows="3" wire:model="shipmentContainerRemarks"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                                </div>
                                <div class="flex flex-col space-y-2 col-span-2">
                                    <label>HS Description</label>
                                    <textarea placeholder="Enter Hs Description" rows="3" wire:model="shipmentHsCodeDesc"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
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
                            No Of Packages
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
                    @forelse($shipment->container as $c)
                    <tr>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $loop->iteration  * 10 }}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            @if($c->jobContainer)
                            {{$c->jobContainer->containers['containerNo'] ?? ''}}
                            @else
                            {{$c->containersData['shipmentContainerNo'] ?? ''}}
                            @endif
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$c->containersData['shipmentNoOfPackages']}}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$c->containersData['shipmentGrossWeight'] ?? ''}} {{$c->containersData['shipmentTypeOfGrossWeight'] ?? ''}}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$c->containersData['shipmentNoOfPackages'] ?? ''}}
                        </td>

                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            <a href="{{ url('view-shipment/' . $shipment->id . '/container-shipment/' . $c->id) }}"
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
                                <img src="{{ asset('images/nodata.svg') }}"
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
        <div class="bg-gray-400 rounded-t-lg">
            <div class="">
                <div x-data="{ open: false }" @close-modal.window="open = false"
                    x-ref="modalContent">
                    <div class="flex items-center justify-between p-3 ">
                        <div class="flex-1"></div> <!-- Spacer kiri -->
                        <p class="font-bold  text-center">TRANSACTION</p>
                        <div class="flex-1 flex justify-end gap-2">
                            <a href="{{route('saleInvoice', ['shipmentId' => $shipment->id])}}" class="py-2 px-3 bg-green-600 text-white rounded-lg text-sm">Print Invoice</a>
                            <a href="{{route('purchaseInvoice', ['shipmentId' => $shipment->id])}}" class="py-2 px-3 bg-red-600 text-white rounded-lg text-sm">Print PI</a>
                            <button @click="open = true; $wire.refreshTransaction()" class="py-2 px-3 bg-blue-600 text-white rounded-lg text-sm">Add Cost</button>

                        </div>
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
                                :id="$shipment->id"
                                :key="'transaction' . $shipment->id . '-' . $refreshKey" />
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
                            Action
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
                    @forelse($shipment->shipmentTransaction as $transaction)
                    <tr>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $loop->iteration }}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            <div x-data class="gap-2">
                                <button
                                    class="px-3 py-2 bg-red-600 text-white rounded-full hover:scale-105 hover:bg-red-700 transition-transform"
                                    @click="
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You won\'t be able to revert this!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d8',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, Keep it',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $wire.confirmDelete({{ $transaction->id }});
                        }
                    })
                ">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button
                                    type="button"
                                    wire:click="editTransaction({{ $shipment->id }}, {{ $transaction->id }})"
                                    class="px-3 py-2 bg-blue-500 rounded-full text-white hover:bg-blue-600 transition transform hover:scale-105">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                            </div>
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $transaction->description }}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $transaction->unit }}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$transaction->transactionClient->name ?? ''}}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ $transaction->quantity }} x {{number_format($transaction->sfcyamount, 2, ',', '.')}}x{{$transaction->srate}}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{ number_format($transaction->samountidr, 2, ',', '.') }}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$transaction->sdrcr}}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$transaction->transactionVendor->name ?? '-'}}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            @if($transaction->camountidr && $transaction->camountidr != 0)
                            {{ $transaction->quantity }} x {{ number_format($transaction->cfcyamount, 2, ',', '.') }}
                            x {{$transaction->crate}}
                            @else
                            -
                            @endif
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            @if($transaction->camountidr && $transaction->camountidr != 0)
                            {{ number_format($transaction->camountidr, 2, ',', '.') }}
                            @else
                            -
                            @endif
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$transaction->cdrcr}}
                        </td>
                        <td scope="col" class="px-6 py-4 uppercase whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                            {{$transaction->freight}}
                        </td>
                        <td scope="col" class="px-6 py-4 whitespace-nowrap text-sm font-medium font-bold {{ $transaction->gp < 0 ? 'text-red-500' : 'text-green-700' }}">
                            {{$transaction->SamountgpFormatted}}
                        </td>
                    </tr>
                    @empty
                    <tr wire:loading.remove>
                        <td colspan="14" class="py-12 text-center">
                            <div class="flex flex-col text-center items-center justify-center">
                                <img src="{{ asset('images/nodata.svg') }}"
                                    alt="No dataShipments illustration"
                                    class="w-64 h-48 mb-4 opacity-75  dark:opacity-50">
                                <p class="text-md font-medium text-gray-600 dark:text-neutral-300">
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
            <x-confirm-delete />
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
    @if($isEditing)
    <div x-data="{ show: false }">
        <div wire:loading
            wire:target="saveTransaction,editTransaction"
            class="fixed inset-0 bg-black bg-opacity-30 z-50 items-center justify-center">
            <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
            <span class="ml-4 text-white text-lg font-medium">TUNGGU SEBENTAR...</span>
        </div> {{-- Backdrop --}}
        <div
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn"
            x-data="{ show: false }"
            x-init="setTimeout(() => show = true, 50)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-cloak>

            <div
                class="bg-white rounded-2xl w-full  mx-4 shadow-2xl transform"
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4" x-cloak>
                {{-- Modal Header --}}
                <div class="flex justify-between items-center p-4 border-b">
                    <h2 class="text-lg font-semibold text-gray-800">Costing</h2>
                    <button wire:click="closeEditTransaction"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Form --}}
                <div wire:ignore>
                    <livewire:shipment.transaction.edit-transaction
                        :id="$shipment->id"
                        :transactionId="$editingTransactionId"
                        :key="'transaction-' . $shipment->id . '-' . $refreshKey" />
                </div>

            </div>
        </div>
    </div>
    @endif
</div>
@push('script')
@script()
<script>
    window.initContainerSelect2 = () => {
        // Configuration for all select elements
        const selectConfigs = [{
                sel: '#parentContainer',
                model: 'parentContainer',
                placeholder: 'Select Parent'
            }, {
                sel: '#typeOfPackages',
                model: 'shipmentTypeOfPackages',
                placeholder: 'Select Package Type'
            },
            {
                sel: '#typeOfGrossWeight',
                model: 'shipmentTypeOfGrossWeight',
                placeholder: 'Select Weight Unit'
            },
            {
                sel: '#typeOfVolume',
                model: 'shipmentVolume',
                placeholder: 'Select Volume  Unit'
            },
            {
                sel: '#typeOfVolumeWeight',
                model: 'shipmentTypeOfVolumeWeight',
                placeholder: 'Select Volume Weight Unit'
            }, {
                sel: '#typeNetOfWeight',
                model: 'shipmentTypeNetOfWeight',
                placeholder: 'Select Net Weight Unit'
            }, {
                sel: '#typeOfTotalWeight',
                model: 'shipmentTypeOfTotalWeight',
                placeholder: 'Select Total Weight Unit'
            }
        ];

        selectConfigs.forEach(({
            sel,
            model,
            placeholder
        }) => {
            const $el = $(sel);
            if (!$el.length) return;

            // Destroy existing Select2 if it exists
            if ($el.hasClass('select2-hidden-accessible')) {
                $el.select2('destroy');
            }

            // Initialize Select2 with modal-friendly settings
            $el.select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%',
                theme: 'tailwindcss-3',
                dropdownParent: $el.closest('.fixed'),
                dropdownAutoWidth: false,
                escapeMarkup: function(markup) {
                    return markup;
                },
                // Prevent Select2 from focusing on search input
                selectOnClose: false,
                // Prevent dropdown from closing modal
                closeOnSelect: true
            });

            // IMPORTANT: Remove all previous event handlers to prevent duplicates
            $el.off('select2:select.container select2:unselect.container select2:open.container select2:close.container');

            // Handle Select2 events with debouncing to prevent multiple triggers
            let updateTimeout;
            $el.on('select2:select.container select2:unselect.container', function(e) {
                e.stopPropagation(); // Prevent event bubbling

                clearTimeout(updateTimeout);
                updateTimeout = setTimeout(() => {
                    const value = $(this).val();
                    if (typeof $wire !== 'undefined' && $wire[model] !== undefined) {
                        // Use Livewire's set method without triggering full refresh
                        $wire.set(model, value, false); // false = don't trigger refresh
                    }
                    console.log(`${model} changed to:`, value);
                }, 100);
            });

            // Prevent modal from closing when dropdown opens
            $el.on('select2:open.container', function(e) {
                e.stopPropagation();
                // Ensure dropdown is positioned correctly
                const dropdown = $('.select2-dropdown');
                // dropdown.css('z-index', '9999');
            });

            // Handle dropdown close
            $el.on('select2:close.container', function(e) {
                e.stopPropagation();
            });

            // Sync with Livewire property if it exists (without triggering events)
            if (typeof $wire !== 'undefined' && $wire[model] !== undefined) {
                $el.val($wire[model]).trigger('change.select2');
            }
        });
    };

    // Initialize when document is ready
    $(document).ready(function() {
        // Initialize Select2 when modal opens with proper timing
        $(document).on('click', '[x-on\\:click="openCreateContainer = true"]', function(e) {
            e.stopPropagation();
            // Wait for modal to fully render
            setTimeout(function() {
                if ($('.fixed').is(':visible')) {
                    window.initContainerSelect2();
                }
            }, 400); // Increased delay for better reliability
        });

        // Enhanced Alpine.js integration
        document.addEventListener('alpine:init', () => {
            Alpine.data('containerForm', () => ({
                openCreateContainer: false,
                init() {
                    this.$watch('openCreateContainer', (value) => {
                        if (value) {
                            // Delay initialization until modal is fully rendered
                            this.$nextTick(() => {
                                setTimeout(() => {
                                    window.initContainerSelect2();
                                }, 300);
                            });
                        } else {
                            // Clean up Select2 when modal closes
                            this.$nextTick(() => {
                                $('.select2-hidden-accessible').each(function() {
                                    $(this).select2('destroy');
                                });
                            });
                        }
                    });
                }
            }));
        });
    });

    // Enhanced Livewire hooks with better error handling
    if (typeof Livewire !== 'undefined') {
        // Preserve Select2 state during Livewire updates
        let preservedValues = {};
        let isFormReset = false;

        // Before Livewire request (preserve state)
        Livewire.hook('message.sent', (message, component) => {
            // Check if this is a form reset/creation request
            isFormReset = message.updates.some(update =>
                update.type === 'callMethod' &&
                (update.payload.method === 'createContainer' ||
                    update.payload.method === 'resetContainerFields' ||
                    update.payload.method === 'cancelContainer')
            );

            if (!isFormReset) {
                preservedValues = {};
                ['#containerType', '#typeOfPackages', '#typeOfGrossWeight',
                    '#typeOfVolumeWeight', '#typeNetOfWeight', '#typeOfTotalWeight'
                ].forEach(sel => {
                    const $el = $(sel);
                    if ($el.length && $el.hasClass('select2-hidden-accessible')) {
                        preservedValues[sel] = $el.val();
                    }
                });
            }
        });

        // After Livewire response (restore state or reset)
        Livewire.hook('message.processed', (message, component) => {
            setTimeout(() => {
                // Only reinitialize if modal is still open
                if ($('.fixed').is(':visible')) {
                    window.initContainerSelect2();

                    if (isFormReset) {
                        // Reset all Select2 values to empty
                        ['#containerType', '#typeOfPackages', '#typeOfGrossWeight',
                            '#typeOfVolumeWeight', '#typeNetOfWeight', '#typeOfTotalWeight'
                        ].forEach(sel => {
                            const $el = $(sel);
                            if ($el.length && $el.hasClass('select2-hidden-accessible')) {
                                $el.val(null).trigger('change.select2');
                            }
                        });
                    } else {
                        // Restore preserved values
                        Object.keys(preservedValues).forEach(sel => {
                            const $el = $(sel);
                            if ($el.length && preservedValues[sel]) {
                                $el.val(preservedValues[sel]).trigger('change.select2');
                            }
                        });
                    }
                }
                preservedValues = {};
                isFormReset = false;
            }, 200);
        });

        // Handle specific element updates
        Livewire.hook('element.updated', (el, component) => {
            if (el.matches('select') || el.querySelector('select')) {
                setTimeout(() => {
                    if ($('.fixed').is(':visible')) {
                        window.initContainerSelect2();
                    }
                }, 150);
            }
        });

        // Listen for close-create-container event to reset Select2
        Livewire.on('close-create-container', () => {
            setTimeout(() => {
                ['#containerType', '#typeOfPackages', '#typeOfGrossWeight',
                    '#typeOfVolumeWeight', '#typeNetOfWeight', '#typeOfTotalWeight'
                ].forEach(sel => {
                    const $el = $(sel);
                    if ($el.length && $el.hasClass('select2-hidden-accessible')) {
                        $el.val(null).trigger('change.select2');
                    }
                });
            }, 100);
        });
    }

    // Manual initialization function with safety checks
    window.forceInitContainerSelect2 = () => {
        setTimeout(() => {
            if ($('.fixed').is(':visible')) {
                window.initContainerSelect2();
            }
        }, 100);
    };

    // Global click handler to prevent modal closing
    $(document).on('click', '.select2-dropdown', function(e) {
        e.stopPropagation();
    });

    // Prevent modal background clicks when Select2 is open
    $(document).on('select2:open', function(e) {
        $('.fixed.inset-0.bg-gray-500').css('pointer-events', 'none');
    });

    $(document).on('select2:close', function(e) {
        $('.fixed.inset-0.bg-gray-500').css('pointer-events', 'auto');
    });
</script>
@endscript
@endpush

<script>
    window.addEventListener('swal', event => {
        let data;
        // Handle both array and object
        if (Array.isArray(event.detail)) {
            data = event.detail[0]; // Ambil element pertama jika array
        } else {
            data = event.detail; // Gunakan langsung jika object
        }
        // console.log('Processed data:', data);
        if (data && data.title) {
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                confirmButtonText: data.confirmButtonText || 'OK'
            });
        } else {
            // console.error('Invalid data structure:', data);
        }
    });
    // Fungsi untuk restore scroll position
    function restoreScrollPosition() {
        const savedPosition = sessionStorage.getItem('scrollPosition');
        if (savedPosition) {
            const scrollTo = parseInt(savedPosition);

            // Cek apakah halaman sudah cukup tinggi untuk di-scroll
            const checkAndScroll = () => {
                if (document.body.scrollHeight > scrollTo) {
                    window.scrollTo({
                        top: scrollTo,
                        behavior: 'auto'
                    });
                    sessionStorage.removeItem('scrollPosition');
                    return true;
                }
                return false;
            };

            // Coba scroll langsung
            if (!checkAndScroll()) {
                // Kalau belum bisa, tunggu sebentar lagi
                setTimeout(() => {
                    if (!checkAndScroll()) {
                        // Terakhir, tunggu sampai semua image/content load
                        const images = document.querySelectorAll('img');
                        let loadedImages = 0;

                        if (images.length === 0) {
                            checkAndScroll();
                        } else {
                            images.forEach(img => {
                                if (img.complete) {
                                    loadedImages++;
                                } else {
                                    img.onload = () => {
                                        loadedImages++;
                                        if (loadedImages === images.length) {
                                            checkAndScroll();
                                        }
                                    };
                                }
                            });

                            if (loadedImages === images.length) {
                                checkAndScroll();
                            }
                        }
                    }
                }, 200);
            }
        }
    }

    // Jalankan setelah DOM ready
    document.addEventListener('DOMContentLoaded', restoreScrollPosition);

    // Backup: jalankan juga setelah window load
    window.addEventListener('load', function() {
        // Cek lagi kalau belum ter-scroll
        setTimeout(restoreScrollPosition, 100);
    });

    // Simpan scroll position sebelum navigasi
    function saveScrollPosition() {
        sessionStorage.setItem('scrollPosition', window.scrollY);
    }
    // ===== KODE YANG BENAR =====
</script>