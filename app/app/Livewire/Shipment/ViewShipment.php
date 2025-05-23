<?php

namespace App\Livewire\Shipment;

use Livewire\Component;
use App\Models\TShipments;

class ViewShipment extends Component
{
    public $shipmentsTypeJob = '';
    public $shipments;
    public $type_shipments = '';
    public $organizationFields = [];
    public function mount($id)
    {
        $this->shipments = TShipments::with(['job', 'container'])->findOrFail($id);

        $this->organizationFields = [
            'Client' => 'client',
            'Shipper' => 'shipper',
            'Consignee' => 'consignee',
            'Notify Party' => 'notify',

        ];
    }
    public function getOrganizationsProperty()
    {
        return collect([
            [
                'label' => 'Client',
                'dataShipments' => $this->shipments->client,
            ],
            [
                'label' => 'Shipper',
                'dataShipments' => optional($this->shipments->shipper),
            ],
            [
                'label' => 'Consignee',
                'dataShipments' => optional($this->shipments->consignee),
            ],
            [
                'label' => 'Notify',
                'dataShipments' => optional($this->shipments->notify),
            ],
        ])->filter(fn($item) => !is_null($item['dataShipments']));
    }

    public function render()
    {
        return view('livewire.shipment.view-shipment');
    }
}
