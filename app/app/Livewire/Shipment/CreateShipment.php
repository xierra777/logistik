<?php

namespace App\Livewire\Shipment;

use Livewire\Component;
use App\Models\TJob;
use App\Models\Customer;
use App\Models\jobContainer;
use App\Models\shipmentContainers;
use App\Models\TShipments;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\customerAddress;

class CreateShipment extends Component
{
    public $step = 1;
    public $shipmentType_job = '';
    public $shipment_id = '';


    public $shippers;
    public $consignees;
    public $notifys;
    public $clients;
    public $dagentsJob;
    public $carriers;
    public $employe;
    public $shipmentEmployee_id;
    public $shipmentClient_id, $shipmentShipper_id, $shipmentConsignee_id, $shipmentNotify_id, $shipmentCarrierAgent, $shipmentDeliveryAgent, $shipmentCarrierAirline, $shipmentClient_address = [];


    // Detail Shipment
    public $shipmentCustomerCodeJob, $shipmentBillLadingDate, $shipmentPort_of_loading, $shipmentPort_of_final, $shipmentPlace_of_receipt, $shipmentPort_of_receipt, $shipmentPort_of_discharge, $shipmentPlace_of_delivery, $shipmentOcean_vessel_feeder, $shipmentEstimearrival, $shipmentEstimedelivery, $shipmentPayableAtJob, $shipmentServices_type, $shipmentIncoTerms, $shipmentFreightTypeJob = "Prepaid", $shipmentCross_trade, $shipmentRemarksJobDetailJobs;

    // Container Detail
    public $shipmentFlightVesselName, $shipmentFlightVesselNo, $shipmentNoOfPackages, $shipmentContainerDeliveryAgent, $shipmentGrossWeight, $shipmentVolumeWeight, $shipmentVolume, $shipmentChargableWeight, $ShipmentHsCode, $shipmentContainerRemarks, $shipmentHsCodeDesc, $shipmentTypeOfVolumeWeight, $shipmentTypeOfGrossWeight, $shipmentTypeOfPackages, $typeOfShipmentVolume, $shipmentHsCode;

    // Container Details select
    public function mount()
    {

        $this->clients = Customer::whereJsonContains('roles', 'client')->get();
        $this->shippers = Customer::whereJsonContains('roles', 'shipper')->get();
        $this->consignees = Customer::whereJsonContains('roles', 'consignee')->get();
        $this->notifys = Customer::whereJsonContains('roles', 'notify')->get();
        $this->dagentsJob = Customer::whereJsonContains('roles', 'agent')->get();
        $this->carriers = Customer::whereJsonContains('roles', 'carrier')->get();
        $this->employe = User::all('id', 'name');
    }

    public function updatedShipmentClientId()
    {
        $this->shipmentClient_address = customerAddress::where('customer_id', $this->shipmentClient_id)->get();
    }


    public function previousStep()
    {
        $this->step--;
    }
    public function nextStep()
    {
        // dd($this->shipmentType_job);

        $this->validateCurrentStep();
        $this->step++;
    }
    public function getClientNameProperty()
    {
        if (!$this->shipmentClient_id) return '';

        $client = $this->clients->firstWhere('id', $this->shipmentClient_id);
        return $client;
    }
    public function updatedShipmentTypejob()
    {
        $this->generateShipmentName();
        // $this->deliveryAgent = null;
        // $this->originAgent = null;
    }
    public function generateShipmentName()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $countThisMonth = TJob::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        $sequence = str_pad($countThisMonth + 1, 3, '0', STR_PAD_LEFT);
        $ctry = 'ID';
        $date = now()->format('ym');

        switch ($this->shipmentType_job) {
            case 'ocean_fcl_export':
                $suffix = 'FE';
                break;
            case 'ocean_fcl_import':
                $suffix = 'FI';
                break;
            case 'trucking':
                $suffix = 'TRC';
                break;
            case 'logistics':
                $suffix = 'LGS';
                break;
            case 'air_inbound':
                $suffix = 'AI';
                break;
            case 'air_outbound':
                $suffix = 'AE';
                break;
            default:
                $suffix = 'BRNJKT';
                break;
        }

