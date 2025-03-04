<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Shipment;

class ViewShipments extends Component
{
    public $shipment;
    public $transaction;
    public function mount($id)
    {
        // Pastikan nama modelnya konsisten (Shipment, bukan shipments)
        $this->shipment = Shipment::with('transactions')->findOrFail($id);
    }
    public function render()
    {
        return view('livewire.view-shipments');
    }
}
