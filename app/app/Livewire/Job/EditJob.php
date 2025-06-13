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

    public function previousStep()
    {
        $this->step--;
    }
    public function mount($id)
    {
        $job = TJob::with('TjobContainer')->findOrFail($id);

        // Load basic job data
        $this->job_id = $job->job_id;
        $this->type_job = $job->type_job;
        $this->client_id = $job->client_id;
        $this->deliveryAgent = $job->dagentsJob;
        $this->carrierAirline = $job->carrierAirline;
        $this->jobEmployee = $job->employee_id;
        $this->customerCodeJob = $job->customerCodeJob;
        $this->jobBillLadingNo = $job->jobBillLadingNo;
        $this->jobBillLadingDate = $job->jobBillLadingDate;
        $this->houseJobBillLadingNo = $job->houseJobBillLadingNo;
        $this->houseJobBillLadingDate = $job->houseJobBillLadingDate;

        // Load data object
        if ($job->data) {
            foreach ($job->data as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }

        // Load container data
        if ($job->jobContainer && $job->jobContainer->containers) {
            foreach ($job->jobContainer->containers as $key => $value) {
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
        DB::beginTransaction();
        try {
            $job = TJob::findOrFail($this->job_id);

            $container = [
                'containerType' => $this->containerType,
                // ... rest of container data
            ];

            $data = [
                'servicesType' => $this->servicesType,
                // ... rest of job data
            ];

            $job->update([
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

            $job->jobContainer()->update([
                'containers' => $container,
                'updated_by' => Auth::user()->id
            ]);

            DB::commit();
            return redirect()->route('listJob')->with('success', [
                'icon' => 'success',
                'title' => 'Updated Successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            session()->flash('error', 'Error updating job: ' . $e->getMessage());
        }
    }

    // Keep other existing methods...
}