        $prefix = "BRN{$suffix}";
        $this->shipment_id = "{$ctry}{$prefix}{$date}{$sequence}";
    }

    private function validateCurrentStep()
    {
        switch ($this->step) {
            case 1:
                $this->validate([
                    'shipmentType_job' => 'required',
                ]);
                break;

            case 2:
                $rules = [];

                switch ($this->shipmentType_job) {
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
    public function submitForm()
    {
        switch ($this->shipmentType_job) {
            case 'ocean_fcl_export':
                $this->ocean_fcl_export();
                break;
            case 'ocean_fcl_import':
                $this->ocean_fcl_import();
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
            default:
                session()->flash('error', 'Job type not recognized.');
        }
    }
    public function ocean_fcl_export()
    {
        $container = [
            'shipmentNoOfPackages'         => $this->shipmentNoOfPackages,
            'shipmentContainerDeliveryAgent' => $this->shipmentContainerDeliveryAgent,
            'shipmentGrossWeight'          => $this->shipmentGrossWeight,
            'shipmentVolumeWeight'         => $this->shipmentVolumeWeight,
            'shipmentVolume'               => $this->shipmentVolume,
            'shipmentChargableWeight'      => $this->shipmentChargableWeight,
            'shipmentHsCode'               => $this->shipmentHsCode,
            'shipmentHsCodeDesc'           => $this->shipmentHsCodeDesc,
            'shipmentContainerRemarks'     => $this->shipmentContainerRemarks,
            'shipmentTypeOfVolumeWeight'   => $this->shipmentTypeOfVolumeWeight,
            'shipmentTypeOfGrossWeight'    => $this->shipmentTypeOfGrossWeight,
            'shipmentTypeOfPackages'       => $this->shipmentTypeOfPackages,
            'typeOfShipmentVolume'         => $this->typeOfShipmentVolume,
            'containerShipmentCarrierAirline' => $this->containerShipmentCarrierAirline,
        ];
        $payload = [
            'shipmentFlightVesselName'     => $this->shipmentFlightVesselName,
            'shipmentFlightVesselNo'       => $this->shipmentFlightVesselNo,
            'shipmentCustomerCodeJob'      => $this->shipmentCustomerCodeJob,
            'shipmentBillLadingDate'       => $this->shipmentBillLadingDate,
            'shipmentPort_of_loading'      => $this->shipmentPort_of_loading,
            'shipmentPort_of_final'        => $this->shipmentPort_of_final,
            'shipmentPlace_of_receipt'     => $this->shipmentPlace_of_receipt,
            'shipmentPort_of_receipt'      => $this->shipmentPort_of_receipt,
            'shipmentPort_of_discharge'    => $this->shipmentPort_of_discharge,
            'shipmentPlace_of_delivery'    => $this->shipmentPlace_of_delivery,
            'shipmentOcean_vessel_feeder'  => $this->shipmentOcean_vessel_feeder,
            'shipmentEstimearrival'        => $this->shipmentEstimearrival,
            'shipmentEstimedelivery'       => $this->shipmentEstimedelivery,
            'shipmentPayableAtJob'         => $this->shipmentPayableAtJob,
            'shipmentServices_type'        => $this->shipmentServices_type,
            'shipmentIncoTerms'            => $this->shipmentIncoTerms,
            'shipmentFreightTypeJob'       => $this->shipmentFreightTypeJob,
            'shipmentCross_trade'          => $this->shipmentCross_trade,
            'shipmentRemarksJobDetailJobs' => $this->shipmentRemarksJobDetailJobs,
        ];

        // dd([
        //     'shipmentClient_id' => $this->shipmentClient_id,
        //     'shipmentType_job' => $this->shipmentType_job,
        //     'shipment_id' => $this->shipment_id,
        //     'shipmentClient_address' => $this->shipmentClient_address,
        //     'shipmentShipper_id' => $this->shipmentShipper_id,
        //     'shipmentConsignee_id' => $this->shipmentConsignee_id,
        //     'shipmentNotify_id' => $this->shipmentNotify_id,
        //     'data' => $payload,
        //     'container' => $container
        // ]);


        $shipment = TShipments::create([
            'shipmentsTypeJob'          => $this->shipmentType_job,
            'shipment_id'              => $this->shipment_id,
            'shipmentClient_id'        => $this->shipmentClient_id,
            'shipmentShipper_id'     => $this->shipmentShipper_id,
            'shipmentConsignee_id'    => $this->shipmentConsignee_id,
            'shipmentNotify_id'         => $this->shipmentNotify_id,
            'shipmentClient_address'    => $this->shipmentClient_address,
            'shipmentCarrierAirline'      => $this->shipmentCarrierAirline,
            'employee_id        ' => $this->shipmentEmployee_id,
            'dataShipments'              => $payload,
        ]);

        shipmentContainers::create([
            'id_shipments' => $shipment->id,
            'containersData' => $container,
        ]);

        return redirect()->route('listJob')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
    }
    public function render()
    {
        $carriers = [];
        $airlines = [];

        if (in_array($this->shipmentType_job, ['air_inbound', 'air_outbound'])) {
            $airlines = Customer::whereJsonContains('roles', 'airline')->get();
        } else {
            $carriers = Customer::whereJsonContains('roles', 'carrier')->get();
        }

        return view('livewire.shipment.create-shipment', [
            'carriers' => $carriers,
            'airlines' => $airlines,
        ]);
    }
}
