<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;

class JournalEntries extends Component
{
    public $shipments;    // Properti untuk daftar shipment

    public function mount()
    {
        $this->shipments = Shipment::all();
    }

    public function render()
    {
        return view('livewire.journal-entries');
    }
}
