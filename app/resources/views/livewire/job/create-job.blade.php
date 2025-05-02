<div class="p-6 max mx-auto [&::-webkit-scrollbar]:hidden">
    <form action="" wire:submit="submitForm">
        <div>
            <div x-data="{ step: @entangle('step') }" class="p-6 space-y-6">

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
                <div class="flex flex-col space-y-3 rounded-md mb-4">
                    <label class="font-bold">Client</label>
                    <select wire:model="client_id" class="border p-2 rounded">
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
                        <input type="radio" wire:model="type_job" value="{{ $key }}" x-model="type_job"
                            class="text-blue-600">
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
            <div x-show="step === 2" x-transition x-cloak>
                <h2 class="text-lg font-semibold mb-3">Detail Job: {{ strtoupper(str_replace('_', ' ', $type_job)) }}</h2>

                @switch($type_job)
                @case('ocean_fcl_export')
                <div class="grid grid-cols-3 gap-3">
                    <div class="flex flex-col space-y-3 rounded-md">
                        <label>No. Job</label>
                        <input type="text" wire:model="job_name" placeholder="Enter Job Name">
                        @error('job_name')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="flex flex-col space-y-3 rounded-md">
                        <label>MBL</label>
                        <input type="text" wire:model="shipment_no" placeholder="Enter MBL">
                        @error('shipment_no')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                    <div class="flex flex-col space-y-3 rounded-md">
                        <label>Shipment ID</label>
                        <input type="text" wire:model="shipment_id" placeholder="Enter Shipment ID">
                        @error('shipment_id')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
                    </div>
                </div>
                @break

                @case('trucking')
                <div class="flex flex-col space-y-3 rounded-md">
                    <label>Detail Trucking</label>
                    <input type="text" wire:model="trucking_detail" placeholder="Rute, kendaraan, driver, dll">
                    @error('trucking_detail')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
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