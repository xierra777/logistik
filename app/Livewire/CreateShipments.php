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
            ['container_id' => '', 'container_type' => '', 'container_seal' => '', 'gross_weight' => '', 'pack_type' => '', 'measurement' => '', 'pcs' => '', 'unit' => '',]
        ];
    }

    public function addContainer()
    {
        $this->containers[] = ['container_id' => '', 'container_type' => '', 'container_seal' => '', 'gross_weight' => '', 'pack_type' => '', 'measurement' => '', 'pcs' => '', 'unit' => '',];
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
        $validatedData = $this->validate([
            'shipment_id'           => 'required|max:255|unique:shipments,shipment_id',
            'shipper'               => 'nullable|string|max:255',
            'consignee'             => 'nullable|string|max:255',
            'notify'                => 'nullable|string|max:255',
            'ocean_vessel_feeder'   => 'nullable|string|max:255',
            'ocean_vessel_mother'   => 'nullable|string|max:255',
            'port_of_discharge'     => 'nullable|string|max:255',
            'combined_transport'    => 'nullable|string|max:255',
            'port_of_loading'       => 'nullable|string|max:255',
            'description'           => 'nullable|string',
        ]);

        $shipment = Shipment::create($validatedData);


        // Looping dan simpan setiap container yang dimasukkan
        foreach ($this->containers as $index => $container) {
            // Validasi masing-masing container
            $this->validate([
                "containers.$index.container_id"   => 'required|max:255|unique:containers,container_id',
                "containers.$index.container_type" => 'required|max:255',
                "containers.$index.container_seal" => 'required|max:255',
                "containers.$index.gross_weight" => 'required|max:255',
                "containers.$index.pcs" => 'required|max:255',
                "containers.$index.unit" => 'required|max:255',
                "containers.$index.pack_type" => 'required|max:255',
                "containers.$index.measurement" => 'max:255',
            ]);

            Container::create([
                'shipment_id'    => $shipment->id,
                'container_id'   => $container['container_id'],
                'container_type' => $container['container_type'],
                'container_seal' => $container['container_seal'],
                'pcs' => $container['pcs'],
                'unit' => $container['unit'],
                'gross_weight' => $container['gross_weight'],
                'pack_type' => $container['pack_type'],
            ]);
        }

        return redirect()->route('shipments')->with('success', [
            'icon'  => 'success',
            'title' => 'Success!',
        ]);
    }
}
