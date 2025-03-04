<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\shipments;
use App\Models\transaction;

class ViewShipments extends Component
{
    public $shipment;
    public $transaction;
    public function mount($id)
    {
        // Pastikan nama modelnya konsisten (Shipment, bukan shipments)
        $this->shipment = Shipments::with('transactions')->findOrFail($id);
    }
    public function render()
    {
        return view('livewire.view-shipments');
    }
}
