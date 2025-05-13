<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Container;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use Termwind\Components\Dd;

class EditShipments extends Component
{
    // Field Shipment
    public $shipment_id = '';
    public $shipment_no = '';
    public $shipments;
    public $liners = '';
    public $servicesType = '';
    public $jobType = '';
    public $shipper_id;
    public $consignee_id;
    public $notify_id;
    public $ocean_vessel_feeder = '';
    public $ocean_vessel_mother = '';
    public $port_of_discharge = '';
    public $place_of_receipt = '';
    public $port_of_loading = '';
    public $description = '';
    public $gross_weight = '';
    public $measurement = '';
    public $estimearrival;
    public $estimedelivery;

    // Field untuk input dinamis Container
    public $containers = [];
    public $customers;
    public function mount(Shipment $id)
    {
        $this->customers = Customer::all();

        // Inisialisasi minimal dengan satu baris container
        $this->containers = [
            ['container_id' => '', 'container_type' => '', 'container_seal' => '', 'gross_weight' => '', 'pack_type' => '', 'measurement' => '', 'pcs' => '', 'unit' => '', 'volume_weight' => '', 'chargeable_weight' => ''],
        ];
        $this->shipments = $id;
        $this->shipment_id = $id->shipment;
        $this->shipper_id = $id->shipper;
        $this->notify_id = $id->notify;
        $this->ocean_vessel_feeder = $id->ocean_vessel_feeder;
        $this->ocean_vessel_mother = $id->ocean_vessel_mother;
        $this->port_of_discharge = $id->port_of_discharge;
        $this->port_of_loading = $id->port_of_loading;
        $this->description = $id->description;
    }
    public function render()
    {
        return view('livewire.edit-shipments');
    }

    public function updateShipments()
    {
        $validated = $this->validate([
            'shipment_id' => 'required|max:255',
            'container_id' => 'required|max:255',
            'container_type' => 'required|max:255',
            'shipper' => 'nullable|string',  // Allowing it to be empty
            'consignee' => 'nullable|string',  // Allowing it to be empty
            'notify' => 'nullable|string',  // Allowing it to be empty
            'ocean_vessel_feeder' => 'nullable|string',  // Allowing it to be empty
            'ocean_vessel_mother' => 'nullable|string',  // Allowing it to be empty
            'port_of_discharge' => 'nullable|string',  // Allowing it to be empty
            'combined_transport' => 'nullable|string',  // Allowing it to be emptys
            'port_of_loading' => 'nullable|string',  // Allowing it to be empty
            'packages' => 'nullable|string',  // Allowing it to be empty
            'description' => 'nullable|string',  // Allowing it to be empty
            'gross_weight' => 'string',  // Allowing it to be empty
            'measurement' => 'string'
        ]);

        $this->shipments->update($validated);

        // Set the success message in session
        session()->flash('success', [
            'icon' => 'success',
            'title' => 'Updated!',
            'text' => 'Shipment updated successfully.',
            'iconColor' => 'yellow'
        ]);

        // Redirect to the shipments index page
        return redirect()->route('shipments');
    }
}
