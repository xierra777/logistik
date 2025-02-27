<form wire:submit="updateShipments" class="p-6 text-dark-900 dark:text-gray-100">
  <div class="space-y-12">
    <div class="border-b border-gray-900/10 pb-12">
      <h2 class="text-base/7 font-semibold text-gray-900">Edit Data Kapal disini</h2>
      <br>
      <div>
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

      <!-- Container ID -->
      <div>
        <label for="container_id" class="block text-sm font-medium text-gray-700">
          Container ID <span class="text-red-500">*</span>
        </label>
        <input
          wire:model="container_id"
          type="text"
          id="container_id"
          name="container_id"
          required
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('container_id')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <!-- Container Type -->
      <div>
        <label for="container_type" class="block text-sm font-medium text-gray-700">
          Container Type <span class="text-red-500">*</span>
        </label>
        <input
          wire:model="container_type"
          type="text"
          id="container_type"
          name="container_type"
          required
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('container_type')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <!-- Shipper -->
      <div>
        <label for="shipper" class="block text-sm font-medium text-gray-700">
          Shipper
        </label>
        <input
          wire:model="shipper"
          type="text"
          id="shipper"
          name="shipper"
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('shipper')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <!-- Consignee -->
      <div>
        <label for="consignee" class="block text-sm font-medium text-gray-700">
          Consignee
        </label>
        <input
          wire:model="consignee"
          type="text"
          id="consignee"
          name="consignee"
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('consignee')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <!-- Notify -->
      <div>
        <label for="notify" class="block text-sm font-medium text-gray-700">
          Notify
        </label>
        <input
          wire:model="notify"
          type="text"
          id="notify"
          name="notify"
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('notify')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <!-- Packages -->
      <div>
        <label for="packages" class="block text-sm font-medium text-gray-700">
          Packages
        </label>
        <input
          wire:model="packages"
          type="text"
          id="packages"
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('packages')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <!-- Description -->
      <div>
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

      <!-- Gross Weight -->
      <div>
        <label for="gross_weight" class="block text-sm font-medium text-gray-700">
          Gross Weight <span class="text-red-500">*</span>
        </label>
        <input
          wire:model="gross_weight"
          type="text"
          id="gross_weight"
          required
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('gross_weight')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <!-- Measurement -->
      <div>
        <label for="measurement" class="block text-sm font-medium text-gray-700">
          Measurement <span class="text-red-500">*</span>
        </label>
        <input
          wire:model="measurement"
          type="text"
          id="measurement"
          required
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('measurement')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
    </div>

    <!-- Buttons -->
    <div class="mt-6 flex items-center justify-end gap-x-6">
      <a wire:navigate href="/shipments" class="text-sm/6 font-semibold text-gray-900 bg-gray-200 px-4 py-2 rounded-md hover:bg-gray-300">
        Back
      </a>

      <button type="submit" class="rounded-md bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 bg-cyan-500 shadow-lg shadow-cyan-500/50">
        Update
      </button>
    </div>
</form>

<script>
  document.addEventListener('livewire:initialized', () => {
    Livewire.on('swal', (event) => {
      const data = event
      swal.fire({
        icon: data[0]['icon'],
        title: data[0]['title'],
        text: data[0]['text'],
      });
    })
  })
</script>