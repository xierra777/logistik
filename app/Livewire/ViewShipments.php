<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\shipments;

class ViewShipments extends Component
{
    public $shipment = [];
    
    public function mount($id){
        $this->shipment = shipments::find($id);
    }
    public function render()
    {
        return view('livewire.view-shipments');
    }
}
