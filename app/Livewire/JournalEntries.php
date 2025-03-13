<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\JournalEntry;
use App\Models\ChartOfAccount;

class JournalEntries extends Component
{
    public $coa_id, $debit, $credit, $description;

    protected $rules = [
        'coa_id'      => 'required',
        'description' => 'required',
        'debit'       => 'nullable|numeric|min:0',
        'credit'      => 'nullable|numeric|min:0',
    ];

    public function save()
    {
        $this->validate();

        // Validasi: Harus hanya satu nilai di antara debit dan credit (tidak boleh keduanya atau keduanya nol)
        if ((empty($this->debit) && empty($this->credit)) || ($this->debit > 0 && $this->credit > 0)) {
            session()->flash('error', 'Masukkan nilai Debit atau Kredit, tidak boleh keduanya atau keduanya nol.');
            return;
        }

        JournalEntry::create([
            'coa_id'      => $this->coa_id,
            'debit'       => $this->debit ?? 0,
            'credit'      => $this->credit ?? 0,
            'description' => $this->description,
        ]);

        $this->resetForm();
        session()->flash('message', 'Jurnal berhasil dicatat');
    }

    public function resetForm()
    {
        $this->coa_id = '';
        $this->debit = '';
        $this->credit = '';
        $this->description = '';
    }

    public function render()
    {
        return view('livewire.journal-entries', [
            'accounts' => ChartOfAccount::all(),
            'entries'  => JournalEntry::latest()->get(),
        ]);
    }
}
