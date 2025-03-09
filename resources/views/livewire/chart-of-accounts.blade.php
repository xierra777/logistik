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
            <input type="text" wire:model="account_code" class="w-full border rounded p-2" placeholder="Masukkan kode akun">
            @error('account_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Account Name</label>
            <input type="text" wire:model="account_name" class="w-full border rounded p-2" placeholder="Masukkan nama akun">
            @error('account_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Account Type</label>
            <select wire:model="account_type" class="w-full border rounded p-2">
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
        <div class="mb-4">
            <label class="block font-medium">Term Type</label>
            <select wire:model="term_type" class="w-full border rounded p-2">
                <option value="">-- Pilih Term Type --</option>
                <option value="CR">Credit (CR)</option>
                <option value="DR">Debit (DR)</option>
            </select>
            @error('term_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-4">
            <label class="block font-medium">Parent Account (Opsional)</label>
            <select wire:model="parent_account_id" class="w-full border rounded p-2">
                <option value="">-- Tidak Ada --</option>
                @foreach ($parents as $parent)
                <option value="{{ $parent->id }}">{{ $parent->account_code }} - {{ $parent->account_name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            {{ $isEditing ? 'Update' : 'Simpan' }}
        </button>
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
                    <td class="border px-4 py-2">{{ $account->account_code }}</td>
                    <td class="border px-4 py-2">{{ $account->account_name }}</td>
                    <td class="border px-4 py-2">{{ $account->account_type }}</td>
                    <td class="border px-4 py-2">{{ $account->term_type }}</td>
                    <td class="border px-4 py-2">
                        {{ $account->parent ? $account->parent->account_code . ' - ' . $account->parent->account_name : '-' }}
                    </td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit({{ $account->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                        <button wire:click="delete({{ $account->id }})" class="bg-red-600 text-white px-2 py-1 rounded" onclick="confirm('Yakin hapus?') || event.stopImmediatePropagation()">Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>