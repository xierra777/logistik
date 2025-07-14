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
    public $sortJournalEntries;
    public function mount()
    {
        $this->shipments = TShipments::all();
        $this->updatedSortJournalEntries();        // Initialize totalDebit and totalCredit to 0
        $this->totalDebit = 0;
        $this->totalCredit = 0;
        foreach ($this->journalEntries as $entry) {
            $this->totalDebit += $entry->debit;
            $this->totalCredit += $entry->credit;
        }
        // dd($this->sortJournalEntries);
    }
    public function updatedSortJournalEntries()
    {
        $this->journalEntries = JournalEntry::all();

        if ($this->sortJournalEntries === 'all' || $this->sortJournalEntries === '') {
            $this->journalEntries = JournalEntry::all();
        } elseif ($this->sortJournalEntries === 'true') {
            $this->journalEntries = JournalEntry::where('is_reversal', true)->get();
        } elseif ($this->sortJournalEntries === 'false') {
            $this->journalEntries = JournalEntry::where('is_reversal', false)->get();
        }
    }
    public function render()
    {
        // dd($this->totalCredit, $this->totalDebit);
        return view('livewire.journal-entries');
    }
}
