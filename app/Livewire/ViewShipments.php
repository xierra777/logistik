<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\shipments;
use App\Models\transaction;

class ViewShipments extends Component
{
    public $shipment = [];
    public $transaction = [];

    public function mount($id)
    {
        $this->shipment = shipments::find($id);
        $this->transaction = transaction::find($id);
    }
    public function render()
    {
        return view('livewire.view-shipments');
    }
}
