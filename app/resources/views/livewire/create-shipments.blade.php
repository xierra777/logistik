<form wire:submit="save">
  <div class="space-y-12">
    <div class="border-b border-gray-900/10 pb-12">
      <h2 class="text-base/7 font-semibold text-gray-900 mb-5">Tambahkan Data Kapal disini</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">

        <label for="hs-radio-air-inbound" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
          <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" wire:model="jobType" value="airInbound" id="hs-radio-air-inbound">
          <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Air Inbound</span>
        </label>
        <label for="hs-radio-air-outbound" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
          <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 ring-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="airOutbound" wire:model="jobType" id="hs-radio-air-outbound">
          <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Air Outbound</span>
        </label>
        <label for="hs-radio-domestic-transport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
          <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" wire:model="jobType" value="domesticTransportation" id="hs-radio-domestic-transport">
          <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Domestic Transportation</span>
        </label>
        <label for="hs-radio-local-truck" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
          <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="localTruck" wire:model="jobType" id="hs-radio-local-truck">
          <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Local Truck</span>
        </label>
        <label for="hs-radio-logistics" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
          <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="Logistics" wire:model="jobType" id="hs-radio-logistics">
          <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Logistics</span>
        </label>
        <label for="hs-radio-OceanFclExport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
          <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="OceanFclExport" wire:model="jobType" id="hs-radio-OceanFclExport">
          <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Ocean FCL Export</span>
        </label>
        <label for="hs-radio-OceanFclImport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
          <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="OceanFclImport" wire:model="jobType" id="hs-radio-OceanFclImport">
          <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Ocean FCL Import</span>
        </label>
        <label for="hs-radio-OceanLclBulkExport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
          <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="OceanLclBulkExport" wire:model="jobType" id="hs-radio-OceanLclBulkExport">
          <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Ocean LCL/Bulk Export</span>
        </label>
        <label for="hs-radio-OceanLclBulkImport" class="flex p-3 w-full bg-white border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400">
          <input type="radio" name="hs-radio-in-form" class="border border-gray-500 shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 checked:border-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" value="OceanLclBulkImport" wire:model="jobType" id="hs-radio-OceanLclBulkImport">
          <span class="text-sm text-gray-500 ms-3 dark:text-neutral-400 uppercase font-bold">Ocean LCL/Bulk Import</span>
        </label>
      </div>
      <div class="mb-4 grid grid-cols-4 gap-6">

        <!-- Shipment ID -->
        <div>
          <label for="shipment_id" class="block text-sm font-medium text-gray-700">
            MAWB/MBL <span class="text-red-500">*</span>
          </label>
          <input
            wire:model="shipment_id"
            type="text"
            id="shipment_id"
            name="shipment_id"
            required
            class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
          @error('shipment_id')
          <span class="text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>

        <!-- Shipment Number -->
        <div>
          <label for="shipment_no" class="block text-sm font-medium text-gray-700">
            No Job <span class="text-red-500">*</span>
          </label>
          <input
            wire:model="shipment_no"
            type="text"
            id="shipment_no"
            name="shipment_no"
            required
            class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
          @error('shipment_no')
          <span class="text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>
        <div>
          <label for="servicesType" class="block text-sm font-medium text-gray-700">
            Service Type <span class="text-red-500">*</span>
          </label>
          <select name="servicesType" id="servicesType" wire:model="servicesType" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            <option value=""></option>
            <option value="CY-CY">CY-CY</option>
            <option value="FCL-FCL">FCL-FCL</option>
            <option value="CFS-CY">CFS-CY</option>
            <option value="CY-CFS">CY-CFS</option>
            <option value="DOOR-DOOR">DOOR-DOOR</option>

          </select>
          @error('shipment_no')
          <span class="text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>
        <div>
          <label for="servicesType" class="block text-sm font-medium text-gray-700">
            Service Type <span class="text-red-500">*</span>
          </label>
          <select name="servicesType" id="servicesType" wire:model="servicesType" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            @foreach($customers as $customer)
            @if(in_array('carrier', $customer->roles)) <!-- Only show customers with role "shipper" -->
            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endif
            @endforeach
          </select>
          @error('shipment_no')
          <span class="text-sm text-red-500">{{ $message }}</span>
          @enderror
        </div>
      </div>


      <div class="mb-4 grid grid-cols-4 gap-6">
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
        <div class="mb-4" wire:ignore>
          <label for="shipper">Shipper</label>
          <select wire:model="shipper_id" id="shipper" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
            <option value="">Select Shipper</option>
            @foreach($customers as $customer)
            @if(in_array('shipper', $customer->roles)) <!-- Only show customers with role "shipper" -->
            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endif
            @endforeach
          </select>
        </div>
        <div class="mb-4" wire:ignore>
          <label for="consignee">Consignee</label>
          <select wire:model="consignee_id" id="consignee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
            <option value="">Select Consignee</option>
            @foreach($customers as $customer)
            @if(in_array('consignee', $customer->roles)) <!-- Only show customers with role "consignee" -->
            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endif
            @endforeach
          </select>
        </div>
        <div class="mb-4" wire:ignore.self>
          <label for="notify">Notify</label>
          <select wire:model="notify_id" id="notify_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
            <option value="">Select Notify</option>
            @foreach($customers as $customer)
            @if(in_array('notify', $customer->roles)) <!-- Only show customers with role "notify" -->
            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
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
      <!-- Container -->
      <div class="bg-white rounded-md border border-gray-300 p-4">
        <h3 class="text-lg font-semibold mb-4">Containers</h3>
        @foreach($containers as $index => $container)
        <div class="mb-4 p-4 border rounded-md">
          <div class="grid grid-cols-5 gap-2">
            <!-- Kolom 1: Container ID -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Container ID:</label>
              <input type="text" name="container_id"
                wire:model="containers.{{ $index }}.container_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.container_id")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <!-- Kolom 2: Container Type -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Container Type:</label>
              <input type="text" name="container_type" wire:model="containers.{{ $index }}.container_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.container_type")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <!-- Kolom 3: Seal No -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Seal No:</label>
              <input type="text" name="container_seal" wire:model="containers.{{ $index }}.container_seal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.container_seal")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <!-- Baris kedua -->
            <!-- Kolom 1: Gross Weight -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Gross Weight:</label>
              <input type="text" name="gross_weight" wire:model="containers.{{ $index }}.gross_weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.gross_weight")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <!-- Kolom 2: Packtype -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Packtype:</label>
              <input type="text" name="pack_type" wire:model="containers.{{ $index }}.pack_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.pack_type")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <!-- Kolom 3: Measurement -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Measurement:</label>
              <input type="text" name="measurement" wire:model="containers.{{ $index }}.measurement" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.measurement")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <!-- Kolom 3: Total Pcs -->
            <div>
              <label class="block text-sm font-medium text-gray-700">No of Pcs:</label>
              <input type="text" name="measurement" wire:model="containers.{{ $index }}.pcs" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.pcs")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <!-- Kolom 4: Unit -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Unit:</label>
              <input type="text" name="unit" wire:model="containers.{{ $index }}.unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.unit")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <!-- Kolom 5: volume_weight -->
            <div>
              <label class="block text-sm font-medium text-gray-700">V. Weight:</label>
              <input type="text" name="unit" wire:model="containers.{{ $index }}.volume_weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.volume_weight")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
            <!-- Kolom 5: chargeable_weight -->
            <div>
              <label class="block text-sm font-medium text-gray-700">C. Weight:</label>
              <input type="text" name="unit" wire:model="containers.{{ $index }}.chargeable_weight" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.chargeable_weight")
              <span class="text-red-500 text-sm">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <!-- Tombol Hapus Container -->
          <button type="button" wire:click="removeContainer({{ $index }})" class="mt-2 text-red-500 hover:underline">
            Hapus Container
          </button>
        </div>
        @endforeach
        <!-- Tombol Tambah Container -->
        <button type="button" wire:click="addContainer()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
          + Tambah Container
        </button>
      </div>
      <!-- Description -->
      <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">
          Description
        </label>
        <textarea
          wire:model="description"
          id="description"
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
        @error('description')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
    </div>


    <!-- Buttons -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
      <a href="{{route ('shipments')}}" class="text-sm/6 font-semibold text-gray-900 bg-gray-200 px-4 py-2 rounded-md hover:bg-gray-300">
        Back
      </a>

      <button type="submit" class="rounded-md bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 bg-cyan-500 shadow-lg shadow-cyan-500/50">
        Save
      </button>
    </div>
