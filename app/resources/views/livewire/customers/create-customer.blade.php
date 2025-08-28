<form wire:submit.prevent="save" class="p-6 text-dark-900 dark:text-gray-100">
  <div class="space-y-12">
    <div class="border-b border-gray-900/10 pb-12">
      <h2 class="text-base/7 font-semibold text-gray-900">Tambahkan Data Organisasi</h2>
      <br>
      <div class="relative w-full mb-4 bg">
        <label for="name" class="block text-sm font-medium text-gray-700">
          Nama Perusahaan <span class="text-red-500">*</span>
        </label>
        <input wire:model="name" type="text" id="name" name="name"
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('name')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
      <div class="relative w-full mb-4 bg">
        <label for="email" class="block text-sm font-medium text-gray-700">
          Email <span class="text-red-500">*</span>
        </label>
        <input wire:model="email" type="text" id="email" name="email" required
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('email')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
      <div class="relative w-full mb-4 bg">
        <label for="contact" class="block text-sm font-medium text-gray-700">
          Kontak <span class="text-red-500">*</span>
        </label>
        <input wire:model="contact" type="text" id="contact" name="contact"
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('contact')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
      <!-- Country Selector -->
      <div class="relative w-full mb-4 bg">
        <label for="countrySelect" class="block text-sm font-medium text-gray-700">
          Country <span class="text-red-500">*</span></label>
        <div wire:ignore>
          <select id="countrySelect"
            class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></select>
        </div>

        @error('country_code') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
      </div>

      <div class="mb-4">
        <label for="contact" class="block text-sm font-medium text-gray-700"></label>
        Select COA Account <span class="text-red-500">*</span>
        <div wire:ignore>
          <select wire:model="coa_id" id="coa_id" class="w-full mt-2 border rounded p-2">
            <option value="">-- Pilih COA --</option>
            @foreach($chartOfAccounts as $coa)
            <option value="{{ $coa->id }}">
              {{ $coa->account_code }} - {{ $coa->account_name }} ({{ $coa->term_type }})
            </option>
            @endforeach
          </select>
        </div>

        @error('coa_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
      </div>
      <!-- roles -->
      <div class="mb-4">
        <label for="roles" class="block text-sm font-medium text-gray-700">Pilih Role</label>
        <div wire:ignore class="relative w-full">
          <!-- @dump($roles) -->
          <select wire:model.live="roles" id="mySelect2" class="w-full" multiple>
            <option value="shipper">Shipper</option>
            <option value="consignee">Consignee</option>
            <option value="client">Client</option>
            <option value="agent">Agent</option>
            <option value="notify">Notify</option>
            <option value="carrier">Carrier</option>
            <option value="airline">Airline</option>
            <option value="delivery_agent">Delivery Agent</option>
            <option value="origin_agent">Origin Agent</option>
            <option value="carrier_agent">Carrier Agent</option>
          </select>
        </div>
        @error('roles')
        <span class="text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <!-- endroles -->
      <div class="relative w-full mb-4 bg">
        <label for="web" class="block text-sm font-medium text-gray-700">
          Web
        </label>
        <input wire:model="web" type="text" id="web" name="web"
          class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" />
        @error('web')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>

      <div class="relative w-full mb-4 bg">
        <label for="address" class="block text-sm font-medium text-gray-700">
          Alamat <span class="text-red-500">*</span>
        </label>
        <textarea
          class="py-2 px-3 mt-2 sm:py-3 sm:px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
          rows="3" placeholder="This is a textarea placeholder" wire:model="address" type="text" id="address"
          name="address" required></textarea>
        @error('address')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>
  <input type="text" wire:model="customer_code" name="" id="">
  <!-- Buttons -->
  <div class="mt-6 flex items-center justify-end gap-x-6">
    <a wire:navigate href="{{route ('listCust')}}"
      class="text-sm/6 font-semibold text-gray-900 bg-gray-200 px-4 py-3 rounded-md hover:bg-gray-300">
      Back
    </a>

    <button type="submit"
      class="rounded-md bg-indigo-600 px-4 py-3 itext-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 bg-cyan-500 shadow-lg shadow-cyan-500/50">
      Save
    </button>
  </div>
</form>
@script()
<script>
  $(document).ready(function() {
    $('#coa_id').select2({
      placeholder: "Select roles",
      allowClear: true,
      theme: 'tailwindcss-3'
    });

    $('#coa_id').on('change', function() {
      let data = $(this).val();
      // console.log(data);
      // $wire.set('roles',data,false);
      $wire.coa_id = data;
    });
  });
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

      // Extract country code from state.id
      let parts = state.id.split(" - ");
      let code = parts[0].toLowerCase();
      let name = parts.slice(1).join(" - "); // Handle names with dashes

      return $('<span><img src="https://flagcdn.com/w40/' + code + '.png" class="w-5 h-3 mr-2 inline object-cover" onerror="this.style.display=\'none\'">' + name + '</span>');
    }

    function formatCountrySelection(state) {
      if (!state.id) return state.text;

      let parts = state.id.split(" - ");
      let code = parts[0];
      let name = parts.slice(1).join(" - ");

      return $('<span><img src="https://flagcdn.com/w20/' + code.toLowerCase() + '.png" class="w-4 h-3 mr-2 inline object-cover" onerror="this.style.display=\'none\'">' + code + ' - ' + name + '</span>');
    }

    function showLoadingState() {
      const $select = $("#countrySelect");
      $select.empty().append('<option value="">Loading countries...</option>');
      $select.prop('disabled', true);
    }

    function showErrorState() {
      const $select = $("#countrySelect");
      $select.empty().append('<option value="">Error loading countries. Please refresh.</option>');
      $select.prop('disabled', false);
    }

    function loadCountries() {
      showLoadingState();

      // Array of API endpoints to try
      const apiEndpoints = [{
          url: "https://restcountries.com/v3.1/all?fields=name,cca2,cca3",
          parser: (data) => data.map(c => ({
            id: c.cca2 + " - " + c.name.common,
            text: c.cca2 + " - " + c.name.common
          }))
        },
        {
          url: "https://countries-api-836d.onrender.com/countries",
          parser: (data) => data.map(c => ({
            id: c.code + " - " + c.name,
            text: c.code + " - " + c.name
          }))
        },
        {
          url: "https://restcountries.com/v3.1/all",
          parser: (data) => data.map(c => ({
            id: c.cca2 + " - " + c.name.common,
            text: c.cca2 + " - " + c.name.common
          }))
        }
      ];

      function tryAPI(index = 0) {
        if (index >= apiEndpoints.length) {
          showErrorState();
          return;
        }

        const endpoint = apiEndpoints[index];

        $.ajax({
          url: endpoint.url,
          method: "GET",
          timeout: 10000, // 10 second timeout
          success: function(data) {
            try {
              let countryData = endpoint.parser(data);

              // Sort countries alphabetically
              countryData.sort((a, b) => a.text.localeCompare(b.text));

              const $select = $("#countrySelect");
              $select.empty().prop('disabled', false);

              // Add placeholder option
              $select.append('<option value="">Select Country</option>');

              // Initialize Select2
              $select.select2({
                data: countryData,
                placeholder: "Pilih Negara",
                allowClear: true,
                theme: 'tailwindcss-3',
                templateResult: formatCountry,
                templateSelection: formatCountrySelection,
                width: "100%",
                dropdownAutoWidth: true,
                escapeMarkup: function(markup) {
                  return markup; // Allow HTML
                }
              }).on("change", function() {
                let selectedValue = $(this).val();

                if (selectedValue && typeof $wire !== 'undefined') {
                  let parts = selectedValue.split(" - ");
                  $wire.country = selectedValue;
                  $wire.country_code = parts[0];

                  // Call Livewire method if exists
                  if (typeof $wire.generateCustomerCode === 'function') {
                    $wire.generateCustomerCode();
                  }
                }
              });


            } catch (parseError) {
              console.error("Error parsing country data:", parseError);
              tryAPI(index + 1);
            }
          },
          error: function(xhr, status, error) {
            console.warn(`API ${index + 1} failed:`, error);
            tryAPI(index + 1);
          }
        });
      }

      // Start trying APIs
      tryAPI();
    }

    // Load countries on page ready
    loadCountries();

    // Optional: Retry button
    $(document).on('click', '#retryCountries', function() {
      loadCountries();
    });

    // Optional: Add retry button to DOM if it doesn't exist
    if ($('#retryCountries').length === 0) {
      $('#countrySelect').after('<button id="retryCountries" class="ml-2 px-3 py-1 text-xs bg-blue-500 text-white rounded hover:bg-blue-600" style="display:none;">Retry</button>');
    }
  });

  document.addEventListener("livewire:load", function() {
    Livewire.hook("message.processed", () => {
      $("#countrySelect").select2("destroy");
      loadCountries();
    });
  });
</script>
@endscript