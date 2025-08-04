<div class="p-6 max mx-auto [&::-webkit-scrollbar]:hidden overflow-x-hidden">
    <form action="" wire:submit="submitForm">
        <div>
            <div x-data="{
    step: @entangle('step'),
    type_job: @entangle('shipmentType_job') ,

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
}" x-init="init()" class="p-6 space-y-6">

                <!-- Step Indicators -->
                <div class="flex justify-center space-x-4 text-sm font-medium">
                    @for($i = 1; $i <= 3; $i++)
                        <div class="px-4 py-2 rounded @if($step === $i) bg-blue-600 text-white @else bg-gray-200 text-gray-600 @endif">
                        Step {{ $i }}
                </div>
                @endfor
            </div>

            @if($step === 1)
            <!-- STEP 2: Isi Detail Job -->
            <div x-show="step === 1" x-transition x-cloak>
                <h2 class="text-lg font-semibold mb-3">Detail Shipments: {{ strtoupper(str_replace('_', ' ', $shipmentType_job)) }}</h2>

                @switch($shipmentType_job)
                @case('ocean_fcl_export')
                @case('ocean_fcl_import')
                @case('ocean_lcl_export')
                @case('ocean_lcl_import')
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 mb-3">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>Shipments Type</label>
                            <input type="text" value="{{ strtoupper(str_replace('_', ' ', $shipmentType_job)) }}" readonly
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0">
                            @error('shipmentType_job')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>Customer Code No</label>
                            <input type="text" wire:model="shipmentCustomerCodeJob" class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0" readonly>
                            @error('shipmentCustomerCodeJob')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md w-full" wire:ignore>
                            <label class="font-bold">Client</label>
                            <select wire:model="shipmentClient_id" id="shipmentClient_id" class="border p-2 rounded ">
                                <option value="">Pilih Client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('shipmentClient_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Shipments</label>
                            <input type="text" wire:model="shipment_id" class=" py-1.5 pr-8 pl-3  block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipment_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>Shipment Date</label>
                            <input type="date" wire:model="shipmentBillLadingDate" class=" py-1.5 pr-8 pl-3  block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4 flex flex-col space-y-3 rounded-md">
                            <label for="shipmentClient_address">Client Address</label>
                            <select class="border border-gray-300 rounded-lg py-1.5 pr-8 pl-3" name="shipmentClient_address" id="" wire:model="shipmentClient_address">
                                @foreach($shipmentClientAddresses as $a)
                                <option value="{{ $a->address }}">{{ $a->address }}</option>
                                @endforeach
                            </select>
                            @error('shipmentClient_address')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror

                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 col-span-2 gap-3">
                            <div class="flex flex-col space-y-3 rounded-md w-full" wire:ignore>
                                <label class="font-bold">Shipper</label>
                                <select wire:model="shipmentShipper_id" id="shipmentShipper_id" class="border p-2 rounded ">
                                    <option value=""></option>
                                    @foreach($shippers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                                @error('shipmentShipper_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                            </div>
                            <div class="flex flex-col space-y-3 rounded-md w-full" wire:ignore>
                                <label class="font-bold">Consignee</label>
                                <select wire:model="shipmentConsignee_id" id="shipmentConsignee_id" class="border p-2 rounded ">
                                    <option value=""></option>
                                    @foreach($consignees as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('shipmentConsignee_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                            </div>
                            <div class="flex flex-col space-y-3 rounded-md w-full" wire:ignore>
                                <label class="font-bold">Notify</label>
                                <select wire:model="shipmentNotify_id" id="shipmentNotify_id" class="border p-2 rounded ">
                                    <option value=""></option>
                                    @foreach($notifys as $n)
                                    <option value="{{ $n->id }}">{{ $n->name }}</option>
                                    @endforeach
                                </select>
                                @error('shipmentNotify_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                            </div>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        <div class="" wire:ignore>
                            <label for="shipmentDeliveryAgent">Delivery Agent</label>
                            <select name="shipmentDeliveryAgent" id="shipmentDeliveryAgent" wire:model="shipmentDeliveryAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($deliveryAgent as $da)
                                <option value="{{ $da->id }}">{{ $da->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="    " wire:ignore>
                            <label for="shipmentCarrierAgent">Carrier Agent</label>
                            <select name="shipmentCarrierAgent" id="shipmentCarrierAgent" wire:model="shipmentCarrierAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($carrierAgent as $ca)
                                <option value="{{ $ca->id }}">{{ $ca->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="">
                            <label> HBL No</label>
                            <input type="text" wire:model="shipmentHouseBillLadingNo" class=" py-1.5 pr-8 pl-3 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentHouseBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 border rounded-md p-4 border-1 border-gray-300 mt-5">
                        <div class="port-container" data-model="port_of_loading" data-radio-name="inputTypeLoading" wire:ignore>
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
                                <select wire:model="shipmentPort_of_loading"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPort_of_loading" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>

                        <div class="port-container" data-model="port_of_final" data-radio-name="inputTypeFinal" wire:ignore>
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
                                <select wire:model="shipmentPort_of_final"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPort_of_final" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore>
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
                                <select wire:model="shipmentPlace_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPlace_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="shipmentPort_of_receipt" data-radio-name="inputTypePReceipt" wire:ignore>
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
                                <select wire:model="shipmentPort_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPort_of_receipt" type="text"
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
                                <select wire:model="shipmentPort_of_discharge"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPort_of_discharge" type="text"
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
                                <select wire:model="shipmentPlace_of_delivery"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPlace_of_delivery" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" hidden>
                            <label for="shipmentOcean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="shipmentOcean_vessel_feeder" name="shipmentOcean_vessel_feeder" wire:model="shipmentOcean_vessel_feeder" class=" block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentOcean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ETA -->
                        <div class="mb-4">
                            <label for="shipmentEstimearrival">ETA / Estimate Time Arrival</label>
                            <input type="datetime-local" id=" shipmentEstimearrival" name="shipmentEstimearrival" wire:model="shipmentEstimearrival" class=" py-1.5 pr-8 pl-3 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentEstimearrival')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ETD -->
                        <div class="mb-4">
                            <label for="shipmentEstimedelivery">ETD / Estimate Time Departure</label>
                            <input type="datetime-local" id=" shipmentEstimedelivery" name="shipmentEstimedelivery" wire:model="shipmentEstimedelivery" class=" py-1.5 pr-8 pl-3 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentEstimedelivery')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="shipmentPayableAtJob">Payable at</label>
                            <input type="text" id="shipmentPayableAtJob" name="shipmentPayableAtJob" wire:model="shipmentPayableAtJob" class=" block w-full rounded-md border-gray-300 shadow-sm py-1.5 pr-8 pl-3 focus:ring focus:ring-blue-200">
                            @error('shipmentPayableAtJob')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" wire:ignore>
                            <label for="shipmentServices_type">Services Type</label>
                            <select name="" id="shipmentServices_type" wire:model="shipmentServices_type" class="block w-full rounded-md border-gray-300 shadow-sm  focus:ring focus:ring-blue-200">
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
                            <label for="shipmentCross_trade">Inco Terms</label>
                            <select name="shipmentCross_trade" id="shipmentCross_trade" wire:model="shipmentCross_trade" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Terms</option>
                                <option value="FOB">FOB</option>
                                <option value="CFR">CFR</option>
                                <option value="CIF">CIF</option>
                                <option value="CPT">CPT</option>
                                <option value="CIP">CIP</option>
                                <option value="FAS">FAS</option>
                            </select>
                        </div>
                        <div wire:ignore>
                            <label for="shipmentFreightTypeJob">Freight</label>
                            <select name="shipmentFreightTypeJob" id="shipmentFreightTypeJob" wire:model="shipmentFreightTypeJob" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="collect">Collect</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label for="shipmentRemarksJobDetailJobs">Remarks</label>
                            <textarea type="text" id="shipmentRemarksJobDetailJobs" name="shipmentRemarksJobDetailJobs" wire:model="shipmentRemarksJobDetailJobs" class=" block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>

                    </div>
                </div>
                @break
                @case('air_inbound')
                @case('air_outbound')
                @case('domestic_transportation')
                @case('trucking')
                @case('logistics')
                <div>
                    <div class="grid grid-cols-1 md:grid-cols-2 mb-3">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>Shipments Type</label>
                            <input type="text" value="{{ strtoupper(str_replace('_', ' ', $shipmentType_job)) }}" readonly
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0">
                            @error('shipmentType_job')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>Customer Code No</label>
                            <input type="text" wire:model="shipmentCustomerCodeJob" class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0" readonly>
                            @error('shipmentCustomerCodeJob')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md w-full" wire:ignore>
                            <label class="font-bold">Client</label>
                            <select wire:model="shipmentClient_id" id="shipmentClient_id" class="border p-2 rounded ">
                                <option value="">Pilih Client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                            @error('shipmentClient_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Shipments</label>
                            <input type="text" wire:model="shipment_id" class=" py-1.5 pr-8 pl-3  block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipment_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>Shipment Date</label>
                            <input type="date" wire:model="shipmentBillLadingDate" class=" py-1.5 pr-8 pl-3  block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentBillLadingDate')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4 flex flex-col space-y-3 rounded-md">
                            <label for="shipmentClient_address">Client Address</label>
                            <select class="border border-gray-300 rounded-lg py-1.5 pr-8 pl-3" name="shipmentClient_address" id="" wire:model="shipmentClient_address">
                                @foreach($shipmentClientAddresses as $a)
                                <option value="{{ $a->address }}">{{ $a->address }}</option>
                                @endforeach
                            </select>
                            @error('shipmentClient_address')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror

                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 col-span-2 gap-3">
                            <div class="flex flex-col space-y-3 rounded-md w-full" wire:ignore>
                                <label class="font-bold">Shipper</label>
                                <select wire:model="shipmentShipper_id" id="shipmentShipper_id" class="border p-2 rounded ">
                                    <option value=""></option>
                                    @foreach($shippers as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </select>
                                @error('shipmentShipper_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                            </div>
                            <div class="flex flex-col space-y-3 rounded-md w-full" wire:ignore>
                                <label class="font-bold">Consignee</label>
                                <select wire:model="shipmentConsignee_id" id="shipmentConsignee_id" class="border p-2 rounded ">
                                    <option value=""></option>
                                    @foreach($consignees as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('shipmentConsignee_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                            </div>
                            <div class="flex flex-col space-y-3 rounded-md w-full" wire:ignore>
                                <label class="font-bold">Notify</label>
                                <select wire:model="shipmentNotify_id" id="shipmentNotify_id" class="border p-2 rounded ">
                                    <option value=""></option>
                                    @foreach($notifys as $n)
                                    <option value="{{ $n->id }}">{{ $n->name }}</option>
                                    @endforeach
                                </select>
                                @error('shipmentNotify_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                            </div>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

                        <div class="" wire:ignore>
                            <label for="shipmentDeliveryAgent">Delivery Agent</label>
                            <select name="shipmentDeliveryAgent" id="shipmentDeliveryAgent" wire:model="shipmentDeliveryAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($deliveryAgent as $da)
                                <option value="{{ $da->id }}">{{ $da->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="" wire:ignore>
                            <label for="shipmentCarrierAgent">Carrier Agent</label>
                            <select name="shipmentCarrierAgent" id="shipmentCarrierAgent" wire:model="shipmentCarrierAgent" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200 select2">
                                <option value="">Select agent</option>
                                @foreach($carrierAgent as $ca)
                                <option value="{{ $ca->id }}">{{ $ca->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="">
                            <label>HAWB No</label>
                            <input type="text" wire:model="shipmentHouseBillLadingNo" class=" py-1.5 pr-8 pl-3 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentHouseBillLadingNo')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4 border rounded-md p-4 border-1 border-gray-300 mt-5">
                        <div class="port-container" data-model="port_of_loading" data-radio-name="inputTypeLoading" wire:ignore>
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
                                <select wire:model="shipmentPort_of_loading"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPort_of_loading" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>

                        <div class="port-container" data-model="port_of_final" data-radio-name="inputTypeFinal" wire:ignore>
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
                                <select wire:model="shipmentPort_of_final"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPort_of_final" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore>
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
                                <select wire:model="shipmentPlace_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPlace_of_receipt" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                        <div class="port-container" data-model="shipmentPort_of_receipt" data-radio-name="inputTypePReceipt" wire:ignore>
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
                                <select wire:model="shipmentPort_of_receipt"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPort_of_receipt" type="text"
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
                                <select wire:model="shipmentPort_of_discharge"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPort_of_discharge" type="text"
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
                                <select wire:model="shipmentPlace_of_delivery"
                                    class="port-select block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                    <option value="" disabled selected>Select a port...</option>
                                </select>
                            </div>
                            <!-- Input Field -->
                            <div class="input-container hidden">
                                <label class="block text-sm font-medium text-gray-700 p-1">Port</label>
                                <input wire:model="shipmentPlace_of_delivery" type="text"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"
                                    placeholder="Enter port name">
                            </div>
                        </div>
                    </div>
                    <div class=" grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" hidden>
                            <label for="shipmentOcean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="shipmentOcean_vessel_feeder" name="shipmentOcean_vessel_feeder" wire:model="shipmentOcean_vessel_feeder" class=" block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentOcean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ETA -->
                        <div class="mb-4">
                            <label for="shipmentEstimearrival">ETA / Estimate Time Arrival</label>
                            <input type="datetime-local" id=" shipmentEstimearrival" name="shipmentEstimearrival" wire:model="shipmentEstimearrival" class=" py-1.5 pr-8 pl-3 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentEstimearrival')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- ETD -->
                        <div class="mb-4">
                            <label for="shipmentEstimedelivery">ETD / Estimate Time Departure</label>
                            <input type="datetime-local" id=" shipmentEstimedelivery" name="shipmentEstimedelivery" wire:model="shipmentEstimedelivery" class=" py-1.5 pr-8 pl-3 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentEstimedelivery')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="shipmentPayableAtJob">Payable at</label>
                            <input type="text" id="shipmentPayableAtJob" name="shipmentPayableAtJob" wire:model="shipmentPayableAtJob" class=" block w-full rounded-md border-gray-300 shadow-sm py-1.5 pr-8 pl-3 focus:ring focus:ring-blue-200">
                            @error('shipmentPayableAtJob')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="mb-4" wire:ignore>
                            <label for="shipmentServices_type">Services Type</label>
                            <select name="" id="shipmentServices_type" wire:model="shipmentServices_type" class="block w-full rounded-md border-gray-300 shadow-sm  focus:ring focus:ring-blue-200">
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
                            <label for="shipmentCross_trade">Inco Terms</label>
                            <select name="shipmentCross_trade" id="shipmentCross_trade" wire:model="shipmentCross_trade" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Terms</option>
                                <option value="FOB">FOB</option>
                                <option value="CFR">CFR</option>
                                <option value="CIF">CIF</option>
                                <option value="CPT">CPT</option>
                                <option value="CIP">CIP</option>
                                <option value="FAS">FAS</option>
                            </select>
                        </div>
                        <div wire:ignore>
                            <label for="shipmentFreightTypeJob">Freight</label>
                            <select name="shipmentFreightTypeJob" id="shipmentFreightTypeJob" wire:model="shipmentFreightTypeJob" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                <option value="collect">Collect</option>
                                <option value="prepaid">Prepaid</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label for="shipmentRemarksJobDetailJobs">Remarks</label>
                            <textarea type="text" id="shipmentRemarksJobDetailJobs" name="shipmentRemarksJobDetailJobs" wire:model="shipmentRemarksJobDetailJobs" class=" block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>

                    </div>
                </div>
                @break
                @default
                <div class="bg-yellow-100 p-3 rounded-xl text-yellow-700">
                    Pilih tipe job yang valid
                </div>
                @endswitch

                <div class="flex justify-between mt-4">
                    <a href="{{ route('viewJob', ['id' => $job->id]) }}" class="mt-4 px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                        back
                    </a> <button wire:click.prevent="nextStep" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lanjut</button>
                </div>
            </div>
            @elseif($step === 2)
            <!-- <pre>{{$step,$shipmentType_job}}</pre> -->
            <!-- STEP 3: Container Info -->
            <div x-show="step === 2" x-transition x-cloak>
                <h2 class="text-lg font-semibold mb-3">Container</h2>
                @switch($shipmentType_job)
                @case('ocean_fcl_export')
                @case('ocean_fcl_import')
                @case('ocean_lcl_export')
                @case('ocean_lcl_import')
                <div class="p-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Client and Job Type Info -->
                        <div class="flex flex-col space-y-3  rounded-md">
                            <label>Client</label>
                            <input type="text" value="{{ $this->clientName->name ?? '-'  }}" readonly
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0"
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3  rounded-md">
                            <label>Job Type</label>
                            <input type="text" value="{{ strtoupper(str_replace('_', ' ', $shipmentType_job)) }}"
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0">
                            @error('seal_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div wire:ignore>
                            <label for="shipmentEmployee_id">Employee</label>
                            <select name="shipmentEmployee_id" id="shipmentEmployee_id" wire:model="shipmentEmployee_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                @foreach($employe as $e)
                                <option value="{{ $e->id }}">{{ $e->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3  gap-3">
                        <div class="w-full" wire:ignore>
                            <label for="shipmentCarrierAirline">Carrier</label>
                            <select name="shipmentCarrierAirline" id="shipmentCarrierAirline" wire:model="shipmentCarrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($carriers as $cr)
                                @if(in_array('carrier', $cr->roles))
                                <option value="{{ $cr->id }}">{{ $cr->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="shipmentFlightVesselName">Vessel Name</label>
                            <input type="text" id="shipmentFlightVesselName" name="shipmentFlightVesselName" wire:model="shipmentFlightVesselName" class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentFlightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-full">
                            <label for="shipmentFlightVesselNo">Voyage</label>
                            <input type="text" id="shipmentFlightVesselNo" name="shipmentFlightVesselNo" wire:model="shipmentFlightVesselNo" class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentFlightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="col-span-2 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="flex flex-col space-y-2">
                                    <label>No Of Packages</label>
                                    <input type="text" wire:model="shipmentNoOfPackages" placeholder="Enter No Of Packages"
                                        class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                </div>
                                <div class="flex flex-col space-y-2" wire:ignore>
                                    <label>Type Of Packages</label>
                                    <select id="shipmentTypeOfPackages" class="block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value=""></option>
                                        <option value="packages">Packages</option>
                                        <option value="cartons">Cartons</option>
                                        <option value="rolls">Rolls</option>
                                        <option value="pallets">Pallets</option>
                                        <option value="crates">Crates</option>
                                        <option value="boxes">Boxes</option>
                                        <option value="drums">Drums</option>
                                        <option value="bags">Bags</option>
                                        <option value="bundles">Bundles</option>
                                        <option value="containers">Containers</option>
                                        <option value="pieces">Pieces</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2 mb-4" wire:ignore>
                            <label for="shipmentContainerDeliveryAgent">Delivery Agent</label>
                            <select name="shipmentContainerDeliveryAgent" id="shipmentContainerDeliveryAgent" wire:model="shipmentContainerDeliveryAgent"
                                class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select agent</option>
                                @foreach($deliveryAgent as $da)
                                <option value="{{ $da->id }}">{{ $da->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-2">
                            <label>Gross Weight</label>
                            <input type="text" placeholder="Enter Gross weight" wire:model="shipmentGrossWeight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Of Gross Weight</label>
                            <select id="shipmentTypeOfGrossWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="KGS">KGS</option>
                            </select>
                        </div>

                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-2">
                            <label>Volume Weight</label>
                            <input type="text" wire:model="shipmentVolumeWeight" placeholder="Enter Gross weight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Of Volume Weight</label>
                            <select id="shipmentTypeOfVolumeWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="KGS">KGS</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-2">
                            <label> Volume </label>
                            <input type="text" wire:model="shipmentVolume" placeholder="Enter Gross weight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Volume</label>
                            <select id="typeOfShipmentVolume" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="CBM">CBM</option>
                            </select>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label>Chargeable Weight</label>
                            <input type="text" placeholder="Enter Chargeable Weight" wire:model="shipmentChargableWeight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>

                        <div class="flex flex-col space-y-2">
                            <label>HS Code</label>
                            <input type="text" placeholder="Enter HS Code" wire:model="ShipmentHsCode"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2 ">
                            <label>HS Description</label>
                            <textarea placeholder="Enter Hs Description" rows="3" wire:model="shipmentHsCodeDesc"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label>Remarks</label>
                            <textarea placeholder="Enter remarks" rows="3" wire:model="shipmentContainerRemarks"
                                class=" block w-ful py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                    </div>
                </div>
                @break
                @case('air_inbound')
                @case('air_outbound')
                <div class="p-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Client and Job Type Info -->
                        <div class="flex flex-col space-y-3  rounded-md">
                            <label>Client</label>
                            <input type="text" value="{{ $this->clientName->name ?? '-'  }}" readonly
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0"
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3  rounded-md">
                            <label>Job Type</label>
                            <input type="text" value="{{ strtoupper(str_replace('_', ' ', $shipmentType_job)) }}"
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0">
                            @error('seal_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div wire:ignore>
                            <label for="shipmentEmployee_id">Employee</label>
                            <select name="shipmentEmployee_id" id="shipmentEmployee_id" wire:model="shipmentEmployee_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                @foreach($employe as $e)
                                <option value="{{ $e->id }}">{{ $e->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3  gap-3">
                        <div class="w-full" wire:ignore>
                            <label for="shipmentCarrierAirline">Airlines</label>
                            <select name="shipmentCarrierAirline" id="shipmentCarrierAirline" wire:model="shipmentCarrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($airlines as $air)
                                <option value="{{ $air->id }}">{{ $air->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="shipmentFlightVesselName">Flight Name</label>
                            <input type="text" id="shipmentFlightVesselName" name="shipmentFlightVesselName" wire:model="shipmentFlightVesselName" class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentFlightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-full">
                            <label for="shipmentFlightVesselNo">Flight No</label>
                            <input type="text" id="shipmentFlightVesselNo" name="shipmentFlightVesselNo" wire:model="shipmentFlightVesselNo" class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentFlightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="col-span-2 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="flex flex-col space-y-2">
                                    <label>No Of Packages</label>
                                    <input type="text" wire:model="shipmentNoOfPackages" placeholder="Enter No Of Packages"
                                        class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                </div>
                                <div class="flex flex-col space-y-2" wire:ignore>
                                    <label>Type Of Packages</label>
                                    <select id="shipmentTypeOfPackages" class="block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value=""></option>
                                        <option value="packages">Packages</option>
                                        <option value="cartons">Cartons</option>
                                        <option value="rolls">Rolls</option>
                                        <option value="pallets">Pallets</option>
                                        <option value="crates">Crates</option>
                                        <option value="boxes">Boxes</option>
                                        <option value="drums">Drums</option>
                                        <option value="bags">Bags</option>
                                        <option value="bundles">Bundles</option>
                                        <option value="containers">Containers</option>
                                        <option value="pieces">Pieces</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2 mb-4" wire:ignore>
                            <label for="shipmentContainerDeliveryAgent">Delivery Agent</label>
                            <select name="shipmentContainerDeliveryAgent" id="shipmentContainerDeliveryAgent" wire:model="shipmentContainerDeliveryAgent"
                                class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select agent</option>
                                @foreach($deliveryAgent as $da)
                                <option value="{{ $da->id }}">{{ $da->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-2">
                            <label>Gross Weight</label>
                            <input type="text" placeholder="Enter Gross weight" wire:model="shipmentGrossWeight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Of Gross Weight</label>
                            <select id="shipmentTypeOfGrossWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="KGS">KGS</option>
                            </select>
                        </div>

                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-2">
                            <label>Volume Weight</label>
                            <input type="text" wire:model="shipmentVolumeWeight" placeholder="Enter Gross weight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Of Volume Weight</label>
                            <select id="shipmentTypeOfVolumeWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="KGS">KGS</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-2">
                            <label> Volume </label>
                            <input type="text" wire:model="shipmentVolume" placeholder="Enter Gross weight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Volume</label>
                            <select id="typeOfShipmentVolume" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="CBM">CBM</option>
                            </select>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label>Chargeable Weight</label>
                            <input type="text" placeholder="Enter Chargeable Weight" wire:model="shipmentChargableWeight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>

                        <div class="flex flex-col space-y-2">
                            <label>HS Code</label>
                            <input type="text" placeholder="Enter HS Code" wire:model="ShipmentHsCode"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2 ">
                            <label>HS Description</label>
                            <textarea placeholder="Enter Hs Description" rows="3" wire:model="shipmentHsCodeDesc"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label>Remarks</label>
                            <textarea placeholder="Enter remarks" rows="3" wire:model="shipmentContainerRemarks"
                                class=" block w-ful py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                    </div>
                </div>
                @break
                @case('domestic_transportation')
                @case('trucking')
                @case('logistics')
                <div class="p-3">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <!-- Client and Job Type Info -->
                        <div class="flex flex-col space-y-3  rounded-md">
                            <label>Client</label>
                            <input type="text" value="{{ $this->clientName->name ?? '-'  }}" readonly
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0"
                                placeholder="Nama client akan muncul otomatis">
                            @error('container_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3  rounded-md">
                            <label>Job Type</label>
                            <input type="text" value="{{ strtoupper(str_replace('_', ' ', $shipmentType_job)) }}"
                                class="text-sm font-bold block w-full focus:ring-0 focus:outline-none border-0">
                            @error('seal_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div wire:ignore>
                            <label for="shipmentEmployee_id">Employee</label>
                            <select name="shipmentEmployee_id" id="shipmentEmployee_id" wire:model="shipmentEmployee_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value=""></option>
                                @foreach($employe as $e)
                                <option value="{{ $e->id }}">{{ $e->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3  gap-3">
                        <div class="w-full" wire:ignore>
                            <label for="shipmentCarrierAirline">Airlines</label>
                            <select name="shipmentCarrierAirline" id="shipmentCarrierAirline" wire:model="shipmentCarrierAirline" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Carrier</option>
                                @foreach($agents as $ag)
                                <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="shipmentFlightVesselName">Flight Name</label>
                            <input type="text" id="shipmentFlightVesselName" name="shipmentFlightVesselName" wire:model="shipmentFlightVesselName" class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentFlightVesselName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="w-full">
                            <label for="shipmentFlightVesselNo">Flight No</label>
                            <input type="text" id="shipmentFlightVesselNo" name="shipmentFlightVesselNo" wire:model="shipmentFlightVesselNo" class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipmentFlightVesselNo')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="col-span-2 mb-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="flex flex-col space-y-2">
                                    <label>No Of Packages</label>
                                    <input type="text" wire:model="shipmentNoOfPackages" placeholder="Enter No Of Packages"
                                        class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                </div>
                                <div class="flex flex-col space-y-2" wire:ignore>
                                    <label>Type Of Packages</label>
                                    <select id="shipmentTypeOfPackages" class="block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value=""></option>
                                        <option value="packages">Packages</option>
                                        <option value="cartons">Cartons</option>
                                        <option value="rolls">Rolls</option>
                                        <option value="pallets">Pallets</option>
                                        <option value="crates">Crates</option>
                                        <option value="boxes">Boxes</option>
                                        <option value="drums">Drums</option>
                                        <option value="bags">Bags</option>
                                        <option value="bundles">Bundles</option>
                                        <option value="containers">Containers</option>
                                        <option value="pieces">Pieces</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col space-y-2 mb-4" wire:ignore>
                            <label for="shipmentContainerDeliveryAgent">Delivery Agent</label>
                            <select name="shipmentContainerDeliveryAgent" id="shipmentContainerDeliveryAgent" wire:model="shipmentContainerDeliveryAgent"
                                class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Select agent</option>
                                @foreach($deliveryAgent as $da)
                                <option value="{{ $da->id }}">{{ $da->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-2">
                            <label>Gross Weight</label>
                            <input type="text" placeholder="Enter Gross weight" wire:model="shipmentGrossWeight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Of Gross Weight</label>
                            <select id="shipmentTypeOfGrossWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="KGS">KGS</option>
                            </select>
                        </div>

                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-2">
                            <label>Volume Weight</label>
                            <input type="text" wire:model="shipmentVolumeWeight" placeholder="Enter Gross weight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Of Volume Weight</label>
                            <select id="shipmentTypeOfVolumeWeight" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="KGS">KGS</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-col space-y-2">
                            <label> Volume </label>
                            <input type="text" wire:model="shipmentVolume" placeholder="Enter Gross weight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2" wire:ignore>
                            <label>Type Volume</label>
                            <select id="typeOfShipmentVolume" class="block w-full rounded-md border-gray-300 shadow-sm">
                                <option value=""></option>
                                <option value="CBM">CBM</option>
                            </select>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label>Chargeable Weight</label>
                            <input type="text" placeholder="Enter Chargeable Weight" wire:model="shipmentChargableWeight"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>

                        <div class="flex flex-col space-y-2">
                            <label>HS Code</label>
                            <input type="text" placeholder="Enter HS Code" wire:model="ShipmentHsCode"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        </div>
                        <div class="flex flex-col space-y-2 ">
                            <label>HS Description</label>
                            <textarea placeholder="Enter Hs Description" rows="3" wire:model="shipmentHsCodeDesc"
                                class=" block w-full py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <label>Remarks</label>
                            <textarea placeholder="Enter remarks" rows="3" wire:model="shipmentContainerRemarks"
                                class=" block w-ful py-1.5 pr-8 pl-3 rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200"></textarea>
                        </div>
                    </div>
                </div>
                @endswitch
                <div class="flex justify-between mt-4">
                    <button wire:click.prevent="previousStep" class="px-4 py-2 border rounded">Kembali</button>
                    <button wire:click.prevent="nextStep" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lanjut</button>
                </div>
            </div>
            @elseif($step === 3)
            <!-- STEP 4: Conclusion -->
            <div x-show="step === 3" x-cloak x-transition>
                <div class="space-y-2">
                    <div class="font-bold text-2xl text-center mb-4">
                        <h2 class="m-2">Detail Shipments</h2>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-items grid grid-cols-3 ">
                            <p class="col-span-1"><strong>Tipe Shipments</strong> </p>
                            <p class="col-span-2">: {{ strtoupper(str_replace('_', ' ', $shipmentType_job)) }} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Client </strong></p>
                            <p class="col-span-2">: {{$this->clientName->name ?? '-'}}</p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>No. Shipments </strong> </p>
                            <p class="col-span-2 font-bold">: {{ $shipment_id }} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3 ">
                            <p class="col-span-1"><strong>Shipper</strong> </p>
                            <p class="col-span-2 uppercase">: {{$this->shipperName->name ?? ''}}</p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Consignee</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $this->consigneeName->name ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Notify</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $this->notifyName->name ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Carrier</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $this->carrierName->name ?? '' }} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Carrier Agent</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $this->carrierAgentName->name ?? '' }} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Delivery Agent</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $this->deliveryAgentName->name ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Freight</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentFreightTypeJob ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Inco Terms</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentCross_trade ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Cross Trade</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentCross_trade ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Port of loading</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentPort_of_loading ?? '' }} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Port of Receipt</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentPort_of_receipt ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Place of receipt</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentPlace_of_receipt ?? '' }} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Port of discharge</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentPort_of_discharge ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Place of delivery</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentPlace_of_delivery ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Port of final</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentPort_of_final ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Payable At</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentPayableAtJob ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Services Type</strong> </p>
                            <p class="col-span-2 uppercase">: {{ $shipmentServices_type ?? ''}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3"><strong>Remarks</strong>
                            <p class="col-span-2 uppercase">: {{ $shipmentRemarksJobDetailJobs ?? ''}} </p>
                        </div>
                    </div>
                    <div class="font-bold text-2xl text-center mb-4">Container</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Carrier</strong> </p>
                            <p class="col-span-2">: {{ $this->containerCarrierName->name ?? ''}}</p>
                        </div>
                        <!-- <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Carrier</strong> </p>
                            <p class="col-span-2">: {{ $this->containerCarrierName->name ?? ''}}</p>
                        </div> -->
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Vessel Name</strong> </p>
                            <p class="col-span-2">: {{ $shipmentFlightVesselName }}</p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Voyage</strong> </p>
                            <p class="col-span-2">: {{ $shipmentFlightVesselNo }}</p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>No of Packages</strong> </p>
                            <p class="col-span-2">: {{ $shipmentNoOfPackages }} {{$shipmentTypeOfPackages}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Gross Weight</strong> </p>
                            <p class="col-span-2">: {{ $shipmentGrossWeight }} {{$shipmentTypeOfGrossWeight}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Volume Weight</strong> </p>
                            <p class="col-span-2">: {{ $shipmentVolumeWeight }} {{$shipmentTypeOfVolumeWeight}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Volume</strong> </p>
                            <p class="col-span-2">: {{ $shipmentVolume }} {{$typeOfShipmentVolume}} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Chargable Weight</strong> </p>
                            <p class="col-span-2">: {{ $shipmentChargableWeight }} </p>
                        </div>
                        <div class="flex flex-items grid grid-cols-3">
                            <p class="col-span-1"><strong>Remarks</strong> </p>
                            <p class="col-span-2">: {{ $shipmentContainerRemarks }} </p>
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
                sel: '#shipmentDeliveryAgent',
                model: 'shipmentDeliveryAgent',
                placeholder: 'Select Agent '
            },
            {
                sel: '#shipmentOriginAgent',
                model: 'shipmentOriginAgent',
                placeholder: 'Select Agent '
            },
            {
                sel: '#shipmentCarrierAgent',
                model: 'shipmentCarrierAgent',
                placeholder: 'Select Carrier Agent '
            },
            {
                sel: '#shipmentShipper_id',
                model: 'shipmentShipper_id',
                placeholder: 'Select shippers'
            },
            {
                sel: '#shipmentConsignee_id',
                model: 'shipmentConsignee_id',
                placeholder: 'Select consignee'
            },
            {
                sel: '#shipmentNotify_id',
                model: 'shipmentNotify_id',
                placeholder: 'Select notify'
            },
            {
                sel: '#shipmentCarrierAirline',
                model: 'shipmentCarrierAirline',
                placeholder: 'Select airlines'
            },
            {
                sel: '#shipmentClient_id',
                model: 'shipmentClient_id',
                placeholder: 'Select Client'
            },
            {
                sel: '#shipmentClient_address',
                model: 'shipmentClient_address',
                placeholder: 'Select address'
            },
            {
                sel: '#shipmentCross_trade',
                model: 'shipmentCross_trade',
                placeholder: 'Select Inco Terms'
            },
            {
                sel: '#shipmentServices_type',
                model: 'shipmentServices_type',
                placeholder: 'Select Services Type'
            },
            {
                sel: '#shipmentCross_trade',
                model: 'shipmentCross_trade',
                placeholder: 'Select is it cross trade? '
            },

            {
                sel: '#shipmentFreightTypeJob',
                model: 'shipmentFreightTypeJob',
                placeholder: 'Select Freight '
            },
            // Container Section
            {
                sel: '#typeOfShipmentVolume',
                model: 'typeOfShipmentVolume',
                placeholder: 'Select Type Of Volume Weight '
            },
            {
                sel: '#shipmentEmployee_id',
                model: 'shipmentEmployee_id',
                placeholder: 'Select Employee '
            },
            {
                sel: '#shipmentTypeOfPackages',
                model: 'shipmentTypeOfPackages',
                placeholder: 'Select type of packages '
            },
            {
                sel: '#shipmentContainerDeliveryAgent',
                model: 'shipmentContainerDeliveryAgent',
                placeholder: 'Select Delivery Agent '
            },
            {
                sel: '#shipmentTypeOfGrossWeight',
                model: 'shipmentTypeOfGrossWeight',
                placeholder: 'Select type of gross weight '
            },
            {
                sel: '#shipmentTypeOfVolumeWeight',
                model: 'shipmentTypeOfVolumeWeight',
                placeholder: 'Select type of volume weight '
            },
            {
                sel: '#containerShipmentCarrierAirline',
                model: 'containerShipmentCarrierAirline',
                placeholder: 'Select Carrier '
            }, {
                sel: '#typeOfShipmentVolume',
                model: 'typeOfShipmentVolume',
                placeholder: 'Select Type Volume'
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
        init(selector = '.port-select', type_job = 'air_outbound') {
            const isAir = type_job.startsWith('air');
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
                        const id = label.split(' - ')[0];
                        const option = new Option(label, id, true, true);
                        $(select).append(option).trigger('change');
                    }

                    // Bind change
                    $(select).off('change.lw').on('change.lw', function() {
                        const selectedId = $(this).select2('data')[0]?.id || ''; // INI YANG DIKIRIM
                        if (model && typeof $wire !== 'undefined') {
                            $wire.set(model, selectedId);
                        }
                    });

                } else {
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
                                                const term = params.data.term?.toUpperCase() || '';
                                                const results = data
                                                    .filter(port => {
                                                        const name = port.name?.toUpperCase() || '';
                                                        const code = port.code?.toUpperCase() || '';
                                                        const country = port.country?.toUpperCase() || '';
                                                        return name.includes(term) || code.includes(term) || country.includes(term);
                                                    })
                                                    .filter(port => !!port.code)
                                                    .slice(0, 20)
                                                    .map(port => ({
                                                        id: `${port.name.toUpperCase()}, ${port.country.toUpperCase()}`,
                                                        text: `${port.name} (${port.code}) - ${port.country}`
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

                            // Restore selected
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
            window.PortSelect2.init();
        });
    });
</script>
@endscript
@endpush