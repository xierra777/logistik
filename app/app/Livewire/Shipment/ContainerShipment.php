<?php

namespace App\Livewire\Shipment;

use Livewire\Component;
use App\Models\TShipments;
use App\Models\shipmentContainers;

class ContainerShipment extends Component
{
    public $container;
    public $shipment;

    public function mount($id, $container_id)
    {
        $this->shipment = TShipments::with('container.jobContainer')->findOrFail($id);
        $this->container = shipmentContainers::where('id', $container_id)
            ->where('id_shipments', $id)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.shipment.container-shipment');
    }
}
