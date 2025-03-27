<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Container;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;


class CreateShipments extends Component
{
    // Field Shipment
    public $shipment_id = '';
    public $shipment_no = '';
    public $shipper = '';
    public $consignee = '';
    public $notify = '';
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

    #[On('port-updated')]
    public function updatePort($model, $value)
    {
        $this->$model = $value;
    }

    public function mount()
    {
        $this->customers = Customer::all();
        if (empty($this->shipment_no)) {
            $this->shipment_no = $this->generateInvoiceNumber();
        }
        // Inisialisasi minimal dengan satu baris container
        $this->containers = [
            ['container_id' => '', 'container_type' => '', 'container_seal' => '', 'gross_weight' => '', 'pack_type' => '', 'measurement' => '', 'pcs' => '', 'unit' => '', 'volume_weight' => '', 'chargeable_weight' => ''],
        ];
    }

    public function generateInvoiceNumber()
    {
        return "BRNJKT" . now()->format('ym') . str_pad(Shipment::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
    }
    public function addContainer()
    {
        $this->containers[] = ['container_id' => '', 'container_type' => '', 'container_seal' => '', 'gross_weight' => '', 'pack_type' => '', 'measurement' => '', 'pcs' => '', 'unit' => '', 'volume_weight' => '', 'chargeable_weight' => ''];
    }

    public function removeContainer($index)
    {
        unset($this->containers[$index]);
        // Reindex array agar konsisten
        $this->containers = array_values($this->containers);
    }



    public function save()
    {
        // Validasi data shipment (tanpa container_id dan container_type karena data container dikelola secara terpisah)
        // Buat shipment baru
        $validatedData =

            $this->validate([
                'shipment_id'           => 'required|max:255|unique:shipments,shipment_id',
                'shipment_no'           => 'required|max:255|unique:shipments,shipment_no',
                'place_of_receipt'      => 'nullable|string|max:255',
                'shipper'               => 'nullable|string|max:255',
                'consignee'             => 'nullable|string|max:255',
                'notify'                => 'nullable|string|max:255',
                'estimearrival'         => 'nullable|date',
                'estimedelivery'        => 'nullable|date',
                'ocean_vessel_feeder'   => 'nullable|string|max:255',
                'ocean_vessel_mother'   => 'nullable|string|max:255',
                'port_of_discharge'     => 'nullable|string|max:255',
                'port_of_loading'       => 'nullable|string|max:255',
                'description'           => 'nullable|string',
            ]);

        // Validate Containers
        if (empty($this->containers)) {
            session()->flash('error', 'Minimal harus ada satu container.');
            return;
        }


        // Looping dan simpan setiap container yang dimasukkan
        foreach ($this->containers as $index => $container) {
            $this->validate([
                "containers.$index.container_id"   => 'required|max:255|unique:containers,container_id',
                "containers.$index.container_type" => 'required|max:255',
                "containers.$index.container_seal" => 'required|max:255',
                "containers.$index.gross_weight"   => 'required|max:255',
                "containers.$index.pcs"           => 'required|max:255',
                "containers.$index.unit"          => 'required|max:255',
                "containers.$index.pack_type"     => 'required|max:255',
                "containers.$index.measurement"   => 'max:255',
                "containers.$index.volume_weight"   => 'max:255',
                "containers.$index.chargeable_weight"   => 'max:255',
            ]);
        }



        DB::beginTransaction();
        try {
            $shipment = Shipment::create($validatedData);

            foreach ($this->containers as $container) {
                Container::create([
                    'shipment_id'    => $shipment->id,
                    'container_id'   => $container['container_id'],
                    'container_type' => $container['container_type'],
                    'container_seal' => $container['container_seal'],
                    'pcs'            => $container['pcs'],
                    'unit'           => $container['unit'],
                    'gross_weight'   => $container['gross_weight'],
                    'pack_type'      => $container['pack_type'],
                    'volume_weight'      => $container['volume_weight'],
                    'chargeable_weight'      => $container['chargeable_weight'],
                ]);
            }

            DB::commit();

            return redirect()->route('shipments')->with('success', [
                'icon'  => 'success',
                'title' => 'Success!',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.create-shipments');
    }
}
