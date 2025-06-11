<?php

namespace App\Livewire;

use App\Models\JournalEntry;
use Livewire\Component;
use App\Models\TShipments;

class JournalEntries extends Component
{
    public $shipments;
    public $journalEntries;

    public function mount()
    {
        $this->shipments = TShipments::all();
        $this->journalEntries = JournalEntry::all();
    }

    public function render()
    {
        return view('livewire.journal-entries');
    }
}
