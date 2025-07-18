<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Chart of Accounts (COA)</h1>

    @if (session()->has('message'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('message') }}
    </div>
    @endif

    <!-- Form untuk Create/Update COA -->
    <form wire:submit.prevent="{{ $isEditing ? 'update' : 'save' }}" class="mb-6">
        <div class="mb-4">
            <label class="block font-medium">Account Code</label>
            <input type="text" wire:model="account_code" class="w-full border rounded-md p-2 border-gray-300" placeholder="Masukkan kode akun">
            @error('account_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Account Name</label>
            <input type="text" wire:model="account_name" class="w-full border rounded-md p-2 border-gray-300" placeholder="Masukkan nama akun">
            @error('account_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4" wire:ignore>
            <label class="block font-medium">Account Type</label>
            <select wire:model="account_type" id="account_type" class="w-full border rounded p-2">
                <option value="">-- Pilih Tipe Akun --</option>
                <option value="Asset">Asset</option>
                <option value="Liability">Liability</option>
                <option value="Equity">Equity</option>
                <option value="Revenue">Revenue</option>
                <option value="Expense">Expense</option>
            </select>
            @error('account_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <!-- Tambahan Dropdown untuk Term Type (DR/CR) -->
        <div class="mb-4" wire:ignore>
            <label class="block font-medium">Term Type</label>
            <select wire:model="term_type" id="term_type" class="w-full border rounded p-2">
                <option value="">-- Pilih Term Type --</option>
                <option value="CR">Credit (CR)</option>
                <option value="DR">Debit (DR)</option>
            </select>
            @error('term_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4" wire:ignore>
            <label class="block font-medium">Parent Account (Opsional)</label>
            <select wire:model="parent_account_id" id="parent_account_id" class="w-full border rounded p-2">
                <option value="">-- Tidak Ada --</option>
                @foreach ($parents as $parent)
                <option value="{{ $parent->id }}">{{ $parent->account_code }} - {{ $parent->account_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="is_payment">Is Payment <span class="text-blue-600 cursor-help" title="Ini adalah informasi tambahan."><i class="bi bi-info-circle"></i></span>
            </label>
            <div class="flex items-center p-4">
                <label for="hs-small-switch-with-icons" class="relative inline-block w-11 h-6 cursor-pointer">
                    <input type="checkbox" id="hs-small-switch-with-icons" class="peer sr-only" wire:model="is_payment">
                    <span class="absolute inset-0 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-blue-600 dark:bg-neutral-700 dark:peer-checked:bg-blue-500 peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                    <span class="absolute top-1/2 start-0.5 -translate-y-1/2 size-5 bg-white rounded-full shadow-xs transition-transform duration-200 ease-in-out peer-checked:translate-x-full dark:bg-neutral-400 dark:peer-checked:bg-white"></span>
                    <!-- Left Icon (Off) -->
                    <span class="absolute top-1/2 start-0.5 -translate-y-1/2 flex justify-center items-center size-5 text-gray-500 peer-checked:text-white transition-colors duration-200 dark:text-neutral-500">
                        <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </span>
                    <!-- Right Icon (On) -->
                    <span class="absolute top-1/2 end-0.5 -translate-y-1/2 flex justify-center items-center size-5 text-gray-500 peer-checked:text-blue-600 transition-colors duration-200 dark:text-neutral-500">
                        <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </span>
                </label>
            </div>
        </div>
        <div class="p-3 flex justify-between">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                {{ $isEditing ? 'Update' : 'Simpan' }}
            </button>
            <div>
                <a class="coaSetting bg-blue-500 rounded-md p-3 hover:scale-105 hover:text-white transition duration-300 ease-in-out hover:shadow-cyan-200" href="{{route('coaSetting')}}">Charge Coa Setting</a>
                <a class="coaSetting bg-blue-500 rounded-md p-3 hover:scale-105 hover:text-white transition duration-300 ease-in-out hover:shadow-cyan-200" href="{{route('accountant.list')}}">Back</a>
            </div>

        </div>

    </form>

    <!-- Tabel untuk Menampilkan Daftar Akun COA -->

    <div class="overflow-x-auto">
        <table class="min-w-full border">
            <thead class="bg-gray-200">
                <tr>
                    <th class="border px-4 py-2">Account Code</th>
                    <th class="border px-4 py-2">Account Name</th>
                    <th class="border px-4 py-2">Account Type</th>
                    <th class="border px-4 py-2">Term Type</th>
                    <th class="border px-4 py-2">Parent</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                <tr>
                    <td class="border whitespace px-4 py-2">{{ $account->account_code }}</td>
                    <td class="border px-4 py-2">{{ $account->account_name }}</td>
                    <td class="border px-4 py-2">{{ $account->account_type }}</td>
                    <td class="border px-4 py-2">{{ $account->term_type }}</td>
                    <td class="border px-4 py-2">
                        {{ $account->parent ? $account->parent->account_code . ' - ' . $account->parent->account_name : '-' }}
                    </td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit({{ $account->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                        <button @click="$dispatch('confirm')" class="bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    @if($accounts->hasPages())
    <div class="mt-4 px-1.5 flex justify-end">
        {{ $accounts->links() }}
    </div>
    @endif

    <!-- Selector Rows Per Page -->
    <div class="mt-4">
        <select wire:model.live="perPage" class="py-1 px-2 bg-gray-100 border rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-700 dark:border-transparent dark:text-neutral-400 dark:focus:ring-neutral-600">
            <option value="5">5 Rows</option>
            <option value="10">10 Rows</option>
            <option value="25">25 Rows</option>
            <option value="50">50 Rows</option>
            <option value="100">100 Rows</option>
        </select>
    </div>
    <div
        x-data="{open : false}"
        x-show="open"
        @confirm.window="
     
        const get_id= event.detail.get_id
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d8',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, Keep it',
            }).then((result) => {
            if (result.isConfirmed) {
                $wire.confirmDelete(get_id).then(result =>{
                 Swal.fire(
                    'Deleted!',
                    'Account has been deleted.',
                    'success')
                 });
            } else if(result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire(
                    'canceled',
                    'Delete has been cancelled',
                    'error'
                )
            }
        });
    ">
    </div>
</div>

@script()
<script>
    $(document).ready(function() {
        $('#parent_account_id').select2({
            placeholder: "Select roles",
            allowClear: true,
            theme: 'tailwindcss-3'
        });
        $('#parent_account_id').on('change', function() {
            let data = $(this).val();
            // console.log(data);
            // $wire.set('roles',data,false);
            $wire.parent_account_id = data;
        });
    });
    $(document).ready(function() {
        $('#account_type').select2({
            placeholder: "Select roles",
            allowClear: true,
            theme: 'tailwindcss-3'
        });
        $('#account_type').on('change', function() {
            let data = $(this).val();
            // console.log(data);
            // $wire.set('roles',data,false);
            $wire.account_type = data;
        });
    });
    $(document).ready(function() {
        $('#term_type').select2({
            placeholder: "Select roles",
            allowClear: true,
            theme: 'tailwindcss-3'
        });
        $('#term_type').on('change', function() {
            let data = $(this).val();
            // console.log(data);
            // $wire.set('roles',data,false);
            $wire.term_type = data;
        });
    });
</script>
@endscript