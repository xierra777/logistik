<?php

namespace App\Livewire\Job;

use App\Models\TJob;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Container;
use App\Models\jobContainer;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\DB;

class CreateJob extends Component
{
    public $step = 1;
    public $type_job = '';
    public $job_name;
    public $job_id = '';

    public $clients;
    public $dagentsJob;
    public $ogentsJob;
    public $carriers;

    public $client_id;
    public $deliveryAgent = "";
    public $originAgent = "";
    public array $ports = [];

    // Bagian Ocean
    public $mbl_no = "", $customerCodeJob,
        $carrier, $servicesType = "", $incoTerms,
        $vessel_name = "",
        $ocean_vessel_feeder = "",
        $port_of_discharge = "",
        $place_of_receipt = "",
        $place_of_delivery = "",
        $port_of_loading = "",
        $description = "",
        $estimearrival,
        $estimedelivery, $voyage, $mbl_date, $cross_trade, $hazardousType, $hazardousClassType, $payableAtJob, $freightTypeJob, $remarksJobDetailJobs;

    // Container Section
    public $containerType, $noOfPackages, $containerReleaseNo, $containerReleaseDate, $typeOfPackages, $grossWeight, $typeOfGrossWeight, $volumeWeight, $typeOfVolumeWeight, $volume, $chargableWeight, $containerRemarks, $containerNo, $containerSealNo, $noOfPallet, $netOfWeight, $typeNetOfWeight, $totalWeight, $typeOfTotalWeight, $hsCode, $hsCodeDesc;



    public function mount()
    {

        $this->clients = Customer::whereJsonContains('roles', 'client')->get();
        $this->dagentsJob = Customer::whereJsonContains('roles', 'agent')->get();
        $this->ogentsJob = Customer::whereJsonContains('roles', 'agent')->get();
        $this->carriers = Customer::whereJsonContains('roles', 'carrier')->get();
    }


    public function getClientNameProperty()
    {
        if (!$this->client_id) return '';

        $client = $this->clients->firstWhere('id', $this->client_id);
        return $client ? $client->name : '';
    }
    public function getDagentNameProperty()
    {
        if (!$this->deliveryAgent) return '';

        $agent = $this->dagentsJob->firstWhere('id', $this->deliveryAgent);
        return $agent?->name ?? '';
    }

