<div class="p-6 max mx-auto [&::-webkit-scrollbar]:hidden">
    <form action="" wire:submit="submitForm">
        <div>
            <div x-data="{
    step: @entangle('step'),
    type_job: @entangle('type_job'),
    init() {
        this.$nextTick(() => window.reinitSelect2());
        this.$nextTick(() => window.PortSelect2.init());

        this.$watch('step', () => {
            this.$nextTick(() => window.reinitSelect2());
            this.$nextTick(() => window.PortSelect2.init());
        });

        this.$watch('type_job', () => {
            this.$nextTick(() => window.reinitSelect2());
            this.$nextTick(() => window.PortSelect2.init());
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
                <div class="flex flex-col space-y-3 rounded-md mb-4" wire:ignore>
                    <label class="font-bold">Client</label>
                    <select wire:model="client_id" id="client_id">
                        <option value="">Pilih Client</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                        @endforeach
                    </select>
                    @error('client_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                </div>
                <div class="grid grid-cols-3 gap-3">
                    @foreach([
                    'ocean_fcl_export' => 'Ocean FCL Export',
                    'ocean_fcl_import' => 'Ocean FCL Import',
                    'ocean_lcl_export' => 'Ocean LCL Export',
                    'ocean_lcl_import' => 'Ocean LCL Import',
                    'air_export' => 'Air Export',
                    'air_import' => 'Air Import',
                    'trucking' => 'Trucking',
                    'logistics' => 'Logistics'
                    ] as $key => $label)
                    <label class="flex items-center space-x-2 cursor-pointer border p-2 rounded border-gray-400 hover:bg-gray-100 rounded-lg">
                        <input type="radio" value="{{ $key }}" x-model="type_job" wire:model="type_job" class="form-radio text-blue-600">
                        <span>{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
                @error('type_job')<div class="text-red-500 mt-2">{{ $message }}</div>@enderror
                <button wire:click.prevent="nextStep" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    Lanjut
                </button>

            </div>

            <!-- STEP 2: Isi Detail Job -->
            <div x-show="step === 2" x-transition>
                <h2 class="text-lg font-semibold mb-3">Detail Job: {{ strtoupper(str_replace('_', ' ', $type_job)) }}</h2>

                @switch($type_job)
                @case('ocean_fcl_export')
                <div>
                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>No. Job</label>
                            <input type="text" wire:model="job_name" placeholder="Enter Job Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('job_name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>MBL</label>
                            <input type="text" wire:model="shipment_no" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipment_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col space-y-3 rounded-md">
                            <label>Shipment ID</label>
                            <input type="text" wire:model="shipment_id" placeholder="Enter Shipment ID" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('shipment_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class=" grid grid-cols-4 gap-6">
                        <!-- Mother Vessel -->
                        <div class="mb-4=">
                            <label for="ocean_vessel_mother">Mother Vessel</label>
                            <input type="text" id="ocean_vessel_mother" name="ocean_vessel_mother" wire:model="ocean_vessel_mother" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('ocean_vessel_mother')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Feeder Vessel -->
                        <div class="mb-4">
                            <label for="ocean_vessel_feeder">Feeder Vessel</label>
                            <input type="text" id="ocean_vessel_feeder" name="ocean_vessel_feeder" wire:model="ocean_vessel_feeder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('ocean_vessel_feeder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- ETA -->
                        <div class="mb-4">
                            <label for="estimearrival">ETA / Estimate Time Arrival</label>
                            <input type="date" id="estimearrival" name="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('estimearrival')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- ETD -->
                        <div class="mb-4">
                            <label for="estimedelivery">ETD / Estimate Time Departure</label>
                            <input type="date" id="estimedelivery" name="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            @error('estimedelivery')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>


                    <!-- Organization -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Shipper -->
                        <div class="mb-4" wire:ignore>
                            <label for="shipper">Shipper</label>
                            <select wire:model="shipper_id" id="shipper" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Shipper</option>
                                @foreach($shippers as $s)
                                @if(in_array('shipper', $s->roles))
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Consignee -->
                        <div class="mb-4" wire:ignore>
                            <label for="consignee">Consignee</label>
                            <select wire:model="consignee_id" id="consignee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Consignee</option>
                                @foreach($consignees as $c)
                                @if(in_array('consignee', $c->roles))
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Notify -->
                        <div class="mb-4" wire:ignore>
                            <label for="notify_id">Notify</label>
                            <select wire:model="notify_id" id="notify_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                                <option value="">Select Notify</option>
                                @foreach($notifies as $n)
                                @if(in_array('notify', $n->roles))
                                <option value="{{ $n->id }}">{{ $n->name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Select Port -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4 border rounded-md p-4 border-1 border-gray-300">
                        <!-- Port Of Loading -->
                        <div class="port-container" data-model="port_of_loading" data-radio-name="inputTypeLoading" wire:ignore wire:change="port_of_loading">
                            <h2 class="text-lg font-semibold">Port Of Loading</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-4">
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
                        <!-- Place Of Receipts -->
                        <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore wire:change="place_of_receipt">
                            <h2 class="text-lg font-semibold">Place Of Receipts</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-4">
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
                        <!-- Port Of Discharge -->
                        <div class="port-container" data-model="port_of_discharge" data-radio-name="inputTypeDischarge" wire:ignore>
                            <h2 class="text-lg font-semibold">Port Of Discharge</h2>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                                <div class="flex items-center gap-4">
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
                    </div>
                </div>

                @break

                @case('ocean_fcl_import')
                <div class="grid grid-cols-3 gap-3 mb-4">
                    <div class="flex flex-col space-y-3 rounded-md">
                        <label>No. Job</label>
                        <input type="text" wire:model="job_name" placeholder="Enter Job Name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        @error('job_name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="flex flex-col space-y-3 rounded-md">
                        <label>MBL</label>
                        <input type="text" wire:model="shipment_no" placeholder="Enter MBL" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        @error('shipment_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="flex flex-col space-y-3 rounded-md">
                        <label>Shipment ID</label>
                        <input type="text" wire:model="shipment_id" placeholder="Enter Shipment ID" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        @error('shipment_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class=" grid grid-cols-4 gap-6">
                    <!-- Mother Vessel -->
                    <div class="mb-4=">
                        <label for="ocean_vessel_mother">Mother Vessel</label>
                        <input type="text" id="ocean_vessel_mother" name="ocean_vessel_mother" wire:model="ocean_vessel_mother" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        @error('ocean_vessel_mother')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Feeder Vessel -->
                    <div class="mb-4">
                        <label for="ocean_vessel_feeder">Feeder Vessel</label>
                        <input type="text" id="ocean_vessel_feeder" name="ocean_vessel_feeder" wire:model="ocean_vessel_feeder" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        @error('ocean_vessel_feeder')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ETA -->
                    <div class="mb-4">
                        <label for="estimearrival">ETA / Estimate Time Arrival</label>
                        <input type="date" id="estimearrival" name="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        @error('estimearrival')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ETD -->
                    <div class="mb-4">
                        <label for="estimedelivery">ETD / Estimate Time Departure</label>
                        <input type="date" id="estimedelivery" name="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                        @error('estimedelivery')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <!-- Organization -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Shipper -->
                    <div class="mb-4" wire:ignore>
                        <label for="shipper">Shipper</label>
                        <select wire:model="shipper_id" id="shipper" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            <option value="">Select Shipper</option>
                            @foreach($shippers as $s)
                            @if(in_array('shipper', $s->roles))
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Consignee -->
                    <div class="mb-4" wire:ignore wire:key="consignee_id">
                        <label for="consignee">Consignee</label>
                        <select wire:model="consignee_id" id="consignee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            <option value="">Select Consignee</option>
                            @foreach($consignees as $c)
                            @if(in_array('consignee', $c->roles))
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Notify -->
                    <div class="mb-4" wire:ignore>
                        <label for="notify_id">Notify</label>
                        <select wire:model="notify_id" id="notify_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
                            <option value="">Select Notify</option>
                            @foreach($notifies as $n)
                            @if(in_array('notify', $n->roles))
                            <option value="{{ $n->id }}">{{ $n->name }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-4 border rounded-md p-4 border-1 border-gray-300">
                    <!-- Port Of Loading -->
                    <div class="port-container" data-model="port_of_loading" data-radio-name="inputTypeLoading" wire:ignore wire:change="port_of_loading">
                        <h2 class="text-lg font-semibold">Port Of Loading</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                            <div class="flex items-center gap-4">
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
                    <!-- Place Of Receipts -->
                    <div class="port-container" data-model="place_of_receipt" data-radio-name="inputTypeReceipt" wire:ignore wire:change="place_of_receipt">
                        <h2 class="text-lg font-semibold">Place Of Receipts</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                            <div class="flex items-center gap-4">
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
                    <!-- Port Of Discharge -->
                    <div class="port-container" data-model="port_of_discharge" data-radio-name="inputTypeDischarge" wire:ignore>
                        <h2 class="text-lg font-semibold">Port Of Discharge</h2>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Select or Input Port</label>
                            <div class="flex items-center gap-4">
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
                </div>
                @break
                @case('trucking')
                <div class="flex flex-col space-y-3 rounded-md">
                    <label>Detail Trucking</label>
                    <input type="text" wire:model="" placeholder="Rute, kendaraan, driver, dll">
                    @error('')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                </div>
                @break

                @default
                <div class="bg-yellow-100 p-3 rounded">
                    Pilih tipe job yang valid
                </div>
                @endswitch

                <div class="flex justify-between mt-4">
                    <button wire:click.prevent="previousStep" class="px-4 py-2 border rounded">Kembali</button>
                    <button wire:click.prevent="nextStep" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lanjut</button>
                </div>
            </div>

            <!-- STEP 3: Container Info -->
            <div x-show="step === 3" x-transition x-cloak>
                <h2 class="text-lg font-semibold mb-3">Container</h2>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex flex-col space-y-3 rounded-md">
                        <input type="text" wire:model="container_number" placeholder="Container Number">
                        @error('container_number')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="flex flex-col space-y-3 rounded-md">
                        <input type="text" wire:model="container_size" placeholder="Size (20', 40', dll)">
                        @error('container_size')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="flex justify-between mt-4">
                    <button wire:click.prevent="previousStep" class="px-4 py-2 border rounded">Kembali</button>
                    <button wire:click.prevent="nextStep" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lanjut</button>
                </div>
            </div>

            <!-- STEP 4: Conclusion -->
            <div x-show="step === 4" x-transition x-cloak>
                <h2 class="text-lg font-semibold mb-3">Kesimpulan</h2>
                <div class="space-y-2">
                    <p><strong>Tipe Job:</strong> {{ strtoupper(str_replace('_', ' ', $type_job)) }}</p>

                    @switch($type_job)
                    @case('ocean_fcl_export')
                    <p><strong>No. Job:</strong> {{ $job_name }}</p>
                    <p><strong>MBL:</strong> {{ $shipment_no }}</p>
                    <p><strong>Shipment ID:</strong> {{ $shipment_id }}</p>
                    @break

                    @case('trucking')
                    <p><strong>Detail Trucking:</strong> {{ $trucking_detail }}</p>
                    @break
                    @endswitch


                </div>

                <div class="flex justify-between mt-4">
                    <button wire:click.prevent="previousStep" class="px-4 py-2 border rounded">Kembali</button>
                    <button wire:click.prevent="submitForm" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Submit</button>
                </div>
            </div>

        </div>


    </form>

</div>
</div>
@script()
<script>
    $(document).ready(function() {
        $('#client_id').select2({
            placeholder: "Select roles",
            allowClear: true,
            theme: 'tailwindcss-3'
        });
        $('#client_id').on('change', function() {
            let data = $(this).val();
            console.log(data);
            // $wire.set('roles',data,false);
            $wire.client_id = data;
        });
    });
</script>
@endscript

@push('scripts')
@script()
<script>
    // Untuk shipper, consignee, notify
    window.reinitSelect2 = () => {
        [{
                sel: '#shipper',
                model: 'shipper_id',
                placeholder: 'Pilih Shipper'
            },
            {
                sel: '#consignee',
                model: 'consignee_id',
                placeholder: 'Pilih Consignee'
            },
            {
                sel: '#notify_id',
                model: 'notify_id',
                placeholder: 'Pilih Notify Party'
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

            $el.off('change.lw').on('change.lw', function() {
                $wire.set(model, $(this).val());
                console.log($(this).val());
            });
        });
    };

    // Untuk port-select (versi modular, bisa digunakan ulang)
    window.PortSelect2 = {
        init(selector = '.port-select', endpoint = '/data/ports.json') {
            fetch(endpoint)
                .then(res => res.json())
                .then(data => {
                    document.querySelectorAll(selector).forEach(select => {
                        // Kosongkan dan isi ulang opsi
                        select.innerHTML = '<option value="" disabled selected>Select a port...</option>';
                        data.forEach(port => {
                            const option = document.createElement('option');
                            option.value = `${port.name} - ${port.code}`;
                            option.textContent = `${port.name} - ${port.code}`;
                            select.appendChild(option);
                        });

                        // Destroy Select2 jika sudah diinisialisasi sebelumnya
                        if ($(select).hasClass("select2-hidden-accessible")) {
                            $(select).select2('destroy');
                        }

                        // Inisialisasi Select2 baru
                        $(select).select2({
                            placeholder: "Select a port",
                            allowClear: true,
                            theme: "tailwindcss-3",
                            width: "100%"
                        });

                        console.log('Port select2 initialized');

                        // Sync ke Livewire
                        $(select).on('change', function() {
                            const model = select.getAttribute('wire:model');
                            const value = $(this).val();
                            console.log('Port selected:', value);
                        });
                    });

                    // Handle toggle radio di setiap .port-container
                    document.querySelectorAll('.port-container').forEach(container => {
                        container.querySelectorAll('input[type="radio"]').forEach(radio => {
                            radio.addEventListener('change', function() {
                                const selectContainer = container.querySelector('.select-container');
                                const inputContainer = container.querySelector('.input-container');
                                if (this.value === 'select') {
                                    selectContainer.classList.remove('hidden');
                                    inputContainer.classList.add('hidden');
                                } else {
                                    selectContainer.classList.add('hidden');
                                    inputContainer.classList.remove('hidden');
                                }
                            });
                        });
                    });

                });
        }
    };


    // Livewire hooks
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