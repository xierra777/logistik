<form wire:submit="save">
  <div class="space-y-12">
    <div class="border-b border-gray-900/10 pb-12">
      <h2 class="text-base/7 font-semibold text-gray-900">Tambahkan Data Kapal disini</h2>
      <br>
      <div class="mb-4">
        <label for="shipment_id" class="block text-sm font-medium text-gray-700">
          Shipment ID <span class="text-red-500">*</span>
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
              <label class="block text-sm font-medium text-gray-700">unit:</label>
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




      <!-- Shipper -->
      <div class="mt-4 mb-4" wire:ignore>
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
      <!-- Consignee Dropdown -->
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

      <!-- Notify Dropdown -->
      <div wire:ignore>
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
      <div class="flex flex-col" wire:ignore>
        <!-- Toggle between Input and Select -->
        <div>
          <label for="portSelect" class="block text-sm font-medium text-gray-700">Select or Input Port</label>
          <div class="flex items-center gap-4">
            <label for="selectOption" class="cursor-pointer">
              <input type="radio" id="selectOption" name="inputType" value="select" checked> Select from List
              <label for="inputOption" class="cursor-pointer">
                <input type="radio" id="inputOption" name="inputType" value="input"> Enter Port Manually
              </label>
            </label>
          </div>
        </div>

        <!-- Select Dropdown (hidden initially) -->
        <div id="selectContainer">
          <label for="portSelect" class="block text-sm font-medium text-gray-700">Port</label>
          <select wire:model="port_of_loading" id="portSelect" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
            <option value="" disabled selected>Select a port...</option>
          </select>
        </div>

        <!-- Input Field (visible initially) -->
        <div id="inputContainer" class="hidden">
          <label for="portInput" class="block text-sm font-medium text-gray-700">Port</label>
          <input wire:model="port_of_loading" type="text" id="portInput" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" placeholder="Enter port name">
        </div>
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
  document.addEventListener("DOMContentLoaded", function() {
    fetch('/data/ports.json') // Sesuaikan path JSON sesuai kebutuhan
      .then(response => response.json())
      .then(data => {
        const select = document.getElementById('portSelect');
        data.forEach(port => {
          const option = document.createElement('option');
          option.value = port.code; // Nilai option
          option.textContent = `${port.name} - ${port.code}`; // Tampilan option
          select.appendChild(option);
        });
        // Setelah option ditambahkan, inisialisasi select2
        $('#portSelect').select2({
          placeholder: 'Select a port',
          allowClear: true,
          theme: 'tailwindcss-3' // Ubah tema jika diperlukan, misal 'tailwindcss'
        });
      })
      .catch(error => console.error('Error loading ports:', error));
  });
  document.querySelectorAll('input[name="inputType"]').forEach((radio) => {
    radio.addEventListener('change', function() {
      const selectContainer = document.getElementById('selectContainer');
      const inputContainer = document.getElementById('inputContainer');

      if (this.value === 'select') {
        selectContainer.classList.remove('hidden');
        inputContainer.classList.add('hidden');
      } else {
        selectContainer.classList.add('hidden');
        inputContainer.classList.remove('hidden');
      }
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