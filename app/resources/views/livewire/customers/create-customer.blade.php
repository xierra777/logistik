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
          type="text"
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

      <div class="mb-4" wire:ignore>
        <label class="block font-medium">Pilih Akun COA</label>
        <select wire:model="coa_id" id="coa_id" class="w-full border rounded p-2">
          <option value="">-- Pilih COA --</option>
          @foreach($chartOfAccounts as $coa)
          <option value="{{ $coa->id }}">
            {{ $coa->account_code }} - {{ $coa->account_name }} ({{ $coa->term_type }})
          </option>
          @endforeach
        </select>
        @error('coa_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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
          <option value="airline">Airline</option>
          <option value="delivery_agent">Delivery Agent</option>
          <option value="origin_agent">Origin Agent</option>
          <option value="carrier_agent">Carrier Agent</option>
        </select>
        @error('roles')
        <span class="text-red-500">{{ $message }}</span>
        @enderror
      </div>
      <!-- endroles -->
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

      <div class="relative w-full mb-4 bg">
        <label for="address" class="block text-sm font-medium text-gray-700">
          Alamat <span class="text-red-500">*</span>
        </label>
        <textarea class="py-2 px-3 sm:py-3 sm:px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600" rows="3" placeholder="This is a textarea placeholder" wire:model="address"
          type="text"
          id="address"
          name="address"
          required></textarea>
        @error('address')
        <span class="text-sm text-red-500">{{ $message }}</span>
        @enderror
      </div>
    </div>
  </div>

  <!-- Buttons -->
  <div class="mt-6 flex items-center justify-end gap-x-6">
    <a wire:navigate href="{{route ('customers.list')}}" class="text-sm/6 font-semibold text-gray-900 bg-gray-200 px-4 py-3 rounded-md hover:bg-gray-300">
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
            let selectedValue = $(this).val();
            $wire.country = selectedValue;
            $wire.country_code = selectedValue.split(" - ")[0];
            $wire.generateCustomerCode();


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