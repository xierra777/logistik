<div class="p-6 max mx-auto [&::-webkit-scrollbar]:hidden">
    <form action="" wire:submit="submitForm">
        <div>
            <div x-data="{
    step: @entangle('step'),
    type_job: @entangle('type_job') ,
    hazardousType :@entangle('hazardousType'),
    init() {
     this.$watch('type_job', value => {
                console.log('Job type changed to:', value);
                this.$nextTick(() => {
                    window.PortSelect2.init('.port-select', value);
                });
            });
        this.$nextTick(() => window.reinitSelect2());
        this.$nextTick(() => window.PortSelect2.init());

        this.$watch('step', () => {
               this.$nextTick(() => window.reinitSelect2());
               this.$nextTick(() => {
                    window.PortSelect2.init('.port-select', this.type_job);
                });            
        });
          this.$nextTick(() => {
                window.PortSelect2.init('.port-select', this.type_job);
            });
        
    }
}"
                x-init="init()" class="p-6 space-y-6">

                <!-- Step Indicators -->
                <div class="flex justify-center space-x-4 text-sm font-medium">
                    @for($i = 1; $i <= 4; $i++)
                        <div class="px-4 py-2 rounded @if($step === $i) bg-blue-600 text-white @else bg-gray-200 text-gray-600 @endif">
                        Step {{ $i }}
                </div>
                @endfor
            </div>

            <!-- STEP 1: Pilih Tipe Job -->
            <div x-show="step === 1" x-transition x-cloak>
                <h2 class="text-lg font-semibold mb-3">Pilih Tipe Job & Client</h2>
                <div class="flex flex-col space-y-3 rounded-md mb-4 w-full" wire:ignore>
                    <label class="font-bold">Client</label>
                    <select wire:model="client_id" id="client_id" class="border p-2 rounded ">
                        <option value="">Pilih Client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach([
                    'ocean_fcl_export' => 'Ocean FCL Export',
                    'ocean_fcl_import' => 'Ocean FCL Import',
                    'ocean_lcl_export' => 'Ocean LCL Export',
                    'ocean_lcl_import' => 'Ocean LCL Import',
                    'air_outbound' => 'Air Outbound',
                    'air_inbound' => 'Air Inbound',
                    'trucking' => 'Trucking',
                    'logistics' => 'Logistics',
                    'domestic_transportation' => 'Domestic Transportation'
                    ] as $key => $label)
                    <label class="flex w-full items-center space-x-2 cursor-pointer border p-2 rounded border-gray-400 hover:bg-gray-100 rounded-lg">
                        <input type="radio" value="{{ $key }}" x-model="type_job"

                            class="text-blue-600">
                        <span>{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
                @error('type_job')<div class="text-red-500 mt-2">{{ $message }}</div>@enderror
                <div class="flex justify-between ">
                    <a href="{{route('listJob')}}" class="mt-4 px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                        back
                    </a>
                    <button wire:click.prevent="nextStep" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Lanjut
                    </button>
                </div>
            </div>
            @if($step === 2)
            <!-- STEP 2: Isi Detail Job -->
            <div x-show="step === 2" x-transition x-cloak>
                <h2 class="text-lg font-semibold mb-3">Detail Job: {{ strtoupper(str_replace('_', ' ', $type_job)) }}</h2>

                @switch($type_job)
                @case('ocean_fcl_export')
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-3 mb-3 gap-x-4">
                        {{-- Client --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Client</label>
                            <input
                                type="text"
                                value="{{ $this->clientName }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none "
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Job Type --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Job Type</label>
                            <input
                                type="text"
                                value="{{ strtoupper(str_replace('_', ' ', $type_job)) }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none">
                            @error('seal_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Customer Code --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Customer Code No</label>
                            <input
                                type="text"
                                wire:model="customerCodeJob"
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none ">
                            @error('customerCodeJob')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 italic mt-1">Note: adjustable as needed</p>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Job</label>
                            <input type="text" wire:model="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('job_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MBL No</label>
                            <input type="text" wire:model="jobBillLadingNo" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MBL Date</label>
                            <input type="date" wire:model="jobBillLadingDate" placeholder="Enter Shipment ID" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" wire:ignore>
                            <label for="carrier">Carrier</label>
                            <select name="carrier" id="carrier" wire:model="carrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($carriers as $cr)
                                @if(in_array('carrier', $cr->roles))
                                <option value="{{ $cr->id }}">{{ $cr->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="deliveryAgent">Delivery Agent</label>
                            <select name="deliveryAgent" id="deliveryAgent_export" wire:model="deliveryAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($dagentsJob as $da)
                                <option value="{{ $da->id }}">{{ $da->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <div class="" wire:ignore>
                                <label for="Services_type">Services Type</label>
                                <select name="" id="servicesType" wire:model="servicesType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Services</option>
                                    <option value="CY/CY">CY/CY</option>
                                    <option value="CFS/CFS">CFS/CFS</option>
                                    <option value="CFS/CY">CFS/CY</option>
                                    <option value="CY/CFS">CY/CFS</option>
                                    <option value="CY/DOOR">CY/DOOR</option>
                                    <option value="DOOR/CY">DOOR/CY</option>
                                </select>
                            </div>
                            <div class="mb-4" wire:ignore>
                                <label for="incoTerms">Inco Terms</label>
                                <select name="incoTerms" id="incoTerms" wire:model="incoTerms" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Terms</option>
                                    <option value="FOB">FOB</option>
                                    <option value="CFR">CFR</option>
                                    <option value="CIF">CIF</option>
                                    <option value="CPT">CPT</option>
                                    <option value="CIP">CIP</option>
                                    <option value="FAS">FAS</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Mother Vessel -->
                        <div class=" mb-4">
                            <label for=" flightVesselName">Vessel Name</label>
                            <input type="text" id="flightVesselName" name="flightVesselName" wire:model="flightVesselName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="flightVesselNo">Voyage</label>
                            <input type="text" id="flightVesselNo" name="flightVesselNo" wire:model="flightVesselNo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Feeder Vessel -->
                        <div class="mb-4" hidden>
                            <label for="ocean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="ocean_vessel_feeder" name="ocean_vessel_feeder" wire:model="ocean_vessel_feeder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('ocean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <!-- ETA -->
                            <div class="">
                                <label for="estimearrival">ETA / Estimate Time Arrival</label>
                                <input type="datetime-local" id=" estimearrival" name="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimearrival')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ETD -->
                            <div class="">
                                <label for="estimedelivery">ETD / Estimate Time Departure</label>
                                <input type="datetime-local" id=" estimedelivery" name="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimedelivery')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="payableAtJob">Payable at</label>
                            <input type="text" id="payableAtJob" name="payableAtJob" wire:model="payableAtJob" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('payableAtJob')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div wire:ignore>
                            <label for="freightTypeJob">Freight</label>
                            <select name="freightTypeJob" id="freightTypeJob" wire:model="freightTypeJob" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="collect">Collect</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                        <div class="" wire:ignore>
                            <label for="cross_trade">Cross trade</label>
                            <select name="" id="cross_trade" wire:model="cross_trade" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 border rounded-md p-4 border-1 border-gray-300 mt-5">
                        <div class="port-container" data-model="port_of_loading" data-radio-name="i nputTypeLoading" wire:ignore wire:change="port_of_loading">
                            <h2 class="text-lg font-semibold">Port Of Loading / POL</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeLoading" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeLoading"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_loading"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_loading" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>

                        <div class="port-container" data-model="port_of_final" data-radio-name="inputTypeFinal" wire:ignore wire:change="port_of_final">
                            <h2 class="text-lg font-semibold">Port Of Final / POF</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeFinal" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeFinal"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_final"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_final" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore wire:change="place_of_receipt">
                            <h2 class="text-lg font-semibold">Place Of Receipts <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="port_of_receipt" data-radio-name="inputTypePReceipt" wire:ignore wire:change="port_of_receipt">
                            <h2 class="text-lg font-semibold">Port Of Receipt / POR <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypePReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypePReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Discharge -->
                        <div class="port-container" data-model="port_of_discharge" data-radio-name="inputTypeDischarge" wire:ignore>
                            <h2 class="text-lg font-semibold">Port Of Discharge / POD <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDischarge" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDischarge"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_discharge"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_discharge" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Delivery -->
                        <div class="port-container" data-model="place_of_delivery" data-radio-name="inputTypeDelivery" wire:ignore>
                            <h2 class="text-lg font-semibold">Place Of Delivery <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDelivery" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDelivery"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_delivery"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_delivery" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 ">
                        <div>
                            <label for="remarksJobDetailJobs">Remarks</label>
                            <textarea type="text" id="remarksJobDetailJobs" name="remarksJobDetailJobs" wire:model="remarksJobDetailJobs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div wire:ignore>
                            <div>
                                <label for="jobEmployee">Employee</label>
                                <select name="jobEmployee" id="jobEmployee" wire:model="jobEmployee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    @foreach($employe as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="hazardousType">Hazardous</label>
                                <select name="hazardousType" id="hazardousType" x-model="hazardousType" wire:model="hazardousType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                        </div>
                        <template x-if="hazardousType === 'yes'">
                            <div wire:ignore x-init="init()">
                                <label for="hazardousClassType">Harzadous Class</label>
                                <select name="hazardousType" id="hazardousClassType" wire:model="hazardousClassType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="class1">Class1</option>
                                    <option value="class2">Class2</option>
                                    <option value="class3">Class3</option>
                                    <option value="class4">Class4</option>
                                    <option value="class5">Class5</option>
                                    <option value="class6">Class6</option>
                                    <option value="class7">Class7</option>
                                    <option value="class8">Class8</option>
                                    <option value="class9">class9</option>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>
                @break
                @case('ocean_fcl_import')
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-3 mb-3 gap-x-4">
                        {{-- Client --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Client</label>
                            <input
                                type="text"
                                value="{{ $this->clientName }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none "
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Job Type --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Job Type</label>
                            <input
                                type="text"
                                value="{{ strtoupper(str_replace('_', ' ', $type_job)) }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none">
                            @error('seal_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Customer Code --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Customer Code No</label>
                            <input
                                type="text"
                                wire:model="customerCodeJob"
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none ">
                            @error('customerCodeJob')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 italic mt-1">Note: adjustable as needed</p>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Job</label>
                            <input type="text" wire:model="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('job_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MBL No</label>
                            <input type="text" wire:model="jobBillLadingNo" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MBL Date</label>
                            <input type="date" wire:model="jobBillLadingDate" placeholder="Enter Shipment ID" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" wire:ignore>
                            <label for="carrier">Carrier</label>
                            <select name="carrier" id="carrier" wire:model="carrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($carriers as $cr)
                                @if(in_array('carrier', $cr->roles))
                                <option value="{{ $cr->id }}">{{ $cr->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="originAgent_import">Origin Agent</label>
                            <select name="originAgent_import" id="originAgent_import" wire:model="originAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($dagentsJob as $da)
                                <option value="{{ $da->id }}">{{ $da->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <div class="" wire:ignore>
                                <label for="Services_type">Services Type</label>
                                <select name="" id="servicesType" wire:model="servicesType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Services</option>
                                    <option value="CY/CY">CY/CY</option>
                                    <option value="CFS/CFS">CFS/CFS</option>
                                    <option value="CFS/CY">CFS/CY</option>
                                    <option value="CY/CFS">CY/CFS</option>
                                    <option value="CY/DOOR">CY/DOOR</option>
                                    <option value="DOOR/CY">DOOR/CY</option>
                                </select>
                            </div>
                            <div class="mb-4" wire:ignore>
                                <label for="incoTerms">Inco Terms</label>
                                <select name="incoTerms" id="incoTerms" wire:model="incoTerms" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Terms</option>
                                    <option value="FOB">FOB</option>
                                    <option value="CFR">CFR</option>
                                    <option value="CIF">CIF</option>
                                    <option value="CPT">CPT</option>
                                    <option value="CIP">CIP</option>
                                    <option value="FAS">FAS</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Mother Vessel -->
                        <div class=" mb-4">
                            <label for=" flightVesselName">Vessel Name</label>
                            <input type="text" id="flightVesselName" name="flightVesselName" wire:model="flightVesselName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="flightVesselNo">Voyage</label>
                            <input type="text" id="flightVesselNo" name="flightVesselNo" wire:model="flightVesselNo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Feeder Vessel -->
                        <div class="mb-4" hidden>
                            <label for="ocean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="ocean_vessel_feeder" name="ocean_vessel_feeder" wire:model="ocean_vessel_feeder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('ocean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <!-- ETA -->
                            <div class="">
                                <label for="estimearrival">ETA / Estimate Time Arrival</label>
                                <input type="datetime-local" id=" estimearrival" name="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimearrival')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ETD -->
                            <div class="">
                                <label for="estimedelivery">ETD / Estimate Time Departure</label>
                                <input type="datetime-local" id=" estimedelivery" name="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimedelivery')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="payableAtJob">Payable at</label>
                            <input type="text" id="payableAtJob" name="payableAtJob" wire:model="payableAtJob" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('payableAtJob')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div wire:ignore>
                            <label for="freightTypeJob">Freight</label>
                            <select name="freightTypeJob" id="freightTypeJob" wire:model="freightTypeJob" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="collect">Collect</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                        <div class="" wire:ignore>
                            <label for="cross_trade">Cross trade</label>
                            <select name="" id="cross_trade" wire:model="cross_trade" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 border rounded-md p-4 border-1 border-gray-300 mt-5">
                        <div class="port-container" data-model="port_of_loading" data-radio-name="i nputTypeLoading" wire:ignore wire:change="port_of_loading">
                            <h2 class="text-lg font-semibold">Port Of Loading / POL</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeLoading" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeLoading"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_loading"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_loading" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>

                        <div class="port-container" data-model="port_of_final" data-radio-name="inputTypeFinal" wire:ignore wire:change="port_of_final">
                            <h2 class="text-lg font-semibold">Port Of Final / POF</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeFinal" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeFinal"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_final"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_final" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore wire:change="place_of_receipt">
                            <h2 class="text-lg font-semibold">Place Of Receipts <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="port_of_receipt" data-radio-name="inputTypePReceipt" wire:ignore wire:change="port_of_receipt">
                            <h2 class="text-lg font-semibold">Port Of Receipt / POR <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypePReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypePReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Discharge -->
                        <div class="port-container" data-model="port_of_discharge" data-radio-name="inputTypeDischarge" wire:ignore>
                            <h2 class="text-lg font-semibold">Port Of Discharge / POD <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDischarge" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDischarge"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_discharge"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_discharge" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Delivery -->
                        <div class="port-container" data-model="place_of_delivery" data-radio-name="inputTypeDelivery" wire:ignore>
                            <h2 class="text-lg font-semibold">Place Of Delivery <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDelivery" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDelivery"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_delivery"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_delivery" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 ">
                        <div>
                            <label for="remarksJobDetailJobs">Remarks</label>
                            <textarea type="text" id="remarksJobDetailJobs" name="remarksJobDetailJobs" wire:model="remarksJobDetailJobs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div wire:ignore>
                            <div>
                                <label for="jobEmployee">Employee</label>
                                <select name="jobEmployee" id="jobEmployee" wire:model="jobEmployee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    @foreach($employe as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="hazardousType">Hazardous</label>
                                <select name="hazardousType" id="hazardousType" x-model="hazardousType" wire:model="hazardousType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                        </div>
                        <template x-if="hazardousType === 'yes'">
                            <div wire:ignore x-init="init()">
                                <label for="hazardousClassType">Harzadous Class</label>
                                <select name="hazardousType" id="hazardousClassType" wire:model="hazardousClassType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="class1">Class1</option>
                                    <option value="class2">Class2</option>
                                    <option value="class3">Class3</option>
                                    <option value="class4">Class4</option>
                                    <option value="class5">Class5</option>
                                    <option value="class6">Class6</option>
                                    <option value="class7">Class7</option>
                                    <option value="class8">Class8</option>
                                    <option value="class9">class9</option>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>
                @break

                @case('trucking')
                @case('logistics')
                @case('domestic_transportation')
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-3 mb-3 gap-x-4">
                        {{-- Client --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Client</label>
                            <input
                                type="text"
                                value="{{ $this->clientName }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none "
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Job Type --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Job Type</label>
                            <input
                                type="text"
                                value="{{ strtoupper(str_replace('_', ' ', $type_job)) }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none">
                            @error('seal_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Customer Code --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Customer Code No</label>
                            <input
                                type="text"
                                wire:model="customerCodeJob"
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none ">
                            @error('customerCodeJob')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 italic mt-1">Note: adjustable as needed</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Job</label>
                            <input type="text" wire:model="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('job_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MAWB No</label>
                            <input type="text" wire:model="jobBillLadingNo" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MAWB Date</label>
                            <input type="date" wire:model="jobBillLadingDate" placeholder="Enter Shipment ID" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" wire:ignore>
                            <label for="airline">Flight</label>
                            <select name="airline" id="airline" wire:model="carrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($airlines as $ai)
                                <option value="{{ $ai->id }}">{{ $ai->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>

                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <div class="" wire:ignore>
                                <label for="Services_type">Services Type</label>
                                <select name="" id="servicesType" wire:model="servicesType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Services</option>
                                    <option value="CY/CY">CY/CY</option>
                                    <option value="CFS/CFS">CFS/CFS</option>
                                    <option value="CFS/CY">CFS/CY</option>
                                    <option value="CY/CFS">CY/CFS</option>
                                    <option value="CY/DOOR">CY/DOOR</option>
                                    <option value="DOOR/CY">DOOR/CY</option>
                                </select>
                            </div>
                            <div class="mb-4" wire:ignore>
                                <label for="incoTerms">Inco Terms</label>
                                <select name="incoTerms" id="incoTerms" wire:model="incoTerms" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Terms</option>
                                    <option value="FOB">FOB</option>
                                    <option value="CFR">CFR</option>
                                    <option value="CIF">CIF</option>
                                    <option value="CPT">CPT</option>
                                    <option value="CIP">CIP</option>
                                    <option value="FAS">FAS</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Mother Vessel -->
                        <div class=" mb-4">
                            <label for=" flightVesselName">Flight Name</label>
                            <input type="text" id="flightVesselName" name="flightVesselName" wire:model="flightVesselName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="flightVesselNo">Flight No</label>
                            <input type="text" id="flightVesselNo" name="flightVesselNo" wire:model="flightVesselNo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Feeder Vessel -->
                        <div class="mb-4" hidden>
                            <label for="ocean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="ocean_vessel_feeder" name="ocean_vessel_feeder" wire:model="ocean_vessel_feeder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('ocean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <!-- ETA -->
                            <div class="">
                                <label for="estimearrival">ETA / Estimate Time Arrival</label>
                                <input type="datetime-local" id=" estimearrival" name="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimearrival')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ETD -->
                            <div class="">
                                <label for="estimedelivery">ETD / Estimate Time Departure</label>
                                <input type="datetime-local" id=" estimedelivery" name="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimedelivery')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="payableAtJob">Payable at</label>
                            <input type="text" id="payableAtJob" name="payableAtJob" wire:model="payableAtJob" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('payableAtJob')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div wire:ignore>
                            <label for="freightTypeJob">Freight</label>
                            <select name="freightTypeJob" id="freightTypeJob" wire:model="freightTypeJob" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="collect">Collect</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                        <div class="" wire:ignore>
                            <label for="cross_trade">Cross trade</label>
                            <select name="" id="cross_trade" wire:model="cross_trade" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 border rounded-md p-4 border-1 border-gray-300 mt-5">
                        <div class="port-container" data-model="port_of_loading" data-radio-name="i nputTypeLoading" wire:ignore wire:change="port_of_loading">
                            <h2 class="text-lg font-semibold">Port Of Loading / POL</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeLoading" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeLoading"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_loading"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_loading" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>

                        <div class="port-container" data-model="port_of_final" data-radio-name="inputTypeFinal" wire:ignore wire:change="port_of_final">
                            <h2 class="text-lg font-semibold">Port Of Final / POF</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeFinal" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeFinal"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_final"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_final" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore wire:change="place_of_receipt">
                            <h2 class="text-lg font-semibold">Place Of Receipts <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="port_of_receipt" data-radio-name="inputTypePReceipt" wire:ignore wire:change="port_of_receipt">
                            <h2 class="text-lg font-semibold">Port Of Receipt / POR <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypePReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypePReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Discharge -->
                        <div class="port-container" data-model="port_of_discharge" data-radio-name="inputTypeDischarge" wire:ignore>
                            <h2 class="text-lg font-semibold">Port Of Discharge / POD <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDischarge" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDischarge"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_discharge"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_discharge" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Delivery -->
                        <div class="port-container" data-model="place_of_delivery" data-radio-name="inputTypeDelivery" wire:ignore>
                            <h2 class="text-lg font-semibold">Place Of Delivery <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDelivery" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDelivery"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_delivery"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_delivery" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 ">
                        <div>
                            <label for="remarksJobDetailJobs">Remarks</label>
                            <textarea type="text" id="remarksJobDetailJobs" name="remarksJobDetailJobs" wire:model="remarksJobDetailJobs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div wire:ignore>
                            <div>
                                <label for="jobEmployee">Employee</label>
                                <select name="jobEmployee" id="jobEmployee" wire:model="jobEmployee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    @foreach($employe as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="hazardousType">Hazardous</label>
                                <select name="hazardousType" id="hazardousType" x-model="hazardousType" wire:model="hazardousType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                        </div>
                        <template x-if="hazardousType === 'yes'">
                            <div wire:ignore x-init="init()">
                                <label for="hazardousClassType">Harzadous Class</label>
                                <select name="hazardousType" id="hazardousClassType" wire:model="hazardousClassType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="class1">Class1</option>
                                    <option value="class2">Class2</option>
                                    <option value="class3">Class3</option>
                                    <option value="class4">Class4</option>
                                    <option value="class5">Class5</option>
                                    <option value="class6">Class6</option>
                                    <option value="class7">Class7</option>
                                    <option value="class8">Class8</option>
                                    <option value="class9">class9</option>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>
                @break
                @case('ocean_lcl_export')
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-3 mb-3 gap-x-4">
                        {{-- Client --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Client</label>
                            <input
                                type="text"
                                value="{{ $this->clientName }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none "
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Job Type --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Job Type</label>
                            <input
                                type="text"
                                value="{{ strtoupper(str_replace('_', ' ', $type_job)) }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none">
                            @error('seal_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Customer Code --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Customer Code No</label>
                            <input
                                type="text"
                                wire:model="customerCodeJob"
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none ">
                            @error('customerCodeJob')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 italic mt-1">Note: adjustable as needed</p>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Job</label>
                            <input type="text" wire:model="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('job_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MBL No</label>
                            <input type="text" wire:model="jobBillLadingNo" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MBL Date</label>
                            <input type="date" wire:model="jobBillLadingDate" placeholder="Enter Shipment ID" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" wire:ignore>
                            <label for="carrier">Carrier</label>
                            <select name="carrier" id="carrier" wire:model="carrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($carriers as $cr)
                                @if(in_array('carrier', $cr->roles))
                                <option value="{{ $cr->id }}">{{ $cr->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="deliveryAgent">Delivery Agent</label>
                            <select name="deliveryAgent" id="deliveryAgent_export" wire:model="deliveryAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($dagentsJob as $da)
                                <option value="{{ $da->id }}">{{ $da->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <div class="" wire:ignore>
                                <label for="Services_type">Services Type</label>
                                <select name="" id="servicesType" wire:model="servicesType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Services</option>
                                    <option value="CY/CY">CY/CY</option>
                                    <option value="CFS/CFS">CFS/CFS</option>
                                    <option value="CFS/CY">CFS/CY</option>
                                    <option value="CY/CFS">CY/CFS</option>
                                    <option value="CY/DOOR">CY/DOOR</option>
                                    <option value="DOOR/CY">DOOR/CY</option>
                                </select>
                            </div>
                            <div class="mb-4" wire:ignore>
                                <label for="incoTerms">Inco Terms</label>
                                <select name="incoTerms" id="incoTerms" wire:model="incoTerms" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Terms</option>
                                    <option value="FOB">FOB</option>
                                    <option value="CFR">CFR</option>
                                    <option value="CIF">CIF</option>
                                    <option value="CPT">CPT</option>
                                    <option value="CIP">CIP</option>
                                    <option value="FAS">FAS</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Mother Vessel -->
                        <div class=" mb-4">
                            <label for=" flightVesselName">Vessel Name</label>
                            <input type="text" id="flightVesselName" name="flightVesselName" wire:model="flightVesselName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="flightVesselNo">Voyage</label>
                            <input type="text" id="flightVesselNo" name="flightVesselNo" wire:model="flightVesselNo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Feeder Vessel -->
                        <div class="mb-4" hidden>
                            <label for="ocean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="ocean_vessel_feeder" name="ocean_vessel_feeder" wire:model="ocean_vessel_feeder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('ocean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <!-- ETA -->
                            <div class="">
                                <label for="estimearrival">ETA / Estimate Time Arrival</label>
                                <input type="datetime-local" id=" estimearrival" name="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimearrival')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ETD -->
                            <div class="">
                                <label for="estimedelivery">ETD / Estimate Time Departure</label>
                                <input type="datetime-local" id=" estimedelivery" name="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimedelivery')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="payableAtJob">Payable at</label>
                            <input type="text" id="payableAtJob" name="payableAtJob" wire:model="payableAtJob" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('payableAtJob')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div wire:ignore>
                            <label for="freightTypeJob">Freight</label>
                            <select name="freightTypeJob" id="freightTypeJob" wire:model="freightTypeJob" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="collect">Collect</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                        <div class="" wire:ignore>
                            <label for="cross_trade">Cross trade</label>
                            <select name="" id="cross_trade" wire:model="cross_trade" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 border rounded-md p-4 border-1 border-gray-300 mt-5">
                        <div class="port-container" data-model="port_of_loading" data-radio-name="i nputTypeLoading" wire:ignore wire:change="port_of_loading">
                            <h2 class="text-lg font-semibold">Port Of Loading / POL</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeLoading" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeLoading"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_loading"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_loading" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>

                        <div class="port-container" data-model="port_of_final" data-radio-name="inputTypeFinal" wire:ignore wire:change="port_of_final">
                            <h2 class="text-lg font-semibold">Port Of Final / POF</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeFinal" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeFinal"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_final"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_final" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore wire:change="place_of_receipt">
                            <h2 class="text-lg font-semibold">Place Of Receipts <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="port_of_receipt" data-radio-name="inputTypePReceipt" wire:ignore wire:change="port_of_receipt">
                            <h2 class="text-lg font-semibold">Port Of Receipt / POR <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypePReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypePReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Discharge -->
                        <div class="port-container" data-model="port_of_discharge" data-radio-name="inputTypeDischarge" wire:ignore>
                            <h2 class="text-lg font-semibold">Port Of Discharge / POD <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDischarge" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDischarge"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_discharge"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_discharge" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Delivery -->
                        <div class="port-container" data-model="place_of_delivery" data-radio-name="inputTypeDelivery" wire:ignore>
                            <h2 class="text-lg font-semibold">Place Of Delivery <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDelivery" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDelivery"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_delivery"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_delivery" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 ">
                        <div>
                            <label for="remarksJobDetailJobs">Remarks</label>
                            <textarea type="text" id="remarksJobDetailJobs" name="remarksJobDetailJobs" wire:model="remarksJobDetailJobs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div wire:ignore>
                            <div>
                                <label for="jobEmployee">Employee</label>
                                <select name="jobEmployee" id="jobEmployee" wire:model="jobEmployee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    @foreach($employe as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="hazardousType">Hazardous</label>
                                <select name="hazardousType" id="hazardousType" x-model="hazardousType" wire:model="hazardousType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                        </div>
                        <template x-if="hazardousType === 'yes'">
                            <div wire:ignore x-init="init()">
                                <label for="hazardousClassType">Harzadous Class</label>
                                <select name="hazardousType" id="hazardousClassType" wire:model="hazardousClassType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="class1">Class1</option>
                                    <option value="class2">Class2</option>
                                    <option value="class3">Class3</option>
                                    <option value="class4">Class4</option>
                                    <option value="class5">Class5</option>
                                    <option value="class6">Class6</option>
                                    <option value="class7">Class7</option>
                                    <option value="class8">Class8</option>
                                    <option value="class9">class9</option>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>
                @break
                @case('ocean_lcl_import')
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-3 mb-3 gap-x-4">
                        {{-- Client --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Client</label>
                            <input
                                type="text"
                                value="{{ $this->clientName }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none "
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Job Type --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Job Type</label>
                            <input
                                type="text"
                                value="{{ strtoupper(str_replace('_', ' ', $type_job)) }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none">
                            @error('seal_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Customer Code --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Customer Code No</label>
                            <input
                                type="text"
                                wire:model="customerCodeJob"
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none ">
                            @error('customerCodeJob')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 italic mt-1">Note: adjustable as needed</p>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Job</label>
                            <input type="text" wire:model="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('job_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MBL No</label>
                            <input type="text" wire:model="jobBillLadingNo" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MBL Date</label>
                            <input type="date" wire:model="jobBillLadingDate" placeholder="Enter Shipment ID" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" wire:ignore>
                            <label for="carrier">Carrier</label>
                            <select name="carrier" id="carrier" wire:model="carrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($carriers as $cr)
                                @if(in_array('carrier', $cr->roles))
                                <option value="{{ $cr->id }}">{{ $cr->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="originAgent">Origin Agent</label>
                            <select name="originAgent" id="originAgent_import" wire:model="originAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($ogentsJob as $oj)
                                <option value="{{ $oj->id }}">{{ $oj->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <div class="" wire:ignore>
                                <label for="Services_type">Services Type</label>
                                <select name="" id="servicesType" wire:model="servicesType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Services</option>
                                    <option value="CY/CY">CY/CY</option>
                                    <option value="CFS/CFS">CFS/CFS</option>
                                    <option value="CFS/CY">CFS/CY</option>
                                    <option value="CY/CFS">CY/CFS</option>
                                    <option value="CY/DOOR">CY/DOOR</option>
                                    <option value="DOOR/CY">DOOR/CY</option>
                                </select>
                            </div>
                            <div class="mb-4" wire:ignore>
                                <label for="incoTerms">Inco Terms</label>
                                <select name="incoTerms" id="incoTerms" wire:model="incoTerms" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Terms</option>
                                    <option value="FOB">FOB</option>
                                    <option value="CFR">CFR</option>
                                    <option value="CIF">CIF</option>
                                    <option value="CPT">CPT</option>
                                    <option value="CIP">CIP</option>
                                    <option value="FAS">FAS</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Mother Vessel -->
                        <div class=" mb-4">
                            <label for=" flightVesselName">Vessel Name</label>
                            <input type="text" id="flightVesselName" name="flightVesselName" wire:model="flightVesselName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="flightVesselNo">Voyage</label>
                            <input type="text" id="flightVesselNo" name="flightVesselNo" wire:model="flightVesselNo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Feeder Vessel -->
                        <div class="mb-4" hidden>
                            <label for="ocean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="ocean_vessel_feeder" name="ocean_vessel_feeder" wire:model="ocean_vessel_feeder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('ocean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <!-- ETA -->
                            <div class="">
                                <label for="estimearrival">ETA / Estimate Time Arrival</label>
                                <input type="datetime-local" id=" estimearrival" name="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimearrival')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ETD -->
                            <div class="">
                                <label for="estimedelivery">ETD / Estimate Time Departure</label>
                                <input type="datetime-local" id=" estimedelivery" name="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimedelivery')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="payableAtJob">Payable at</label>
                            <input type="text" id="payableAtJob" name="payableAtJob" wire:model="payableAtJob" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('payableAtJob')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div wire:ignore>
                            <label for="freightTypeJob">Freight</label>
                            <select name="freightTypeJob" id="freightTypeJob" wire:model="freightTypeJob" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="collect">Collect</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                        <div class="" wire:ignore>
                            <label for="cross_trade">Cross trade</label>
                            <select name="" id="cross_trade" wire:model="cross_trade" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 border rounded-md p-4 border-1 border-gray-300 mt-5">
                        <div class="port-container" data-model="port_of_loading" data-radio-name="i nputTypeLoading" wire:ignore wire:change="port_of_loading">
                            <h2 class="text-lg font-semibold">Port Of Loading / POL</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeLoading" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeLoading"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_loading"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_loading" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>

                        <div class="port-container" data-model="port_of_final" data-radio-name="inputTypeFinal" wire:ignore wire:change="port_of_final">
                            <h2 class="text-lg font-semibold">Port Of Final / POF</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeFinal" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeFinal"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_final"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_final" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore wire:change="place_of_receipt">
                            <h2 class="text-lg font-semibold">Place Of Receipts <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="port_of_receipt" data-radio-name="inputTypePReceipt" wire:ignore wire:change="port_of_receipt">
                            <h2 class="text-lg font-semibold">Port Of Receipt / POR <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypePReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypePReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Discharge -->
                        <div class="port-container" data-model="port_of_discharge" data-radio-name="inputTypeDischarge" wire:ignore>
                            <h2 class="text-lg font-semibold">Port Of Discharge / POD <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDischarge" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDischarge"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_discharge"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_discharge" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Delivery -->
                        <div class="port-container" data-model="place_of_delivery" data-radio-name="inputTypeDelivery" wire:ignore>
                            <h2 class="text-lg font-semibold">Place Of Delivery <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDelivery" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDelivery"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_delivery"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_delivery" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 ">
                        <div>
                            <label for="remarksJobDetailJobs">Remarks</label>
                            <textarea type="text" id="remarksJobDetailJobs" name="remarksJobDetailJobs" wire:model="remarksJobDetailJobs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div wire:ignore>
                            <div>
                                <label for="jobEmployee">Employee</label>
                                <select name="jobEmployee" id="jobEmployee" wire:model="jobEmployee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    @foreach($employe as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="hazardousType">Hazardous</label>
                                <select name="hazardousType" id="hazardousType" x-model="hazardousType" wire:model="hazardousType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                        </div>
                        <template x-if="hazardousType === 'yes'">
                            <div wire:ignore x-init="init()">
                                <label for="hazardousClassType">Harzadous Class</label>
                                <select name="hazardousType" id="hazardousClassType" wire:model="hazardousClassType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="class1">Class1</option>
                                    <option value="class2">Class2</option>
                                    <option value="class3">Class3</option>
                                    <option value="class4">Class4</option>
                                    <option value="class5">Class5</option>
                                    <option value="class6">Class6</option>
                                    <option value="class7">Class7</option>
                                    <option value="class8">Class8</option>
                                    <option value="class9">class9</option>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>
                @break
                @case('air_outbound')
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-3 mb-3 gap-x-4">
                        {{-- Client --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Client</label>
                            <input
                                type="text"
                                value="{{ $this->clientName }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none "
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Job Type --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Job Type</label>
                            <input
                                type="text"
                                value="{{ strtoupper(str_replace('_', ' ', $type_job)) }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none">
                            @error('seal_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Customer Code --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Customer Code No</label>
                            <input
                                type="text"
                                wire:model="customerCodeJob"
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none ">
                            @error('customerCodeJob')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 italic mt-1">Note: adjustable as needed</p>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Job</label>
                            <input type="text" wire:model="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('job_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MAWB No</label>
                            <input type="text" wire:model="jobBillLadingNo" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MAWB Date</label>
                            <input type="date" wire:model="jobBillLadingDate" placeholder="Enter Shipment ID" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div></div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>HAWB No</label>
                            <input type="text" wire:model="houseJobBillLadingNo" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('houseJobBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>HAWB Date</label>
                            <input type="date" wire:model="houseJobBillLadingDate" placeholder="Enter HBL DATE" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('houseJobBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" wire:ignore>
                            <label for="airline">Flight</label>
                            <select name="airline" id="airline" wire:model="carrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($airlines as $ai)
                                <option value="{{ $ai->id }}">{{ $ai->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="deliveryAgent">Delivery Agent</label>
                            <select name="deliveryAgent" id="deliveryAgent_export" wire:model="deliveryAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($ogentsJob as $oa)
                                @if(in_array('agent', $oa->roles))
                                <option value="{{ $oa->id }}">{{ $oa->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <div class="" wire:ignore>
                                <label for="Services_type">Services Type</label>
                                <select name="" id="servicesType" wire:model="servicesType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Services</option>
                                    <option value="CY/CY">CY/CY</option>
                                    <option value="CFS/CFS">CFS/CFS</option>
                                    <option value="CFS/CY">CFS/CY</option>
                                    <option value="CY/CFS">CY/CFS</option>
                                    <option value="CY/DOOR">CY/DOOR</option>
                                    <option value="DOOR/CY">DOOR/CY</option>
                                </select>
                            </div>
                            <div class="mb-4" wire:ignore>
                                <label for="incoTerms">Inco Terms</label>
                                <select name="incoTerms" id="incoTerms" wire:model="incoTerms" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Terms</option>
                                    <option value="FOB">FOB</option>
                                    <option value="CFR">CFR</option>
                                    <option value="CIF">CIF</option>
                                    <option value="CPT">CPT</option>
                                    <option value="CIP">CIP</option>
                                    <option value="FAS">FAS</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Mother Vessel -->
                        <div class=" mb-4">
                            <label for=" flightVesselName">Flight Name</label>
                            <input type="text" id="flightVesselName" name="flightVesselName" wire:model="flightVesselName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="flightVesselNo">Flight No</label>
                            <input type="text" id="flightVesselNo" name="flightVesselNo" wire:model="flightVesselNo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Feeder Vessel -->
                        <div class="mb-4" hidden>
                            <label for="ocean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="ocean_vessel_feeder" name="ocean_vessel_feeder" wire:model="ocean_vessel_feeder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('ocean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <!-- ETA -->
                            <div class="">
                                <label for="estimearrival">ETA / Estimate Time Arrival</label>
                                <input type="datetime-local" id=" estimearrival" name="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimearrival')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ETD -->
                            <div class="">
                                <label for="estimedelivery">ETD / Estimate Time Departure</label>
                                <input type="datetime-local" id=" estimedelivery" name="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimedelivery')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="payableAtJob">Payable at</label>
                            <input type="text" id="payableAtJob" name="payableAtJob" wire:model="payableAtJob" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('payableAtJob')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div wire:ignore>
                            <label for="freightTypeJob">Freight</label>
                            <select name="freightTypeJob" id="freightTypeJob" wire:model="freightTypeJob" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="collect">Collect</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                        <div class="" wire:ignore>
                            <label for="cross_trade">Cross trade</label>
                            <select name="" id="cross_trade" wire:model="cross_trade" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 border rounded-md p-4 border-1 border-gray-300 mt-5">
                        <div class="port-container" data-model="port_of_loading" data-radio-name="i nputTypeLoading" wire:ignore wire:change="port_of_loading">
                            <h2 class="text-lg font-semibold">Port Of Loading / POL</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeLoading" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeLoading"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_loading"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_loading" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>

                        <div class="port-container" data-model="port_of_final" data-radio-name="inputTypeFinal" wire:ignore wire:change="port_of_final">
                            <h2 class="text-lg font-semibold">Port Of Final / POF</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeFinal" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeFinal"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_final"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_final" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore wire:change="place_of_receipt">
                            <h2 class="text-lg font-semibold">Place Of Receipts <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="port_of_receipt" data-radio-name="inputTypePReceipt" wire:ignore wire:change="port_of_receipt">
                            <h2 class="text-lg font-semibold">Port Of Receipt / POR <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypePReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypePReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Discharge -->
                        <div class="port-container" data-model="port_of_discharge" data-radio-name="inputTypeDischarge" wire:ignore>
                            <h2 class="text-lg font-semibold">Port Of Discharge / POD <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDischarge" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDischarge"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_discharge"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_discharge" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Delivery -->
                        <div class="port-container" data-model="place_of_delivery" data-radio-name="inputTypeDelivery" wire:ignore>
                            <h2 class="text-lg font-semibold">Place Of Delivery <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDelivery" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDelivery"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_delivery"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_delivery" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 ">
                        <div>
                            <label for="remarksJobDetailJobs">Remarks</label>
                            <textarea type="text" id="remarksJobDetailJobs" name="remarksJobDetailJobs" wire:model="remarksJobDetailJobs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div wire:ignore>
                            <div>
                                <label for="jobEmployee">Employee</label>
                                <select name="jobEmployee" id="jobEmployee" wire:model="jobEmployee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    @foreach($employe as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="hazardousType">Hazardous</label>
                                <select name="hazardousType" id="hazardousType" x-model="hazardousType" wire:model="hazardousType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                        </div>
                        <template x-if="hazardousType === 'yes'">
                            <div wire:ignore x-init="init()">
                                <label for="hazardousClassType">Harzadous Class</label>
                                <select name="hazardousType" id="hazardousClassType" wire:model="hazardousClassType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="class1">Class1</option>
                                    <option value="class2">Class2</option>
                                    <option value="class3">Class3</option>
                                    <option value="class4">Class4</option>
                                    <option value="class5">Class5</option>
                                    <option value="class6">Class6</option>
                                    <option value="class7">Class7</option>
                                    <option value="class8">Class8</option>
                                    <option value="class9">class9</option>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>
                @break
                @case('air_inbound')
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-3 mb-3 gap-x-4">
                        {{-- Client --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Client</label>
                            <input
                                type="text"
                                value="{{ $this->clientName }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none "
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Job Type --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Job Type</label>
                            <input
                                type="text"
                                value="{{ strtoupper(str_replace('_', ' ', $type_job)) }}"
                                readonly
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none">
                            @error('seal_no')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Customer Code --}}
                        <div class="flex flex-col space-y-2">
                            <label class="text-sm font-medium text-gray-700">Customer Code No</label>
                            <input
                                type="text"
                                wire:model="customerCodeJob"
                                class="text-sm font-bold w-full border-2 border-gray-300 rounded-md focus:ring-0 focus:outline-none ">
                            @error('customerCodeJob')
                            <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                            <p class="text-xs text-gray-500 italic mt-1">Note: adjustable as needed</p>
                        </div>
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Job</label>
                            <input type="text" wire:model="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('job_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MAWB No</label>
                            <input type="text" wire:model="jobBillLadingNo" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MAWB Date</label>
                            <input type="date" wire:model="jobBillLadingDate" placeholder="Enter Shipment ID" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('jobBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div></div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>HAWB No</label>
                            <input type="text" wire:model="houseJobBillLadingNo" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('houseJobBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>HAWB Date</label>
                            <input type="date" wire:model="houseJobBillLadingDate" placeholder="Enter HBL DATE" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('houseJobBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" wire:ignore>
                            <label for="airline">Flight</label>
                            <select name="airline" id="airline" wire:model="carrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($airlines as $ai)
                                <option value="{{ $ai->id }}">{{ $ai->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label for="originAgent">Origin Agent</label>
                            <select name="originAgent" id="originAgent_import" wire:model="originAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($ogentsJob as $oa)
                                @if(in_array('agent', $oa->roles))
                                <option value="{{ $oa->id }}">{{ $oa->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <div class="" wire:ignore>
                                <label for="Services_type">Services Type</label>
                                <select name="" id="servicesType" wire:model="servicesType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Services</option>
                                    <option value="CY/CY">CY/CY</option>
                                    <option value="CFS/CFS">CFS/CFS</option>
                                    <option value="CFS/CY">CFS/CY</option>
                                    <option value="CY/CFS">CY/CFS</option>
                                    <option value="CY/DOOR">CY/DOOR</option>
                                    <option value="DOOR/CY">DOOR/CY</option>
                                </select>
                            </div>
                            <div class="mb-4" wire:ignore>
                                <label for="incoTerms">Inco Terms</label>
                                <select name="incoTerms" id="incoTerms" wire:model="incoTerms" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="">Select Terms</option>
                                    <option value="FOB">FOB</option>
                                    <option value="CFR">CFR</option>
                                    <option value="CIF">CIF</option>
                                    <option value="CPT">CPT</option>
                                    <option value="CIP">CIP</option>
                                    <option value="FAS">FAS</option>
                                </select>
                            </div>
                        </div>


                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Mother Vessel -->
                        <div class=" mb-4">
                            <label for=" flightVesselName">Flight Name</label>
                            <input type="text" id="flightVesselName" name="flightVesselName" wire:model="flightVesselName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="flightVesselNo">Flight No</label>
                            <input type="text" id="flightVesselNo" name="flightVesselNo" wire:model="flightVesselNo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('flightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Feeder Vessel -->
                        <div class="mb-4" hidden>
                            <label for="ocean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="ocean_vessel_feeder" name="ocean_vessel_feeder" wire:model="ocean_vessel_feeder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('ocean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ">
                            <!-- ETA -->
                            <div class="">
                                <label for="estimearrival">ETA / Estimate Time Arrival</label>
                                <input type="datetime-local" id=" estimearrival" name="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimearrival')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- ETD -->
                            <div class="">
                                <label for="estimedelivery">ETD / Estimate Time Departure</label>
                                <input type="datetime-local" id=" estimedelivery" name="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                @error('estimedelivery')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div>
                            <label for="payableAtJob">Payable at</label>
                            <input type="text" id="payableAtJob" name="payableAtJob" wire:model="payableAtJob" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('payableAtJob')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div wire:ignore>
                            <label for="freightTypeJob">Freight</label>
                            <select name="freightTypeJob" id="freightTypeJob" wire:model="freightTypeJob" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="collect">Collect</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                        <div class="" wire:ignore>
                            <label for="cross_trade">Cross trade</label>
                            <select name="" id="cross_trade" wire:model="cross_trade" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                    </div>

                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 border rounded-md p-4 border-1 border-gray-300 mt-5">
                        <div class="port-container" data-model="port_of_loading" data-radio-name="i nputTypeLoading" wire:ignore wire:change="port_of_loading">
                            <h2 class="text-lg font-semibold">Port Of Loading / POL</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeLoading" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeLoading"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_loading"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_loading" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>

                        <div class="port-container" data-model="port_of_final" data-radio-name="inputTypeFinal" wire:ignore wire:change="port_of_final">
                            <h2 class="text-lg font-semibold">Port Of Final / POF</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeFinal" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeFinal"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_final"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_final" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore wire:change="place_of_receipt">
                            <h2 class="text-lg font-semibold">Place Of Receipts <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="port_of_receipt" data-radio-name="inputTypePReceipt" wire:ignore wire:change="port_of_receipt">
                            <h2 class="text-lg font-semibold">Port Of Receipt / POR <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypePReceipt" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypePReceipt"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Discharge -->
                        <div class="port-container" data-model="port_of_discharge" data-radio-name="inputTypeDischarge" wire:ignore>
                            <h2 class="text-lg font-semibold">Port Of Discharge / POD <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDischarge" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDischarge"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="port_of_discharge"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="port_of_discharge" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <!-- Port Of Delivery -->
                        <div class="port-container" data-model="place_of_delivery" data-radio-name="inputTypeDelivery" wire:ignore>
                            <h2 class="text-lg font-semibold">Place Of Delivery <span class="text-red-500">*</span></h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="select" name="inputTypeDelivery" checked> Select from List
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="input" name="inputTypeDelivery"> Enter Port Manually
                                    </label>
                                </div>
                            </div>
                            <!-- Select Dropdown -->
                            <div class="select-container">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <select wire:model="place_of_delivery"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="place_of_delivery" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 ">
                        <div>
                            <label for="remarksJobDetailJobs">Remarks</label>
                            <textarea type="text" id="remarksJobDetailJobs" name="remarksJobDetailJobs" wire:model="remarksJobDetailJobs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div wire:ignore>
                            <div>
                                <label for="jobEmployee">Employee</label>
                                <select name="jobEmployee" id="jobEmployee" wire:model="jobEmployee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    @foreach($employe as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="hazardousType">Hazardous</label>
                                <select name="hazardousType" id="hazardousType" x-model="hazardousType" wire:model="hazardousType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>

                        </div>
                        <template x-if="hazardousType === 'yes'">
                            <div wire:ignore x-init="init()">
                                <label for="hazardousClassType">Harzadous Class</label>
                                <select name="hazardousType" id="hazardousClassType" wire:model="hazardousClassType" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value=""></option>
                                    <option value="class1">Class1</option>
                                    <option value="class2">Class2</option>
                                    <option value="class3">Class3</option>
                                    <option value="class4">Class4</option>
                                    <option value="class5">Class5</option>
                                    <option value="class6">Class6</option>
                                    <option value="class7">Class7</option>
                                    <option value="class8">Class8</option>
                                    <option value="class9">class9</option>
                                </select>
                            </div>
                        </template>
                    </div>
                </div>
                @break
                @default
                <div class="bg-yellow-100 p-3 rounded-xl text-yellow-700">
                    Pilih tipe job yang valid
                </div>
                @endswitch

                <div class="flex justify-between mt-4">
                    <button wire:click.prevent="previousStep" class="px-4 py-2 border rounded">Kembali</button>
                    <button wire:click.prevent="nextStep" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lanjut</button>
                </div>
            </div>
            @elseif($step === 3)
            <!-- <pre>{{$step,$type_job}}</pre> -->
            <!-- STEP 3: Container Info -->
            <div x-show="step === 3" x-transition x-cloak>
                <h2 class="text-lg font-semibold mb-3">Container</h2>
                @switch($type_job)
                @case('ocean_fcl_export')
                @case('ocean_fcl_import')
                @case('ocean_lcl_export')
                @case('ocean_lcl_import')
                <div class="p-3">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <!-- Client and Job Type Info -->
                        <div class="flex flex-col col-span-2 space-y-3 rounded-md">
                            <label>Client</label>
                            <input type="text" value="{{ $this->clientName }}" readonly
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0"
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col col-span-2 space-y-3 rounded-md">
                            <label>Job Type</label>
                            <input type="text" value="{{ strtoupper(str_replace('_', ' ', $type_job)) }}"
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0">
                            @error('seal_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>

                        <!-- Left Column -->
                        <div class="flex flex-col space-y-2 col-span-2" wire:ignore>
                            <label>Container Type</label>
                            <select name="" id="containerType" wire:model="containerType" class="w-full block rounded-md border border-gray-300">
                                <option value=""></option>
                                <option value="20'DC">20'DC - 20 ft Dry Container</option>
                                <option value="20'HC">20'HC - 20 ft High Cube</option>
                                <option value="20'OT">20'OT - 20 ft Open Top</option>
                                <option value="20'FR">20'FR - 20 ft Flat Rack</option>
                                <option value="20'RF">20'RF - 20 ft Reefer</option>
                                <option value="20'TK">20'TK - 20 ft Tank</option>
                                <option value="20'VH">20'VH - 20 ft Ventilated</option>
                                <option value="20'PL">20'PL - 20 ft Platform</option>
                                <option value="40'DC">40'DC - 40 ft Dry Container</option>
                                <option value="40'HC">40'HC - 40 ft High Cube</option>
                                <option value="40'OT">40'OT - 40 ft Open Top</option>
                                <option value="40'FR">40'FR - 40 ft Flat Rack</option>
                                <option value="40'RF">40'RF - 40 ft Reefer</option>
                                <option value="40'TK">40'TK - 40 ft Tank</option>
                                <option value="40'VH">40'VH - 40 ft Ventilated</option>
                                <option value="40'PL">40'PL - 40 ft Platform</option>
                                <option value="45'HC">45'HC - 45 ft High Cube</option>
                                <option value="45'RF">45'RF - 45 ft Reefer</option>
                                <option value="45'PL">45'PL - 45 ft Platform</option>
                                <option value="FCL">FCL - Full Container Load</option>
                                <option value="LCL">LCL - Less than Container Load</option>
                            </select>
                            @error('container_type')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
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
                            <label>Container Release No.</label>
                            <input type="text" placeholder="Enter Container No " wire:model="containerReleaseNo"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('container_size')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label>Release date</label>
                            <input type="date" wire:model="containerReleaseDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-span-2"></div>
                        <!-- Package Info -->
                        <div class="flex flex-col space-y-2">
                            <label>No Of Packages</label>
                            <input type="text" wire:model="noOfPackages" placeholder="Enter No Of Packages"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Of Packages</label>
                            <select id="typeOfPackages" wire:model="typeOfPackages" class="block w-full rounded-md border-gray-300 shadow-sm">
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
                            <label>No of Pallet</label>
                            <input type="text" placeholder="Enter No Of Pallet" wire:model="noOfPallet"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div></div>
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
                        <div class="flex flex-col space-y-2">
                            <label>Net Of Weight</label>
                            <input type="text" placeholder="Enter Net Of Weight" wire:model="netOfWeight"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Of Net Of Weight</label>
                            <select id="typeNetOfWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="KGS">KGS</option>
                            </select>
                        </div>
                        <!-- Weight Info -->
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

                        <!-- Volume Weight Info -->


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

                        <div class="flex flex-col space-y-2">
                            <label>HS Code</label>
                            <input type="text" placeholder="Enter HS Code" wire:model="hsCode"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div></div>
                        <div class="flex flex-col space-y-2 col-span-2">
                            <label>Remarks</label>
                            <textarea placeholder="Enter remarks" rows="3" wire:model="containerRemarks"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div class="flex flex-col space-y-2 col-span-2">
                            <label>HS Description</label>
                            <textarea placeholder="Enter Hs Description" rows="3" wire:model="hsCodeDesc"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                    </div>
                </div>
                @break
                @case('air_outbound')
                @case('air_inbound')
                @case('trucking')
                @case('logistics')
                @case('domestic_transportation')
                <div class="p-3">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <!-- Client and Job Type Info -->
                        <div class="flex flex-col col-span-2 space-y-3 rounded-md">
                            <label>Client</label>
                            <input type="text" value="{{ $this->clientName }}" readonly
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0"
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col col-span-2 space-y-3 rounded-md">
                            <label>Job Type</label>
                            <input type="text" value="{{ strtoupper(str_replace('_', ' ', $type_job)) }}"
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0">
                            @error('seal_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>

                        <!-- Left Column -->
                        <div class="flex flex-col space-y-2">
                            <label>Container Release No.</label>
                            <input type="text" placeholder="Enter Container No " wire:model="containerReleaseNo"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('container_size')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label>Release date</label>
                            <input type="date" wire:model="containerReleaseDate" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-span-2"></div>
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
                            <label>No of Pallet</label>
                            <input type="text" placeholder="Enter No Of Pallet" wire:model="noOfPallet"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div></div>
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
                        <div class="flex flex-col space-y-2">
                            <label>Net Of Weight</label>
                            <input type="text" placeholder="Enter Net Of Weight" wire:model="netOfWeight"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Of Net Of Weight</label>
                            <select id="typeNetOfWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="KGS">KGS</option>
                            </select>
                        </div>
                        <!-- Weight Info -->
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

                        <!-- Volume Weight Info -->


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

                        <div class="flex flex-col space-y-2">
                            <label>HS Code</label>
                            <input type="text" placeholder="Enter HS Code" wire:model="hsCode"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div></div>
                        <div class="flex flex-col space-y-2 col-span-2">
                            <label>Remarks</label>
                            <textarea placeholder="Enter remarks" rows="3" wire:model="containerRemarks"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div class="flex flex-col space-y-2 col-span-2">
                            <label>HS Description</label>
                            <textarea placeholder="Enter Hs Description" rows="3" wire:model="hsCodeDesc"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                    </div>
                </div>
                @break
                @endswitch
                <div class="flex justify-between mt-4">
                    <button wire:click.prevent="previousStep" class="px-4 py-2 border rounded">Kembali</button>
                    <button wire:click.prevent="nextStep" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lanjut</button>
                </div>
            </div>
            @elseif($step === 4)
            <!-- STEP 4: Conclusion -->
            <div x-show="step === 4" x-cloak x-transition>
                <h2 class="text-lg font-semibold mb-3">Kesimpulan</h2>
                <div class="space-y-2">
                    <div class="font-bold text-2xl text-center mb-4">Detail Jobs</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-6">
                            <div class="flex flex-items grid grid-cols-3 ">
                                <p class="col-span-1"><strong>Tipe Job</strong> </p>
                                <p class="col-span-2">: {{ strtoupper(str_replace('_', ' ', $type_job)) }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>Client </strong></p>
                                <p class="col-span-2">: {{ $this->clientName }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>No. Job / HBL No. </strong> </p>
                                <p class="col-span-2">: {{ $job_id }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>MBL:</strong> </p>
                                <p class="col-span-2">: {{ $jobBillLadingNo }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>HBL No. </strong> </p>
                                <p class="col-span-2">: {{ $houseJobBillLadingNo }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>HBL No. </strong> </p>
                                <p class="col-span-2">: {{ $houseJobBillLadingDate }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>MBL:</strong> </p>
                                <p class="col-span-2">: {{ $jobBillLadingNo }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>MBL DATE</strong> </p>
                                <p class="col-span-2">: {{ $jobBillLadingDate }} </p>
                            </div>

                        </div>
                        <div class="space-y-6 ">
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>Port Of loading</strong> </p>
                                <p class="col-span-2">: {{ $port_of_loading }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>Place Of Receipt</strong> </p>
                                <p class="col-span-2">: {{ $place_of_receipt }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>Port Of Discharge</strong> </p>
                                <p class="col-span-2">: {{ $port_of_discharge }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>place Of Delivery</strong> </p>
                                <p class="col-span-2">: {{ $place_of_delivery }} </p>
                            </div>
                            @if(in_array($type_job, ['ocean_fcl_export', 'ocean_lcl_export', 'air_outbound']))
                            <div class="flex flex-items grid grid-cols-3 ">
                                <p class="col-span-1"><strong>Delivery Agents</strong> </p>
                                <p class="col-span-2">: {{ $this->dagentName}} </p>
                            </div>
                            @elseif(in_array($type_job, ['ocean_fcl_import', 'ocean_lcl_import', 'air_inbound']))
                            <div class="flex flex-items grid grid-cols-3 ">
                                <p class="col-span-1"><strong>Origin Agents</strong> </p>
                                <p class="col-span-2">: {{ $this->ogentName }} </p>
                            </div>
                            @else
                            @endif
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>ETA / Estimate Time Arrival </strong> </p>
                                <p class="col-span-2">: {{ \Carbon\Carbon::parse($estimearrival)->format('d M Y H:i') }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>ETD / Estimate Time Departure</strong> </p>
                                <p class="col-span-2">: {{ \Carbon\Carbon::parse($estimedelivery)->format('d M Y H:i') }} </p>
                            </div>
                        </div>

                        <div class="space-y-6">
                            @if(in_array($type_job, ['ocean_fcl_export', 'ocean_lcl_export', 'ocean_fcl_import','ocean_lcl_import']))

                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>Carrier</strong> </p>
                                <p class="col-span-2">: {{ $this->carrierAirlineName }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3 ">
                                <p class="col-span-1"><strong>Vessel Name</strong> </p>
                                <p class="col-span-2">: {{ $flightVesselName }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3 ">
                                <p class="col-span-1"><strong>Voyage</strong> </p>
                                <p class="col-span-2">: {{ $flightVesselNo }} </p>
                            </div>
                            @elseif(in_array($type_job, [ 'air_outbound', 'air_inbound']))

                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>Airlines</strong> </p>
                                <p class="col-span-2">: {{ $this->carrierAirlineName }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3 ">
                                <p class="col-span-1"><strong>Flight Name</strong> </p>
                                <p class="col-span-2">: {{ $flightVesselName }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3 ">
                                <p class="col-span-1"><strong>Flight No</strong> </p>
                                <p class="col-span-2">: {{ $flightVesselNo }} </p>
                            </div>
                            @else
                            @endif

                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>Services Type</strong> </p>
                                <p class="col-span-2">: {{ $servicesType }} </p>
                            </div>
                            <div class="flex flex-items grid grid-cols-3">
                                <p class="col-span-1"><strong>Inco Terms</strong> </p>
                                <p class="col-span-2">: {{ $incoTerms }} </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class=" flex justify-between mt-4">
                    <button wire:click.prevent="previousStep" class="px-4 py-2 border rounded">Kembali</button>
                    <button wire:click.prevent="submitForm" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Submit</button>
                </div>
            </div>
            @else
            @endif
        </div>

    </form>

</div>
</div>
@push('scripts')
@script()
<script>
    // Untuk shipper, consignee, notify
    window.reinitSelect2 = () => {
        [{
                sel: '#carrier',
                model: 'carrierAirline',
                placeholder: 'Select carrier'
            },
            {
                sel: '#airline',
                model: 'carrierAirline',
                placeholder: 'Select airlines'
            },
            {
                sel: '#client_id',
                model: 'client_id',
                placeholder: 'Select Client'
            },
            {
                sel: '#incoTerms',
                model: 'incoTerms',
                placeholder: 'Select Inco Terms'
            },
            {
                sel: '#servicesType',
                model: 'servicesType',
                placeholder: 'Select services Type '
            },
            {
                sel: '#containerType',
                model: 'containerType',
                placeholder: 'Select container Type '
            },
            {
                sel: '#typeOfPackages',
                model: 'typeOfPackages',
                placeholder: 'Select Type Of Packages '
            },
            {
                sel: '#typeOfGrossWeight',
                model: 'typeOfGrossWeight',
                placeholder: 'Select Type Of Gross Weight '
            },
            {
                sel: '#typeOfVolumeWeight',
                model: 'typeOfVolumeWeight',
                placeholder: 'Select Type Of Volume Weight '
            },
            {
                sel: '#typeNetOfWeight',
                model: 'typeNetOfWeight',
                placeholder: 'Select Type Of Net Of Packages '
            },
            {
                sel: '#typeOfTotalWeight',
                model: 'typeOfTotalWeight',
                placeholder: 'Select Type Of Total Weight '
            },
            {
                sel: '#hazardousType',
                model: 'hazardousType',
                placeholder: 'Select Type '
            },
            {
                sel: '#cross_trade',
                model: 'cross_trade',
                placeholder: 'Select is it cross trade? '
            },

            {
                sel: '#deliveryAgent_export',
                model: 'deliveryAgent',
                placeholder: 'Select Agent '
            },
            {
                sel: '#hazardousClassType',
                model: 'hazardousClassType',
                placeholder: 'Select agent '
            },
            {
                sel: '#freightTypeJob',
                model: 'freightTypeJob',
                placeholder: 'Select Freight '
            },
            {
                sel: '#jobEmployee',
                model: 'jobEmployee',
                placeholder: 'Select Agent '
            },
            {
                sel: '#originAgent_import',
                model: 'originAgent',
                placeholder: 'Select Agent '
            },

            {
                sel: '#flightJob',
                model: 'flightJob',
                placeholder: 'Select Flight '
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

    // Untuk port-select (versi modular, bisa digunakan ulang)
    window.PortSelect2 = {
        init(selector = '.port-select', type_job = ['logistics', 'air', 'domestics_transport', 'trucking']) {
            const isAir = ['air', 'logistics', 'domestics_transport', 'trucking'].some(prefix => type_job.startsWith(prefix));
            const endpoint = isAir ? '/data/airports-ajax' : `/data/ports.json?t=${new Date().toISOString().slice(0, 16)}`;

            document.querySelectorAll(selector).forEach(select => {
                const model = select.getAttribute('wire:model');
                const currentValue = model && typeof $wire !== 'undefined' ? $wire[model] : select.value;

                if ($(select).hasClass("select2-hidden-accessible")) {
                    $(select).select2('destroy');
                }

                if (isAir) {
                    // ✈️ AIRPORTS (AJAX)
                    $(select).select2({
                        placeholder: "Select an airport...",
                        allowClear: true,
                        theme: "tailwindcss-3",
                        width: "100%",
                        minimumInputLength: 3,
                        ajax: {
                            url: endpoint,
                            dataType: 'json',
                            delay: 350,
                            data: function(params) {
                                return {
                                    q: params.term || '',
                                    page: params.page || 1
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.results,
                                    pagination: {
                                        more: data.pagination?.more ?? false
                                    }
                                };
                            },
                            cache: true
                        }
                    });

                    // Restore selected
                    if (currentValue) {
                        const label = currentValue;
                        const id = label.split(' - ')[0]; // "CGK - Indonesia" => "CGK"
                        const option = new Option(label, id, true, true);
                        $(select).append(option).trigger('change');
                    }

                    // Bind change
                    $(select).off('change.lw').on('change.lw', function() {
                        const selectedLabel = $(this).select2('data')[0]?.id;
                        if (model && typeof $wire !== 'undefined') {
                            $wire.set(model, selectedLabel);
                        }
                    });

                } else {
                    // 🚢 SEAPORTS (STATIC JSON + search filter)
                    fetch(endpoint)
                        .then(res => res.json())
                        .then(data => {
                            $(select).select2({
                                placeholder: "Select a port...",
                                allowClear: true,
                                theme: "tailwindcss-3",
                                width: "100%",
                                minimumInputLength: 2,
                                data: [],
                                ajax: {
                                    transport: function(params, success, failure) {
                                        fetch(endpoint)
                                            .then(res => res.json())
                                            .then(data => {
                                                const term = params.data.term?.toLowerCase() || '';
                                                const results = data
                                                    .filter(port => {
                                                        const name = port.name?.toLowerCase() || '';
                                                        const code = port.code?.toLowerCase() || '';
                                                        const country = port.country?.toLowerCase() || '';
                                                        return name.includes(term) || code.includes(term) || country.includes(term);
                                                    })
                                                    .filter(port => !!port.code)
                                                    .slice(0, 20)
                                                    .map(port => ({
                                                        id: `${port.name}, ${port.country}`.toUpperCase(),
                                                        text: `${port.name} (${port.code}) - ${port.country}`.toUpperCase()

                                                    }));

                                                success({
                                                    results
                                                });
                                            })
                                            .catch(failure);
                                    },
                                    delay: 250
                                }

                            });

                            if (currentValue) {
                                const option = new Option(currentValue, currentValue, true, true);
                                $(select).append(option).trigger('change');
                            }

                            $(select).off('change.lw').on('change.lw', function() {
                                const selectedValue = $(this).val();
                                if (model && typeof $wire !== 'undefined') {
                                    $wire.set(model, selectedValue);
                                }
                            });
                        });
                }
            });
            document.querySelectorAll('.port-container').forEach(container => {
                const radios = container.querySelectorAll('input[type="radio"]');
                radios.forEach(radio => {
                    radio.removeEventListener('change', window.PortSelect2.radioChangeHandler);
                });
                const radioChangeHandler = function() {
                    const selectContainer = container.querySelector('.select-container');
                    const inputContainer = container.querySelector('.input-container');
                    if (this.value === 'select') {
                        selectContainer.classList.remove('hidden');
                        inputContainer.classList.add('hidden');
                    } else {
                        selectContainer.classList.add('hidden');
                        inputContainer.classList.remove('hidden');
                    }
                };
                radios.forEach(radio => {
                    radio.addEventListener('change', radioChangeHandler);
                });
                // Save handler reference for removal next time
                window.PortSelect2.radioChangeHandler = radioChangeHandler;
            });
        }

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