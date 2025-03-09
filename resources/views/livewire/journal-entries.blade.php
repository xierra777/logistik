<div>
    <h1>Input Journal Entry</h1>

    @if (session()->has('message'))
    <div style="color:green;">{{ session('message') }}</div>
    @endif

    @if (session()->has('error'))
    <div style="color:red;">{{ session('error') }}</div>
    @endif

    <form wire:submit.prevent="save">
        <div>
            <label>Akun</label>
            <select wire:model="coa_id">
                <option value="">Pilih Akun</option>
                @foreach ($accounts as $account)
                <option value="{{ $account->id }}">
                    {{ $account->account_name }} ({{ $account->account_type }})
                </option>
                @endforeach
            </select>
            @error('coa_id') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Debit</label>
            <input type="number" step="0.01" wire:model="debit">
            @error('debit') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Kredit</label>
            <input type="number" step="0.01" wire:model="credit">
            @error('credit') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Deskripsi</label>
            <input type="text" wire:model="description">
            @error('description') <span style="color:red;">{{ $message }}</span> @enderror
        </div>

        <button type="submit">Simpan Journal</button>
    </form>

    <h2>Daftar Journal Entry</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Akun</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $entry)
            <tr>
                <td>{{ $entry->chartOfAccount->account_name }}</td>
                <td>{{ number_format($entry->debit, 2) }}</td>
                <td>{{ number_format($entry->credit, 2) }}</td>
                <td>{{ $entry->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>