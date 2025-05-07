<?php

namespace App\Livewire\Job;

use App\Models\TJob;
use Livewire\Component;
use App\Models\Customer;
use Carbon\Carbon;
use Livewire\Attributes\On;

class EditJob extends Component
{
    public $step = 1;
    public $type_job;
    public $job_name;
    public $job_id = "";

    public $clients;
    public $shippers;
    public $consignees;
    public $notifies;

    public $client_id;
    public $shipper_id;
    public $consignee_id;
    public $notify_id;

    // Bagian Ocean
    public $shipment_id = "", $shipment_no,
        $liners = "", $servicesType = "",
        $ocean_vessel_feeder = "",
        $ocean_vessel_mother = "",
        $port_of_discharge = "",
        $place_of_receipt = "",
        $port_of_loading = "",
        $description = "",
        $estimearrival,
        $estimedelivery;

    public $container_number = "";
    public $container_size = "";
    public $container_id = "";


    public function mount()
    {

        $this->clients = Customer::whereJsonContains('roles', 'client')->get();
        $this->shippers = Customer::whereJsonContains('roles', 'shipper')->get();
        $this->consignees = Customer::whereJsonContains('roles', 'consignee')->get();
        $this->notifies = Customer::whereJsonContains('roles', 'notify')->get();
    }
    protected $listeners = ['portUpdated'];

    public function portUpdated($model, $value)
    {
        $this->{$model} = $value;
    }
    public function nextStep()
    {
        $this->validateCurrentStep();
        $this->step++;
        $this->dispatch('reinit-select2');
    }
    public function updatedTypeJob()
    {
        $this->generateJobName();
        $this->dispatch('reinit-select2');
    }

    public function previousStep()
    {
        $this->step--;
        $this->dispatch('reinit-select2');
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
                            'job_name' => 'required',
                            'shipment_no' => 'required',
                            'shipment_id' => 'required'
                        ];
                        break;

                    case 'trucking':
                        $rules = ['trucking_detail' => 'required'];
                        break;

                    // Tambahkan case untuk tipe job lainnya
                    default:
                        $rules = [];
                        break;
                }

                $this->validate($rules);
                break;

            case 3:
                $this->validate([
                    'container_number' => 'required',
                    'container_size' => 'required'
                ]);
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
        $this->job_name = "{$prefix}{$date}{$sequence}";
    }

    public function ocean_fcl_export()
    {
        $payload = [
            // Data payload khusus untuk Ocean FCL Export
            'shipment_id'         => $this->shipment_id,
            'shipment_no'         => $this->shipment_no,
            'servicesType'        => $this->servicesType,
            'liners'              => $this->liners,
            'ocean_vessel_feeder' => $this->ocean_vessel_feeder,
            'ocean_vessel_mother' => $this->ocean_vessel_mother,
            'estimearrival'       => $this->estimearrival,
            'estimedelivery'      => $this->estimedelivery,
            'place_of_receipt'    => $this->place_of_receipt,
            'port_of_discharge'   => $this->port_of_discharge,
            'port_of_loading'     => $this->port_of_loading,
            'description'         => $this->description,
        ];
        dd([
            'shipper_id' => $this->shipper_id,
            'consignee_id' => $this->consignee_id,
            'notify_id' => $this->notify_id,
            'type_job' => $this->type_job,
            'job_name' => $this->job_name,
            'data' => $payload,
        ]);


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

        ]);
        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function render()
    {
        return view('livewire.job.edit-job');
    }
}
