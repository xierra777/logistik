<?php

namespace App\Livewire\Job;

use App\Models\TJob;
use Livewire\Component;
use App\Models\Customer;
use App\Models\Container;
use App\Models\jobContainer;
use Carbon\Carbon;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateJob extends Component
{
    public $step = 1;
    public $type_job = '';
    public $job_name;
    public $job_id = '';
    public $airlines;
    public $clients;
    public $dagentsJob;
    public $ogentsJob;
    public $carriers;
    public $employe;
    public $client_id;
    public $deliveryAgent = "";
    public $originAgent = "";
    public array $ports = [];

    // Bagian Ocean
    public $jobBillLadingdNo = "", $houseJobBillLadingNo, $houseJobBillLadingDate, $customerCodeJob,
        $carrierAirline, $servicesType = "", $incoTerms,
        $flightVesselName = "",
        $ocean_vessel_feeder = "",
        $port_of_discharge = "",
        $place_of_receipt = "",
        $place_of_delivery = "",
        $port_of_loading = "",
        $port_of_final = "",
        $port_of_receipt = "",
        $description = "",
        $estimearrival,
        $estimedelivery, $flightVesselNo, $cross_trade, $hazardousType, $hazardousClassType, $payableAtJob, $freightTypeJob, $remarksJobDetailJobs;
    public $jobEmployee;
    // Bagian Air
    public $jobBillLadingNo, $jobBillLadingDate, $airlinesJob;
    // Container Section
    public $containerType, $noOfPackages, $containerReleaseNo, $containerReleaseDate, $typeOfPackages, $grossWeight, $typeOfGrossWeight, $volumeWeight, $typeOfVolumeWeight, $volume, $chargableWeight, $containerRemarks, $containerNo, $containerSealNo, $noOfPallet, $netOfWeight, $typeNetOfWeight, $totalWeight, $typeOfTotalWeight, $hsCode, $hsCodeDesc;



    public function mount()
    {
        $this->clients = Customer::whereJsonContains('roles', 'client')->get();
        $this->dagentsJob = Customer::whereJsonContains('roles', 'delivery_agent')->get();
        $this->ogentsJob = Customer::whereJsonContains('roles', 'origin_agent')->get();
        $this->carriers = Customer::whereJsonContains('roles', 'carrier')->get();
        $this->airlines = Customer::whereJsonContains('roles', 'airline')->get();
        $this->employe = User::all('id', 'name');
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
    public function getCarrierAirlineNameProperty()
    {
        if (!$this->carrierAirline) return '';

        if (in_array($this->type_job, ['air_inbound', 'air_outbound'])) {
            $carrier = $this->airlines->firstWhere('id', $this->carrierAirline);
        } else {
            $carrier = $this->carriers->firstWhere('id', $this->carrierAirline);
        }

        return $carrier ? $carrier->name : '';
    }
    public function getOgentNameProperty()
    {
        if (!$this->originAgent) return '';

        $agent = $this->ogentsJob->firstWhere('id', $this->originAgent);
        return $agent?->name ?? '';
    }
    public function updatedClientId()
    {
        $this->generateCustCode();
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
        // dd($this->carrierAirlineName);
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
                        break;

                    case 'ocean_lcl_import':
                        $rules = [
                            'job_id' => 'required',
                        ];
                        break;
                    case 'air_outbound':
                        $rules = [
                            'job_id' => 'required',
                        ];
                        break;
                    case 'air_inbound':
                        $rules = [];
                        break;

                    default:
                        $rules = ['job_id' => 'required'];
                        break;
                }
                // $this->generateCustCode();

                // $this->validate($rules);
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
            case 'ocean_lcl_export':
                $this->ocean_lcl_export();
                break;
            case 'ocean_lcl_import':
                $this->ocean_lcl_import();
                break;
            case 'air_outbound':
                $this->air_outbound();
                break;
            case 'air_inbound':
                $this->air_inbound();
                break;
            case 'trucking':
                $this->trucking();
                break;
            case 'domestic_transportation':
                $this->domestic_transportation();
                break;
            case 'logistics':
                $this->logistics();
                break;

            default:
                session()->flash('error', 'Job type not recognized.');
        }
    }
    public function generateCustCode()
    {
        if (!$this->client_id) {
            $this->customerCodeJob = null;
            return;
        }

        $client = Customer::find($this->client_id);
        if (!$client) {
            $this->customerCodeJob = null;
            return;
        }

        $this->customerCodeJob = $client->customer_code;
    }
    public function generateJobName()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $countThisMonth = TJob::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        $sequence = str_pad($countThisMonth + 1, 3, '0', STR_PAD_LEFT);

        $type = strtoupper(str_replace('_', '-', $this->type_job));
        $date = now()->format('ym');

        switch ($this->type_job) {
            case 'ocean_fcl_export':
                $prefix = 'BRNJKTFE';
                break;
            case 'ocean_fcl_import':
                $prefix = 'BRNJKTFI';
                break;
            case 'ocean_lcl_export':
                $prefix = 'BRNJKTLE';
                break;
            case 'ocean_lcl_import':
                $prefix = 'BRNJKTLI';
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
            case 'air_inbound':
                $prefix = 'BRNJKTAI';
                break;
            case 'air_outbound':
                $prefix = 'BRNJKTAO';
                break;
            case 'trucking':
                $prefix = 'BRNJKTTRC';
                break;
            case 'logistics':
                $prefix = 'BRNJKTLGS';
                break;
            case 'domestic_transportation':
                $prefix = 'BRNJKTDOT';
                break;
            default:
                $prefix = 'BRNJKT';
                break;
        }
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

            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'flightVesselName'    => $this->flightVesselName,
            'flightVesselNo'      => $this->flightVesselNo,
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
            'port_of_final'       => $this->port_of_final,
            'port_of_receipt'     => $this->port_of_receipt,
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
            'job_id'                 => $this->job_id,
            'type_job'               => $this->type_job,
            'client_id'              => $this->client_id,
            'dagentsJob'             => $this->deliveryAgent ?: null,
            'carrierAirline'         => $this->carrierAirline,
            'employee_id'            => $this->jobEmployee,
            'customerCodeJob'        => $this->customerCodeJob,
            'jobBillLadingNo'        => $this->jobBillLadingNo,
            'jobBillLadingDate'      => $this->jobBillLadingDate,
            'houseJobBillLadingNo'   => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data'                   => $data,
            'created_by'             => Auth::user()->id
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container,
            'created_by'        => Auth::user()->id

        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
        $this->reset();

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function ocean_lcl_export()
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

            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'flightVesselName'    => $this->flightVesselName,
            'flightVesselNo'      => $this->flightVesselNo,
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
            'port_of_final'       => $this->port_of_final,
            'port_of_receipt'     => $this->port_of_receipt,
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
            'job_id'            => $this->job_id,
            'type_job'          => $this->type_job,
            'client_id'         => $this->client_id,
            'dagentsJob'        => $this->deliveryAgent ?: null,
            'carrierAirline'    => $this->carrierAirline,
            'employee_id'       => $this->jobEmployee,
            'customerCodeJob'     => $this->customerCodeJob,
            'jobBillLadingNo'     => $this->jobBillLadingNo,
            'jobBillLadingDate'   => $this->jobBillLadingDate,
            'houseJobBillLadingNo' => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data'              => $data,
            'created_by'        => Auth::user()->id
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container,
            'created_by'        => Auth::user()->id

        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
        $this->reset();

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function ocean_lcl_import()
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

            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'flightVesselName'    => $this->flightVesselName,
            'flightVesselNo'      => $this->flightVesselNo,
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
            'port_of_final'       => $this->port_of_final,
            'port_of_receipt'     => $this->port_of_receipt,
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
            'job_id'            => $this->job_id,
            'type_job'          => $this->type_job,
            'client_id'         => $this->client_id,
            'ogentsJob'        => $this->originAgent ?: null,
            'carrierAirline'    => $this->carrierAirline,
            'employee_id'       => $this->jobEmployee,
            'customerCodeJob'     => $this->customerCodeJob,
            'jobBillLadingNo'     => $this->jobBillLadingNo,
            'jobBillLadingDate'   => $this->jobBillLadingDate,
            'houseJobBillLadingNo' => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data'              => $data,
            'created_by'        => Auth::user()->id
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container,
            'created_by'        => Auth::user()->id

        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
        $this->reset();

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function ocean_fcl_import()
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

            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'flightVesselName'    => $this->flightVesselName,
            'flightVesselNo'      => $this->flightVesselNo,
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
            'port_of_final'       => $this->port_of_final,
            'port_of_receipt'     => $this->port_of_receipt,
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
            'job_id'            => $this->job_id,
            'type_job'          => $this->type_job,
            'client_id'         => $this->client_id,
            'ogentsJob'        => $this->originAgent ?: null,
            'carrierAirline'    => $this->carrierAirline,
            'employee_id'       => $this->jobEmployee,
            'customerCodeJob'     => $this->customerCodeJob,
            'jobBillLadingNo'     => $this->jobBillLadingNo,
            'jobBillLadingDate'   => $this->jobBillLadingDate,
            'houseJobBillLadingNo' => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data'              => $data,
            'created_by'        => Auth::user()->id
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container,
            'created_by'        => Auth::user()->id

        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
        $this->reset();

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function air_outbound()
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
            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'flightVesselName'    => $this->flightVesselName,
            'flightVesselNo'      => $this->flightVesselNo,
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
            'port_of_final'       => $this->port_of_final,
            'port_of_receipt'     => $this->port_of_receipt,
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
            'job_id'            => $this->job_id,
            'type_job'          => $this->type_job,
            'client_id'         => $this->client_id,
            'dagentsJob'        => $this->deliveryAgent ?: null,
            'carrierAirline'    => $this->carrierAirline,
            'employee_id'       => $this->jobEmployee,
            'customerCodeJob'     => $this->customerCodeJob,
            'jobBillLadingNo'     => $this->jobBillLadingNo,
            'jobBillLadingDate'   => $this->jobBillLadingDate,
            'houseJobBillLadingNo' => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data'              => $data,
            'created_by'        => Auth::user()->id
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container,
            'created_by'        => Auth::user()->id

        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
        $this->reset();

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function air_inbound()
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
            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'flightVesselName'    => $this->flightVesselName,
            'flightVesselNo'      => $this->flightVesselNo,
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
            'port_of_final'       => $this->port_of_final,
            'port_of_receipt'     => $this->port_of_receipt,
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
            'job_id'            => $this->job_id,
            'type_job'          => $this->type_job,
            'client_id'         => $this->client_id,
            'ogentsJob'        => $this->originAgent ?: null,
            'carrierAirline'    => $this->carrierAirline,
            'employee_id'       => $this->jobEmployee,
            'customerCodeJob'     => $this->customerCodeJob,
            'jobBillLadingNo'     => $this->jobBillLadingNo,
            'jobBillLadingDate'   => $this->jobBillLadingDate,
            'houseJobBillLadingNo' => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data'              => $data,
            'created_by'        => Auth::user()->id
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container,
            'created_by'        => Auth::user()->id

        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
        $this->reset();

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function logistics()
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
            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'flightVesselName'    => $this->flightVesselName,
            'flightVesselNo'      => $this->flightVesselNo,
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
            'port_of_final'       => $this->port_of_final,
            'port_of_receipt'     => $this->port_of_receipt,
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
            'job_id'            => $this->job_id,
            'type_job'          => $this->type_job,
            'client_id'         => $this->client_id,
            'carrierAirline'    => $this->carrierAirline,
            'employee_id'       => $this->jobEmployee,
            'customerCodeJob'     => $this->customerCodeJob,
            'jobBillLadingNo'     => $this->jobBillLadingNo,
            'jobBillLadingDate'   => $this->jobBillLadingDate,
            'houseJobBillLadingNo' => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data'              => $data,
            'created_by'        => Auth::user()->id
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container,
            'created_by'        => Auth::user()->id

        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
        $this->reset();

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function trucking()
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
            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'flightVesselName'    => $this->flightVesselName,
            'flightVesselNo'      => $this->flightVesselNo,
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
            'port_of_final'       => $this->port_of_final,
            'port_of_receipt'     => $this->port_of_receipt,
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
            'job_id'            => $this->job_id,
            'type_job'          => $this->type_job,
            'client_id'         => $this->client_id,
            'carrierAirline'    => $this->carrierAirline,
            'employee_id'       => $this->jobEmployee,
            'customerCodeJob'     => $this->customerCodeJob,
            'jobBillLadingNo'     => $this->jobBillLadingNo,
            'jobBillLadingDate'   => $this->jobBillLadingDate,
            'houseJobBillLadingNo' => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data'              => $data,
            'created_by'        => Auth::user()->id
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container,
            'created_by'        => Auth::user()->id

        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
        $this->reset();

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function domestic_transportation()
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
            'servicesType'        => $this->servicesType,
            'incoTerms'           => $this->incoTerms,
            'flightVesselName'    => $this->flightVesselName,
            'flightVesselNo'      => $this->flightVesselNo,
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
            'port_of_final'       => $this->port_of_final,
            'port_of_receipt'     => $this->port_of_receipt,
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

        // dd($this->job_id);

        $job = TJob::create([
            'job_id'            => $this->job_id,
            'type_job'          => $this->type_job,
            'client_id'         => $this->client_id,
            'carrierAirline'    => $this->carrierAirline,
            'employee_id'       => $this->jobEmployee,
            'customerCodeJob'     => $this->customerCodeJob,
            'jobBillLadingNo'     => $this->jobBillLadingNo,
            'jobBillLadingDate'   => $this->jobBillLadingDate,
            'houseJobBillLadingNo' => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data'              => $data,
            'created_by'        => Auth::user()->id
        ]);

        jobContainer::create([
            'id_job' =>  $job->id,
            'containers' => $container,
            'created_by'        => Auth::user()->id

        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
        $this->reset();

        session()->flash('message', 'Ocean FCL Export job created successfully.');
    }
    public function render()
    {


        return view('livewire.job.create-job',);
    }
}
