<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\shipments;
use App\Models\Customer;


class CreateShipments extends Component
{
    public $shipment_id = '';
    public $container_id = '';
    public $container_type = '';
    public $shipper = '';
    public $consignee = '';
    public $notify = '';
    public $ocean_vessel_feeder = '';
    public $ocean_vessel_mother = '';
    public $port_of_discharge = '';
    public $combined_transport = '';
    public $port_of_loading = '';
    public $packages = '';
    public $description = '';
    public $gross_weight = '';
    public $measurement = '';


    public $customers; // Variable to hold the customer data



    public function mount()
    {
        // Fetch customers with roles 'shipper', 'consignee', 'notify' for the dropdowns
        $this->customers = Customer::all(); // Get all customers

        // You could filter this list if needed, e.g. only customers with 'shipper' role
    }

    public function render()
    {
        return view('livewire.create-shipments');
    }


    public function save()
    {
        $validated = $this->validate([
            'shipment_id' => 'required|max:255|unique:shipments',
            'container_id' => 'required|max:255|unique:shipments',
            'container_type' => 'required|max:255',
            'shipper' => 'nullable|string',  // Allowing it to be empty
            'consignee' => 'nullable|string',  // Allowing it to be empty
            'notify' => 'nullable|string',  // Allowing it to be empty
            'ocean_vessel_feeder' => 'nullable|string',  // Allowing it to be empty
            'ocean_vessel_mother' => 'nullable|string',  // Allowing it to be empty
            'port_of_discharge' => 'nullable|string',  // Allowing it to be empty
            'combined_transport' => 'nullable|string',  // Allowing it to be empty
            'port_of_loading' => 'nullable|string',  // Allowing it to be empty
            'packages' => 'nullable|string',  // Allowing it to be empty
            'description' => 'nullable|string',  // Allowing it to be empty
            'gross_weight' => 'string',  // Allowing it to be empty
            'measurement' => 'string'
        ]);


        // If everything looks correct, proceed to create the shipment
        shipments::create([
            'shipment_id' => $this->shipment_id,
            'container_id' => $this->container_id,
            'container_type' => $this->container_type,
            'shipper' => $this->shipper,
            'consignee' => $this->consignee,
            'notify' => $this->notify,
            'port_of_loading' => $this->port_of_loading,
            'description' => $this->description,
            'gross_weight' => $this->gross_weight,
            'measurement' => $this->measurement,
        ]);

        // dd($this->shipper, $this->consignee, $this->notify);
        // dd(session('success'));


        // dd(session('success')); // This will dump the session and stop execution

        return redirect()->route('shipments')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
    }
}