    public function getOgentNameProperty()
    {
        if (!$this->outputAgent) return '';

        $agent = $this->ogentsJob->firstWhere('id', $this->outputAgent);
        return $agent?->name ?? '';
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
        // $this->deliveryAgent = null;
        // $this->originAgent = null;
        // // Reset juga semua input yang gak relevan dengan type_job
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
                ]);
                break;

            case 2:
                $rules = [];

                switch ($this->type_job) {
                    case 'ocean_fcl_export':
                        $rules = [
                            'job_id' => 'required',

                        ];
                        break;
                    case 'ocean_fcl_import':
                        $rules = [
                            'job_id' => 'required',

                        ];
                        break;
                    case 'ocean_lcl_export':
                        $rules = [
                            'job_id' => 'required',

                        ];
                    case 'ocean_lcl_import':
                        $rules = [
                            'job_id' => 'required',

                        ];
                        break;
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



        $container = [
            'containerType'       => $this->containerType,
            'containerReleaseNo'  => $this->containerReleaseNo,
            'containerNo'         => $this->containerNo,
            'containerReleaseDate' => $this->containerReleaseDate,
            'noOfPackages'        => $this->noOfPackages,
            'typeOfPackages'      => $this->typeOfPackages,
            'grossWeight'         => $this->grossWeight,
            'typeOfGrossWeight'   => $this->typeOfGrossWeight,
            'volumeWeight'        => $this->volumeWeight,
            'typeOfVolumeWeight'  => $this->typeOfVolumeWeight,
            'volume'              => $this->volume,
            'containerSealNo'     => $this->containerSealNo,
            'noOfPallet'          => $this->noOfPallet,
            'netOfWeight'         => $this->netOfWeight,
            'typeNetOfWeight'     => $this->typeNetOfWeight,
            'totalWeight'         => $this->totalWeight,
            'typeOfTotalWeight'   => $this->typeOfTotalWeight,
            'hsCode'              => $this->hsCode,
            'hsCodeDesc'          => $this->hsCodeDesc,
        ];
        $data = [
            'mbl_no'              => $this->mbl_no,
            'mbl_date'            => $this->mbl_date,
            'customerCodeJob'     => $this->customerCodeJob, //akandibuat query
            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'carrier'             => $this->carrier, //harus dibuat query
            'vessel_name'         => $this->vessel_name,
            'voyage'              => $this->voyage,


            'ocean_vessel_feeder' => $this->ocean_vessel_feeder, //hidden right here
            'cross_trade'         => $this->cross_trade, //buat relasinya besok soalnya diquery nanti
            'harzardousType'      => $this->hazardousType, //kemungkinan query
            'hazardousClassType'  => $this->hazardousClassType,

            'payableAtJob'        => $this->payableAtJob, //Kemungkinan Query Terjadi
            'freightTypeJob'      => $this->freightTypeJob,
            'remarksJobDetailJobs' => $this->remarksJobDetailJobs,

            'estimearrival'       => $this->estimearrival,
            'estimedelivery'      => $this->estimedelivery,
            'place_of_receipt'    => $this->place_of_receipt,
            'port_of_discharge'   => $this->port_of_discharge,
            'place_of_delivery'   => $this->place_of_delivery,
            'port_of_loading'     => $this->port_of_loading,
        ];
        // dd([
        //     'shipper_id' => $this->shipper_id,
        //     'consignee_id' => $this->consignee_id,
        //     'notify_id' => $this->notify_id,
        //     'type_job' => $this->type_job,
        //     'job_name' => $this->job_name,
        //     'data' => $data,
        //     'container' => $container
        // ]);


        $job = TJob::create([
            'job_id' => $this->job_id,
            'type_job' => $this->type_job,
            'client_id' => $this->client_id,
            'dagentsJob' => $this->deliveryAgent,

            // 'shipper_id' => $this->shipper_id,
            // 'consignee_id' => $this->consignee_id,
            // 'notify_id' => $this->notify_id,
            'data'  => $data,
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container
        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function ocean_fcl_import()
    {



        $container = [
            'containerType'       => $this->containerType,
            'containerReleaseNo'  => $this->containerReleaseNo,
            'containerReleaseDate' => $this->containerReleaseDate,
            'noOfPackages'        => $this->noOfPackages,
            'typeOfPackages'      => $this->typeOfPackages,
            'grossWeight'         => $this->grossWeight,
            'typeOfGrossWeight'   => $this->typeOfGrossWeight,
            'volumeWeight'        => $this->volumeWeight,
            'typeOfVolumeWeight'  => $this->typeOfVolumeWeight,
            'volume'              => $this->volume,
            'containerSealNo'     => $this->containerSealNo,
            'noOfPallet'          => $this->noOfPallet,
            'netOfWeight'         => $this->netOfWeight,
            'typeNetOfWeight'     => $this->typeNetOfWeight,
            'totalWeight'         => $this->totalWeight,
            'typeOfTotalWeight'   => $this->typeOfTotalWeight,
            'hsCode'              => $this->hsCode,
            'hsCodeDesc'          => $this->hsCodeDesc,
        ];
        $data = [
            'mbl_no'              => $this->mbl_no,
            'mbl_date'            => $this->mbl_date,
            'customerCodeJob'     => $this->customerCodeJob,
            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'deliveryAgent'       => $this->deliveryAgent,
            'carrier'             => $this->carrier,
            'vessel_name'         => $this->vessel_name,
            'voyage'              => $this->voyage,


            'ocean_vessel_feeder' => $this->ocean_vessel_feeder, //hidden right here
            'cross_trade'         => $this->cross_trade, //buat relasinya besok soalnya diquery nanti
            'harzardousType'      => $this->hazardousType,
            'hazardousClassType'  => $this->hazardousClassType,

            'payableAtJob'        => $this->payableAtJob, //Kemungkinan Query Terjadi
            'freightTypeJob'      => $this->freightTypeJob,
            'remarksJobDetailJobs' => $this->remarksJobDetailJobs,

            'estimearrival'       => $this->estimearrival,
            'estimedelivery'      => $this->estimedelivery,
            'place_of_receipt'    => $this->place_of_receipt,
            'port_of_discharge'   => $this->port_of_discharge,
            'place_of_delivery'   => $this->place_of_delivery,
            'port_of_loading'     => $this->port_of_loading,
            'description'         => $this->description,
        ];
        // dd([
        //     'shipper_id' => $this->shipper_id,
        //     'consignee_id' => $this->consignee_id,
        //     'notify_id' => $this->notify_id,
        //     'type_job' => $this->type_job,
        //     'job_name' => $this->job_name,
        //     'data' => $data,
        //     'container' => $container
        // ]);


        $job = TJob::create([
            'job_id' => $this->job_id,
            'type_job' => $this->type_job,
            'client_id' => $this->client_id,
            'ogentsJob' => $this->originAgent,
            'dagentsJob' => $this->deliveryAgent,
            'data'  => $data,
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container
        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function render()
    {
        return view('livewire.job.create-job');
    }
}
