<?php

namespace App\Livewire;

use App\Models\JournalEntry;
use Livewire\Component;
use App\Models\TShipments;

class JournalEntries extends Component
{
    public $shipments;
    public $journalEntries;

    public $totalDebit;
    public $totalCredit;
    public function mount()
    {
        $this->shipments = TShipments::all();
        $this->journalEntries = JournalEntry::all();
        // Initialize totalDebit and totalCredit to 0
        $this->totalDebit = 0;
        $this->totalCredit = 0;
        foreach ($this->journalEntries as $entry) {
            $this->totalDebit += $entry->debit;
            $this->totalCredit += $entry->credit;
        }
    }

    public function render()
    {
        return view('livewire.journal-entries');
    }
}
