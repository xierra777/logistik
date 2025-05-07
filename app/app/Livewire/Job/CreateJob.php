<?php

namespace App\Livewire\Job;

use App\Models\TJob;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Container;
use Carbon\Carbon;
use Livewire\Attributes\On;

use Illuminate\Support\Facades\DB;

class CreateJob extends Component
{
    public $step = 1;
    public $type_job;
    public $job_name;
    public $job_id = "";

    public $clients;
    public $shippers;
    public $consignees;
    public $notifies;
    public $carriers;

    public $client_id;
    public $shipper_id;
    public $consignee_id;
    public $notify_id;
    public array $ports = [];

    // Bagian Ocean
    public $shipment_id = "", $shipment_no,
        $carrier, $servicesType = "", $incoTerms,
        $vessel_name = "",
        $ocean_vessel_feeder = "",
        $port_of_discharge = "",
        $place_of_receipt = "",
        $place_of_delivery = "",
        $port_of_loading = "",
        $description = "",
        $estimearrival,
        $estimedelivery, $voyage;

    public $containers = [];



    public function mount()
    {
        $this->containers = [
            ['container_id' => '', 'container_type' => '', 'container_seal' => '', 'gross_weight' => '', 'pack_type' => '', 'measurement' => '', 'pcs' => '', 'unit' => '', 'volume_weight' => '', 'chargeable_weight' => ''],
        ];
        $this->clients = Customer::whereJsonContains('roles', 'client')->get();
        $this->shippers = Customer::whereJsonContains('roles', 'shipper')->get();
        $this->consignees = Customer::whereJsonContains('roles', 'consignee')->get();
        $this->notifies = Customer::whereJsonContains('roles', 'notify')->get();
        $this->carriers = Customer::whereJsonContains('roles', 'carrier')->get();
    }

    public function getClientNameProperty()
    {
        if (!$this->client_id) return '';

        $client = $this->clients->firstWhere('id', $this->client_id);
        return $client ? $client->name : '';
    }

    public function getShipperNameProperty()
    {
        if (!$this->shipper_id) return '';

        $shipper = $this->shippers->firstWhere('id', $this->shipper_id);
        return $shipper ? $shipper->name : '';
    }

    public function getConsigneeNameProperty()
    {
        if (!$this->consignee_id) return '';

        $consignee = $this->consignees->firstWhere('id', $this->consignee_id);
        return $consignee ? $consignee->name : '';
    }

    public function getNotifyNameProperty()
    {
        if (!$this->notify_id) return '';

        $notify = $this->notifies->firstWhere('id', $this->notify_id);
        return $notify ? $notify->name : '';
    }
    #[On('port-updated')]
    public function updatePort($model, $value)
    {
        $this->$model = $value;
    }
    public function nextStep()
    {
        $this->validateCurrentStep();
        $this->step++;
    }
    public function updatedTypeJob()
    {
        $this->generateJobName();
    }

    public function previousStep()
    {
        $this->step--;
    }

    private function validateCurrentStep()
    {
        switch ($this->step) {
            case 1:
                $this->validate([
                    'type_job' => 'required',
                    'client_id' => 'required',
                ]);
                break;

            case 2:
                $rules = [];

                switch ($this->type_job) {
                    case 'ocean_fcl_export':
                        $rules = [
                            'job_id' => 'required',
                            'shipment_no' => 'required',
                            'shipment_id' => 'required'
                        ];
                        break;

                    case 'trucking':
                        $rules = [];
                        break;

                    // Tambahkan case untuk tipe job lainnya
                    default:
                        $rules = [];
                        break;
                }

                $this->validate($rules);
                break;

            case 3:
                break;
        }
    }

    public function submitForm()
    {
        switch ($this->type_job) {
            case 'ocean_fcl_export':
                $this->ocean_fcl_export();
                break;
            case 'ocean_fcl_import':
                $this->ocean_fcl_import();
                break;
            case 'air_export':
                $this->airExport();
                break;
            case 'trucking':
                $this->trucking();
                break;
            default:
                session()->flash('error', 'Job type not recognized.');
        }
    }
    public function generateJobName()
    {
        // Mendapatkan format tanggal dengan YYMMDD
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $countThisMonth = TJob::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $sequence = str_pad($countThisMonth + 1, 3, '0', STR_PAD_LEFT);

        $type = strtoupper(str_replace('_', '-', $this->type_job));
        $date = now()->format('Ym'); // Format: 202505 (bulan dan tahun)

        switch ($this->type_job) {
            case 'ocean_fcl_export':
                $prefix = 'BRNJKTFE';
                break;
            case 'ocean_fcl_import':
                $prefix = 'BRNJKTFI';
                break;
            case 'trucking':
                $prefix = 'BRNJKTTR';
                break;
            case 'air_export':
                $prefix = 'BRNJKTAE';
                break;
            case 'air_import':
                $prefix = 'BRNJKTAI';
                break;
            case 'logistics':
                $prefix = 'BRNJKTLG';
                break;
            default:
                $prefix = 'BRNJKT';  // Default prefix jika type_job tidak dikenali
                break;
        }

        // Format job_name otomatis berdasarkan prefix dan tanggal
        $this->job_id = "{$prefix}{$date}{$sequence}";
    }

    public function ocean_fcl_export()
    {

        $json = file_get_contents(public_path('data/ports.json'));
        $this->ports = json_decode($json, true);

        $container = [];
        $data = [
            'shipment_id'         => $this->shipment_id,
            'shipment_no'         => $this->shipment_no,
            'servicesType'        => $this->servicesType,
            'carrier'             => $this->carrier,
            'incoTerms'           => $this->incoTerms,
            'ocean_vessel_feeder' => $this->ocean_vessel_feeder,
            'vessel_name'         => $this->vessel_name,
            'estimearrival'       => $this->estimearrival,
            'estimedelivery'      => $this->estimedelivery,
            'place_of_receipt'    => $this->place_of_receipt,
            'port_of_discharge'   => $this->port_of_discharge,
            'port_of_loading'     => $this->port_of_loading,
            'description'         => $this->description,
            'shipper_id'          => $this->shipper_id,

        ];
        dd([
            'shipper_id' => $this->shipper_id,
            'consignee_id' => $this->consignee_id,
            'notify_id' => $this->notify_id,
            'type_job' => $this->type_job,
            'job_name' => $this->job_name,
            'data' => $data,
        ]);

        DB::beginTransaction();
        try {
            foreach ($this->containers as $container) {
                Container::create([
                    'shipment_id'            => $shipment->id,
                    'container_id'           => $container['container_id'],
                    'container_type'         => $container['container_type'],
                    'container_seal'         => $container['container_seal'],
                    'pcs'                    => $container['pcs'],
                    'unit'                   => $container['unit'],
                    'gross_weight'           => $container['gross_weight'],
                    'pack_type'              => $container['pack_type'],
                    'volume_weight'          => $container['volume_weight'],
                    'chargeable_weight'      => $container['chargeable_weight'],
                ]);
            }
            TJob::create([
                'job_id' => $this->job_id,
                'job_name' => $this->job_name,
                'type_job' => $this->type_job,
                'shipper_id' => $this->shipper_id,
                'consignee_id' => $this->consignee_id,
                'notify_id' => $this->notify_id,
                'shipment_no' => $this->shipment_no,
                'shipment_id' => $this->shipment_id,
                'data'  => $payload,
                'container' => $container

            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }


        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function render()
    {
        return view('livewire.job.create-job');
    }
}
