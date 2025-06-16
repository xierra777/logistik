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


class EditJob extends Component
{
    public $job; // Add this line to declare the $job property
    public $step = 1;
    public $type_job = '';
    public $job_name;
    public $job_id = '';

    public $clients;
    public $dagentsJob;
    public $ogentsJob;
    public $carriers;
    public $employe;
    public $employe_id;
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


    public function nextStep()
    {
        $this->step++;
    }

    public function getClientNameProperty()
    {
        if (!$this->client_id) return '';

        $client = $this->clients->firstWhere('id', $this->client_id);
        return $client ? $client->name : '';
    }
    public function getCarrierAirlineNameProperty()
    {
        if (!$this->carrierAirline) return '';
        $carrier = $this->carriers->firstWhere('id', $this->carrierAirline);
        return $carrier ? $carrier->name : '';
    }
    public function getDagentNameProperty()
    {
        if (!$this->deliveryAgent) return '';

        $agent = $this->dagentsJob->firstWhere('id', $this->deliveryAgent);
        return $agent?->name ?? '';
    }


    public function previousStep()
    {
        $this->step--;
    }
    public function mount($id)
    {
        $this->job = TJob::with('TjobContainer')->findOrFail($id);
        // Load basic job data
        $this->job_id = $this->job->job_id;
        $this->type_job = $this->job->type_job;
        $this->client_id = $this->job->client_id;
        $this->deliveryAgent = $this->job->dagentsJob;
        $this->carrierAirline = $this->job->carrierAirline;
        $this->jobEmployee = $this->job->employee_id;
        $this->customerCodeJob = $this->job->customerCodeJob;
        $this->jobBillLadingNo = $this->job->jobBillLadingNo;
        $this->jobBillLadingDate = $this->job->jobBillLadingDate;
        $this->houseJobBillLadingNo = $this->job->houseJobBillLadingNo;
        $this->houseJobBillLadingDate = $this->job->houseJobBillLadingDate;
        $this->employe_id = $this->job->employee_id;

        // Load data object
        if ($this->job->data) {
            foreach ($this->job->data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        // Load lookup data
        $this->clients = Customer::whereJsonContains('roles', 'client')->get();
        $this->dagentsJob = Customer::whereJsonContains('roles', 'agent')->get();
        $this->ogentsJob = Customer::whereJsonContains('roles', 'agent')->get();
        $this->carriers = Customer::whereJsonContains('roles', 'carrier')->get();
        $this->employe = User::all('id', 'name');
    }

    public function submitForm()
    {

        $data = [
            'servicesType' => $this->servicesType,
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

        $this->job->update([
            'type_job' => $this->type_job,
            'client_id' => $this->client_id,
            'dagentsJob' => $this->deliveryAgent,
            'carrierAirline' => $this->carrierAirline,
            'employee_id' => $this->jobEmployee,
            'customerCodeJob' => $this->customerCodeJob,
            'jobBillLadingNo' => $this->jobBillLadingNo,
            'jobBillLadingDate' => $this->jobBillLadingDate,
            'houseJobBillLadingNo' => $this->houseJobBillLadingNo,
            'houseJobBillLadingDate' => $this->houseJobBillLadingDate,
            'data' => $data,
            'updated_by' => Auth::user()->id
        ]);
        return redirect()->route('listJob')->with('success', [
            'icon' => 'success',
            'title' => 'Success updating data !',
            'backgroundColor' => '#FFFF00',
            'iconColor' => '#808080',
            'titleColor' => '#808080',
        ]);
    }

    public function render()
    {
        $carriers = [];
        $airlines = [];

        if (in_array($this->type_job, ['air_inbound', 'air_outbound'])) {
            $airlines = Customer::whereJsonContains('roles', 'airline')->get();
        } else {
            $carriers = Customer::whereJsonContains('roles', 'carrier')->get();
        }

        return view('livewire.job.edit-job', [
            'carriers' => $carriers,
            'airlines' => $airlines,
        ]);
    }
}
