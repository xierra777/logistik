<form wire:submit.prevent="save" class="p-6 text-dark-900 dark:text-gray-100">
  <div class="space-y-12">
    <div class="border-b border-gray-900/10 pb-12">
      <h2 class="text-base/7 font-semibold text-gray-900">Tambahkan Data Organisasi</h2>
      <br>
      <div class="relative w-full mb-4 bg">
        <label for="name" class="block text-sm font-medium text-gray-700">
          Nama Perusahaan <span class="text-red-500">*</span>
        </label>
        <input
          wire:model="name"
          type="text"
          id="name"
          name="name"
          required
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('name')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
      <div class="relative w-full mb-4 bg">
        <label for="email" class="block text-sm font-medium text-gray-700">
          Email <span class="text-red-500">*</span>
        </label>
        <input
          wire:model="email"
          type="text"
          id="email"
          name="email"
          required
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('email')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
      <div class="relative w-full mb-4 bg">
        <label for="contact" class="block text-sm font-medium text-gray-700">
          Kontak <span class="text-red-500">*</span>
        </label>
        <input
          wire:model="contact"
          type="number"
          id="contact"
          name="contact"
          required
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('contact')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
      <!-- Country Selector -->
      <div class="relative w-full mb-4 bg">
        <label class="block text-sm font-medium text-gray-700">Negara <span class="text-red-500">*</span></label>
        <div wire:ignore>
          <select id="countrySelect" class="w-full"></select>
        </div>
        <!-- Input hidden untuk menyimpan nilai ke Livewire -->
        <input type="hidden" wire:model="country" id="hiddenCountry">
        @error('country') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
      </div>
      <!-- roles -->
      <div wire:ignore class="relative w-full mb-4 bg">
        <!-- @dump($roles) -->
        <label for="roles" class="block text-sm font-medium text-gray-700">Pilih Role</label>
        <select wire:model.live="roles" id="mySelect2" class="w-full" multiple>
          <option value="shipper">Shipper</option>
          <option value="consignee">Consignee</option>
          <option value="client">Client</option>
          <option value="agent">Agent</option>
          <option value="notify">Notify</option>
          <option value="carrier">Carrier</option>
        </select>
        @error('roles')
        <span class="text-red-500">{{ $message }}</span>
        @enderror
      </div>
      <!-- endroles -->
      <div class="relative w-full mb-4 bg">
        <label for="address" class="block text-sm font-medium text-gray-700">
          Alamat <span class="text-red-500">*</span>
        </label>
        <input
          wire:model="address"
          type="text"
          id="address"
          name="address"
          required
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('address')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
      <div class="relative w-full mb-4 bg">
        <label for="web" class="block text-sm font-medium text-gray-700">
          Web <span class="text-red-500">*</span>
        </label>
        <input
          wire:model="web"
          type="text"
          id="web"
          name="web"
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('web')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>

  <!-- Buttons -->
  <div class="mt-6 flex items-center justify-end gap-x-6">
    <a wire:navigate href="{{route ('shipments')}}" class="text-sm/6 font-semibold text-gray-900 bg-gray-200 px-4 py-3 rounded-md hover:bg-gray-300">
      Back
    </a>

    <button type="submit" class="rounded-md bg-indigo-600 px-4 py-3 itext-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 bg-cyan-500 shadow-lg shadow-cyan-500/50">
      Save
    </button>
  </div>
</form>
@script()
<script>
  $(document).ready(function() {
    $('#mySelect2').select2({
      placeholder: "Select roles",
      allowClear: true,
      theme: 'tailwindcss-3'
    });

    $('#mySelect2').on('change', function() {
      let data = $(this).val();
      // console.log(data);
      // $wire.set('roles',data,false);
      $wire.roles = data;
    });
  });
</script>
@endscript
@script()
<script>
  $(document).ready(function() {
    function formatCountry(state) {
      if (!state.id) return state.text;
      // Extract only the country code from state.id (assumed format: "US - United States")
      let parts = state.id.split(" - ");
      let code = parts[0].toLowerCase();
      return $('<span><img src="https://flagcdn.com/w40/' + code + '.png" class="w-5 mr-2 inline">' + state.text + '</span>');
    }

    function loadCountries() {
      $.ajax({
        url: "https://restcountries.com/v3.1/all",
        method: "GET",
        success: function(data) {
          let countryData = data.map(c => ({
            id: c.cca2 + " - " + c.name.common,
            text: c.cca2 + " - " + c.name.common
          }));
          const $select = $("#countrySelect");
          $select.empty();
          // Add an empty option for placeholder
          $select.append('<option value=""></option>');

          $("#countrySelect").select2({
            data: countryData,
            placeholder: "Pilih Negara",
            allowClear: true,
            theme: 'tailwindcss-3',
            templateResult: formatCountry,
            templateSelection: formatCountry,
            width: "100%"
          }).on("change", function() {
            let selectedValue = $(this).val(); // e.g., "US - United States"
            // $wire.set("country", selectedValue, false);
            $wire.country = selectedValue;
            // Update Livewire property (assuming you use a hidden input bound to Livewire)
            // $("#hiddenCountry").val(selectedValue).trigger("input");
          });
        },
        error: function(xhr, status, error) {
          console.error("Error fetching countries:", error);
        }
      });
    }

    loadCountries();
  });

  document.addEventListener("livewire:load", function() {
    Livewire.hook("message.processed", () => {
      $("#countrySelect").select2("destroy");
      loadCountries();
    });
  });
</script>
@endscript