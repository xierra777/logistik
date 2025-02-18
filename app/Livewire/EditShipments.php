<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\shipments;

class EditShipments extends Component
{
    public $shipments;
    public $shipment_id;
    public $container_id;
    public $container_type;
    public $shipper;
    public $consignee;
    public $notify;
    public $ocean_vessel_feeder;
    public $ocean_vessel_mother;
    public $port_of_discharge;
    public $combined_transport;
    public $port_of_loading;
    public $packages;
    public $description;
    public $gross_weight;
    public $measurement;

    public function mount(Shipments $id)
    {
        $this->shipments = $id;
        $this->shipment_id = $id->shipment_id;
        $this->container_id = $id->container_id;
        $this->container_type = $id->container_type;
        $this->shipper = $id->shipper;
        $this->notify = $id->notify;
        $this->ocean_vessel_feeder = $id->ocean_vessel_feeder;
        $this->ocean_vessel_mother = $id->ocean_vessel_mother;
        $this->port_of_discharge = $id->port_of_discharge;
        $this->combined_transport = $id->combined_transport;
        $this->port_of_loading = $id->port_of_loading;
        $this->packages = $id->packages;
        $this->description = $id->description;
        $this->gross_weight = $id->gross_weight;
        $this->measurement = $id->measurement;
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
