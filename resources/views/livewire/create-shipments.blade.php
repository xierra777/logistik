<form wire:submit="save">
  <div class="space-y-12">
    <div class="border-b border-gray-900/10 pb-12">
      <h2 class="text-base/7 font-semibold text-gray-900">Tambahkan Data Kapal disini</h2>
      <br>
      <div class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-6">
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
      </div>

      <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="mb-4">
          <label for="ocean_vessel_mother">Mother Vessel</label>
          <input type="text" name="ocean_vessel_mother" wire:model="ocean_vessel_mother" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
          @error('ocean_vessel_mother')
          <span class="text-red-500 text-sm">{{ $message }}</span>
          @enderror
        </div>
        <div class="mb-4">
          <label for="">ETA / Estimate Time Arrival</label>
          <input type="date" name="estimearrival" id="estimearrival" wire:model="estimearrival" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
          @error('estimearrival') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
          <label for="">ETD / Estimate Time Departure</label>
          <input type="date" name="estimedelivery" id="estimedelivery" wire:model="estimedelivery" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
          @error('estimedeparture') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
      </div>

      <!-- Organization -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="mb-4" wire:ignore>
          <label for="shipper">Shipper</label>
          <select wire:model="shipper" id="shipper" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
            <option value="">Select Shipper</option>
            @foreach($customers as $customer)
            @if(in_array('shipper', $customer->roles)) <!-- Only show customers with role "shipper" -->
            <option value="{{ $customer->name }}">{{ $customer->name }}</option>
            @endif
            @endforeach
          </select>
        </div>
        <div class="mb-4" wire:ignore>
          <label for="consignee">Consignee</label>
          <select wire:model="consignee" id="consignee" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
            <option value="">Select Consignee</option>
            @foreach($customers as $customer)
            @if(in_array('consignee', $customer->roles)) <!-- Only show customers with role "consignee" -->
            <option value="{{ $customer->name }}">{{ $customer->name }}</option>
            @endif
            @endforeach
          </select>
        </div>
        <div class="mb-4" wire:ignore>
          <label for="notify">Notify</label>
          <select wire:model="notify" id="notify" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
            <option value="">Select Notify</option>
            @foreach($customers as $customer)
            @if(in_array('notify', $customer->roles)) <!-- Only show customers with role "notify" -->
            <option value="{{ $customer->name }}">{{ $customer->name }}</option>
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
          <div class="grid grid-cols-4 gap-4">
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
            <!-- Kolom 3: Measurement -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Unit:</label>
              <input type="text" name="unit" wire:model="containers.{{ $index }}.unit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
              @error("containers.$index.unit")
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
      <a wire:navigate href="{{route ('shipments')}}" class="text-sm/6 font-semibold text-gray-900 bg-gray-200 px-4 py-2 rounded-md hover:bg-gray-300">
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
      $wire.shipper = data;
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
      $wire.consignee = data;
    });
  });
</script>
@endscript
@script()
<script>
  $(document).ready(function() {
    $('#notify').select2({
      placeholder: "Select roles",
      allowClear: true,
      theme: 'tailwindcss-3'
    });
    $('#notify').on('change', function() {
      let data = $(this).val();
      // console.log(data);
      // $wire.set('roles',data,false);
      $wire.notify = data;
    });
  });
</script>
@endscript