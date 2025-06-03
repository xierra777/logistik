<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TShipments;

class JournalEntries extends Component
{
    public $shipments;    // Properti untuk daftar shipment

    public function mount()
    {
        $this->shipments = TShipments::all();
    }

    public function render()
    {
        return view('livewire.journal-entries');
    }
}
