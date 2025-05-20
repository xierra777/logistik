<?php

namespace App\Livewire\Shipment;

use Livewire\Component;
use App\Models\TJob;
use App\Models\Customer;
use App\Models\jobContainer;
use Carbon\Carbon;
use Livewire\Attributes\On;
use App\Models\User;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\DB;

class CreateShipments extends Component
{
      public $step = 1;
    public $type_job = '';
    public $job_name;
    public $job_id = '';

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
    public $jobBillLadingdNo = "", $customerCodeJob,
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
        $estimedelivery, $flightVesselNo, $jobBillLadingdDate, $cross_trade, $hazardousType, $hazardousClassType, $payableAtJob, $freightTypeJob, $remarksJobDetailJobs;
    public $jobEmployee;
    // Bagian Air
    public $jobBillLadingNo, $jobBillLadingDate, $airlinesJob;
    // Container Section
    public $containerType, $noOfPackages, $containerReleaseNo, $containerReleaseDate, $typeOfPackages, $grossWeight, $typeOfGrossWeight, $volumeWeight, $typeOfVolumeWeight, $volume, $chargableWeight, $containerRemarks, $containerNo, $containerSealNo, $noOfPallet, $netOfWeight, $typeNetOfWeight, $totalWeight, $typeOfTotalWeight, $hsCode, $hsCodeDesc;


public function mount()
    {

        $this->clients = Customer::whereJsonContains('roles', 'client')->get();
        $this->dagentsJob = Customer::whereJsonContains('roles', 'agent')->get();
        $this->ogentsJob = Customer::whereJsonContains('roles', 'agent')->get();
        $this->carriers = Customer::whereJsonContains('roles', 'carrier')->get();
        $this->employe = User::all('id', 'name');
    }

    public function previousStep()
    {
        $this->step--;
    }
     public function nextStep()
    {
        $this->validateCurrentStep();
        $this->step++;
    }
     public function getClientNameProperty()
    {
        if (!$this->client_id) return '';

        $client = $this->clients->firstWhere('id', $this->client_id);
        return $client ? $client->name : '';
    }
    // public function updatedTypeJob()
    // {
    //     $this->generateJobName();
    //     // $this->deliveryAgent = null;
    //     // $this->originAgent = null;
    //     // // Reset juga semua input yang gak relevan dengan type_job
    // }

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
                    case 'air_inbound':
                        $rules = [];
                        break;

                    // Tambahkan case untuk tipe job lainnya
                    default:
                        $rules = [];
                        break;
                }

                // $this->validate($rules);
                break;

            case 3:
                break;
        }
    }
    public function render()
    {
        return view('livewire.shipment.create-shipments');
    }
}