</form>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    Livewire.on('scroll-to-error', function(fieldName) {
      let errorField = document.querySelector(`[data-error="${fieldName}"]`);
      if (errorField) {
        errorField.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
        errorField.focus(); // Fokus ke input biar user langsung bisa edit
      }
    });
  });
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    fetch('/data/ports.json')
      .then(response => response.json())
      .then(data => {
        document.querySelectorAll('.port-select').forEach(select => {
          // Kosongkan dulu biar tidak double append
          select.innerHTML = '<option value="" disabled selected>Select a port...</option>';

          data.forEach(port => {
            const option = document.createElement('option');
            option.value = `${port.name} - ${port.code}`;
            option.textContent = `${port.name} - ${port.code}`;
            select.appendChild(option);
          });

          // Inisialisasi Select2
          $(select).select2({
            placeholder: 'Select a port',
            allowClear: true,
            theme: 'tailwindcss-3'
          });

          // Pastikan data tetap tersimpan di Livewire
          $(select).on('change', function() {
            let selectedValue = $(this).val();
            let modelName = $(this).attr('wire:model'); // Ambil nama model Livewire

            // Kirim ke Livewire dengan dispatch event
            window.dispatchEvent(new CustomEvent('port-updated', {
              detail: {
                model: modelName,
                value: selectedValue
              }
            }));
          });
        });
      })
      .catch(error => console.error('Error loading ports:', error));
  });
  // Tangani toggle radio (select/input) untuk tiap container
  document.querySelectorAll('.port-container').forEach(container => {
    // Cari radio di dalam container tersebut
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
</script>
@script()
<script>
  $(document).ready(function() {
    $('#shipper').select2({
      placeholder: "Select roles",
      allowClear: true,
      theme: 'tailwindcss-3'
    });
    $('#shipper').on('change', function() {
      let data = $(this).val();
      // console.log(data);
      // $wire.set('roles',data,false);
      $wire.shipper_id = data;
    });
  });
</script>
@endscript
@script()
<script>
  $(document).ready(function() {
    $('#consignee').select2({
      placeholder: "Select roles",
      allowClear: true,
      theme: 'tailwindcss-3'
    });
    $('#consignee').on('change', function() {
      let data = $(this).val();
      // console.log(data);
      // $wire.set('roles',data,false);
      $wire.consignee_id = data;
    });
  });
</script>
@endscript
@script()
<script>
  $(document).ready(function() {
    $('#notify_id').select2({
      placeholder: "Select roles",
      allowClear: true,
      theme: 'tailwindcss-3'
    });
    $('#notify_id').on('change', function() {
      let data = $(this).val();
      // console.log(data);
      // $wire.set('roles',data,false);
      $wire.notify_id = data;
    });
  });
</script>
@endscript