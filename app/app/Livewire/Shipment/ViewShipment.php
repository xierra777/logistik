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
    public $refreshKey = null;
    protected $listeners = [
        'transactionSaved' => 'refreshShipment',

    ];
    public function mount($id)
    {
        $this->shipments = TShipments::with([
            'job',
            'container',
            'client.addresses',
            'shipper.addresses',
            'consignee.addresses',
            'notify.addresses',
            'deliveryAgent',
            'carrierModel',
            'carrierAgent',
            'shipmentTransaction',
        ])->findOrFail($id);

        $this->organizationFields = [
            'Client' => 'client',
            'Shipper' => 'shipper',
            'Consignee' => 'consignee',
            'Notify Party' => 'notify',

        ];
    }
    public function refreshTransaction()
    {
        $this->refreshKey = now()->timestamp;
    }
    public function getOrganizationsProperty()
    {
        return collect([
            [
                'label' => 'Client',
                'dataShipments' => optional($this->shipments->client) ? (object)[
                    'id' => $this->shipments->client->id,
                    'name' => $this->shipments->client->name,
                    'email' => $this->shipments->client->email,
                    'contact' => $this->shipments->client->contact,
                    'address' => $this->shipments->shipmentClient_address,
                ] : null,
            ],
            [
                'label' => 'Shipper',
                'dataShipments' => optional($this->shipments->shipper) ? (object)[
                    'id' => $this->shipments->shipper->id,
                    'name' => $this->shipments->shipper->name,
                    'email' => $this->shipments->shipper->email,
                    'contact' => $this->shipments->shipper->contact,
                    'address' => optional($this->shipments->shipper->addresses->first())->address,
                ] : null,
            ],
            [
                'label' => 'Consignee',
                'dataShipments' => optional($this->shipments->consignee) ? (object)[
                    'id' => $this->shipments->consignee->id,
                    'name' => $this->shipments->consignee->name,
                    'email' => $this->shipments->consignee->email,
                    'contact' => $this->shipments->consignee->contact,
                    'address' => optional($this->shipments->consignee->addresses->first())->address,
                ] : null,
            ],
            [
                'label' => 'Notify',
                'dataShipments' => optional($this->shipments->notify) ? (object)[
                    'id' => $this->shipments->notify->id,
                    'name' => $this->shipments->notify->name,
                    'email' => $this->shipments->notify->email,
                    'contact' => $this->shipments->notify->contact,
                    'address' => optional($this->shipments->notify->addresses->first())->address,
                ] : null,
            ],
        ])->filter(fn($item) => !is_null($item['dataShipments']));
    }




    public function render()
    {
        return view('livewire.shipment.view-shipment');
    }
}
