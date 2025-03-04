<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Container;
use App\Models\shipment;

class CreateShipments extends Component
{
    // Field Shipment
    public $shipment_id = '';
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

    // Field untuk input dinamis Container
    public $containers = [];
    public $customers;

    public function mount()
    {
        $this->customers = Customer::all();
        // Inisialisasi minimal dengan satu baris container
        $this->containers = [
            ['container_id' => '', 'container_type' => '']
        ];
    }

    public function addContainer()
    {
        $this->containers[] = ['container_id' => '', 'container_type' => ''];
    }

    public function removeContainer($index)
    {
        unset($this->containers[$index]);
        // Reindex array agar konsisten
        $this->containers = array_values($this->containers);
    }

    public function render()
    {
        return view('livewire.create-shipments');
    }

    public function save()
    {
        // Validasi data shipment (tanpa container_id dan container_type karena data container dikelola secara terpisah)
        // Buat shipment baru
        $shipment = shipment::create([
            'shipment_id'         => $this->shipment_id,
            'shipper'             => $this->shipper,
            'consignee'           => $this->consignee,
            'notify'              => $this->notify,
            'ocean_vessel_feeder' => $this->ocean_vessel_feeder,
            'ocean_vessel_mother' => $this->ocean_vessel_mother,
            'port_of_discharge'   => $this->port_of_discharge,
            'combined_transport'  => $this->combined_transport,
            'port_of_loading'     => $this->port_of_loading,
            'packages'            => $this->packages,
            'description'         => $this->description,
            'gross_weight'        => $this->gross_weight,
            'measurement'         => $this->measurement,
        ]);

        // Looping dan simpan setiap container yang dimasukkan
        foreach ($this->containers as $index => $container) {
            // Validasi masing-masing container
            $this->validate([
                "containers.$index.container_id"   => 'required|max:255|unique:containers,container_id',
                "containers.$index.container_type" => 'required|max:255',
            ]);

            Container::create([
                'shipment_id'    => $shipment->id,
                'container_id'   => $container['container_id'],
                'container_type' => $container['container_type'],
            ]);
        }

        return redirect()->route('shipments')->with('success', [
            'icon'  => 'success',
            'title' => 'Success!',
        ]);
    }
}
