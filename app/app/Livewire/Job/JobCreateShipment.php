<?php

namespace App\Livewire\Job;

use Livewire\Component;
use App\Models\TJob;
use App\Models\Customer;
use App\Models\jobContainer;
use App\Models\shipmentContainers;
use App\Models\TShipments;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\customerAddress;
use Livewire\Attributes\On;

class JobCreateShipment extends Component
{
    public $step = 1;
    public $shipmentType_job = '';
    public $shipment_id = '';
    public $shipmentHouseBillLadingNo;


    public $shippers;
    public $consignees;
    public $notifys;
    public $clients;
    public $agentsJob;
    public $carrierModel;
    public $deliveryAgent;
    public $carrierAgent;
    public $containerShipmentCarrierAirline;
    public $employe;
    public $shipmentEmployee_id;
    public $shipmentClient_id, $shipmentShipper_id, $shipmentConsignee_id, $shipmentNotify_id, $shipmentCarrierAgent, $shipmentDeliveryAgent, $shipmentCarrierAirline, $shipmentClient_address, $shipmentClientAddresses = [];


    // Detail Shipment
    public $shipmentCustomerCodeJob, $shipmentBillLadingDate, $shipmentPort_of_loading, $shipmentPort_of_final, $shipmentPlace_of_receipt, $shipmentPort_of_receipt, $shipmentPort_of_discharge, $shipmentPlace_of_delivery, $shipmentOcean_vessel_feeder, $shipmentEstimearrival, $shipmentEstimedelivery, $shipmentPayableAtJob, $shipmentServices_type, $shipmentCross_trade, $shipmentFreightTypeJob = "Prepaid", $shipmentRemarksJobDetailJobs;

    // Container Detail
    public $shipmentFlightVesselName, $shipmentFlightVesselNo, $shipmentNoOfPackages, $shipmentContainerDeliveryAgent, $shipmentGrossWeight, $shipmentVolumeWeight, $shipmentVolume, $shipmentChargableWeight, $ShipmentHsCode, $shipmentContainerRemarks, $shipmentHsCodeDesc, $shipmentTypeOfVolumeWeight, $shipmentTypeOfGrossWeight, $shipmentTypeOfPackages, $typeOfShipmentVolume, $shipmentHsCode;
    public $job, $id_job;

    // Added missing properties to fix undefined property errors
    public $carrierAirline;
    public $jobEmployee;
    public $customerCodeJob;
    public $jobBillLadingNo;
    public $jobBillLadingDate;
    public $houseJobBillLadingNo;
    public $houseJobBillLadingDate;
    public $employe_id;

    // Container Details select
    public function mount($id)
    {
        $this->job = TJob::with('TjobContainer')->findOrFail($id);
        // dd($this->job->dagentsJob);
        $this->id_job = $this->job->id;
        $this->shipmentClient_id = $this->job->client_id;
        $this->shipmentShipper_id = $this->job->client_id;
        $this->updatedShipmentClientId($this->shipmentClient_id); // Tambahkan ini!
        $this->shipmentDeliveryAgent = $this->job->dagentsJob;
        $this->shipmentContainerDeliveryAgent = $this->job->dagentsJob;
        $this->carrierAirline = $this->job->carrierAirline;
        $this->jobEmployee = $this->job->employee_id;
        $this->shipmentType_job = $this->job->type_job;
        $this->updatedShipmentTypejob($this->shipmentType_job);
        $this->shipmentCustomerCodeJob = $this->job->customerCodeJob;
        $this->shipmentBillLadingDate = $this->job->jobBillLadingDate;
        $this->shipmentCarrierAirline = $this->job->carrierAirline;
        $this->shipmentFreightTypeJob = $this->job->data['freightTypeJob'];
        $this->shipmentCross_trade      = $this->job->data['incoTerms'];
        $this->shipmentServices_type = $this->job->data['servicesType'];
        $this->shipmentEstimearrival = $this->job->data['estimearrival'];
        $this->shipmentEstimedelivery = $this->job->data['estimedelivery'];
        $this->shipmentPort_of_loading = $this->job->data['port_of_loading'];
        $this->shipmentPort_of_final = $this->job->data['port_of_final'];
        $this->shipmentPort_of_receipt = $this->job->data['port_of_receipt'];
        $this->shipmentPlace_of_delivery = $this->job->data['place_of_delivery'];
        $this->shipmentPort_of_discharge = $this->job->data['port_of_discharge'];
        $this->shipmentPlace_of_receipt = $this->job->data['place_of_receipt'];
        $this->shipmentPayableAtJob = $this->job->data['payableAtJob'];
        $this->shipmentEmployee_id = $this->job->employee_id;
        $this->shipmentRemarksJobDetailJobs = $this->job->data['remarksJobDetailJobs'];
        $this->clients = Customer::whereJsonContains('roles', 'client')->get();
        $this->shippers = Customer::whereJsonContains('roles', 'shipper')->get();
        $this->consignees = Customer::whereJsonContains('roles', 'consignee')->get();
        $this->notifys = Customer::whereJsonContains('roles', 'notify')->get();
        $this->agentsJob = Customer::whereJsonContains('roles', 'agent')->get();
        $this->deliveryAgent = Customer::whereJsonContains('roles', 'delivery_agent')->get();
        $this->carrierAgent = Customer::whereJsonContains('roles', 'carrier_agent')->get();
        $this->carrierModel = Customer::whereJsonContains('roles', 'carrier')->get();
        $this->employe = User::all('id', 'name');
    }
    #[On('port-updated')]
    public function updatePort($model, $value)
    {
        $this->$model = $value;
    }

    public function updatedShipmentClientId($value)
    {
        $addresses = customerAddress::where('customer_id', $value)->get();
        $this->shipmentClientAddresses = $addresses;

        if (!empty($addresses)) {
            $this->shipmentClient_address = $addresses->first()->address ?? null;
        } else {
            $this->shipmentClient_address = null;
        }
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
        if (!$this->shipmentClient_id) return '';

        $client = $this->clients->firstWhere('id', $this->shipmentClient_id);
        return $client;
    }
    public function getShipperNameProperty()
    {
        if (!$this->shipmentShipper_id) return '';

        $shipper = $this->shippers->firstWhere('id', $this->shipmentShipper_id);
        return $shipper;
    }
    public function getConsigneeNameProperty()
    {
        if (!$this->shipmentConsignee_id) return '';

        $consignee = $this->consignees->firstWhere('id', $this->shipmentConsignee_id);
        return $consignee;
    }
    public function getNotifyNameProperty()
    {
        if (!$this->shipmentNotify_id) return '';

        $notify = $this->notifys->firstWhere('id', $this->shipmentNotify_id);
        return $notify;
    }
    public function getCarrierNameProperty()
    {
        if (!$this->shipmentCarrierAirline) return '';
        $carrier = $this->carrierModel->firstWhere('id', $this->shipmentCarrierAirline);
        return $carrier;
    }
    public function getContainerCarrierNameProperty()
    {
        if (!$this->containerShipmentCarrierAirline) return '';
        $containerCarrier = $this->carrierModel->firstWhere('id', $this->containerShipmentCarrierAirline);
        return $containerCarrier;
    }
    public function getCarrierAgentNameProperty()
    {
        if (!$this->shipmentCarrierAgent) return '';

        $carrierAgent = $this->carrierAgent->firstWhere('id', $this->shipmentCarrierAgent);
        return $carrierAgent;
    }
    public function getDeliveryAgentNameProperty()
    {
        if (!$this->shipmentDeliveryAgent) return '';

        $delivery = $this->deliveryAgent->firstWhere('id', $this->shipmentDeliveryAgent);
        return $delivery;
    }
    public function updatedShipmentTypejob()
    {
        $this->generateShipmentName();
        // $this->deliveryAgent = null;
        // $this->originAgent = null;
    }


    public function generateShipmentName()
    {
        $ctry = 'ID';
        $date = now()->format('ym');
        // Tentukan suffix berdasarkan type job
        $type = $this->shipmentType_job;
        $map = [
            'ocean_fcl_export'  => 'FE',
            'ocean_fcl_import'  => 'FI',
            'trucking'          => 'TRC',
            'logistics'         => 'LGS',
            'air_inbound'       => 'AI',
            'air_outbound'      => 'AE',
        ];
        $suffix = $map[$type] ?? 'BRNJKT';

        // Prefix shipment ID
        $prefix = "{$ctry}BRN{$suffix}{$date}";

        // Cari shipment terakhir bulan ini dengan prefix sama
        $last = TShipments::where('shipment_id', 'like', "$prefix%")
            ->orderByDesc('shipment_id')
            ->first();

        // Ambil urutan terakhir dari 3 digit di akhir shipment_id
        $lastSequence = $last ? (int) substr($last->shipment_id, -3) : 0;
        $nextSequence = str_pad($lastSequence + 1, 3, '0', STR_PAD_LEFT);

        // Set shipment_id
        $this->shipment_id = "{$prefix}{$nextSequence}";
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
                            'shipmentPort_of_receipt'      =>'required',
                            'shipmentPort_of_discharge'    => 'required',
                            'shipmentPlace_of_delivery'    => 'required',
                            ''

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
        ];
        $payload = [
            'shipmentFlightVesselName'     => $this->shipmentFlightVesselName,
            'shipmentFlightVesselNo'       => $this->shipmentFlightVesselNo,
            'shipmentCustomerCodeJob'      => $this->shipmentCustomerCodeJob,
            'shipmentBillLadingDate'       => $this->shipmentBillLadingDate,
            'shipmentHouseBillLadingNo'    => $this->shipmentHouseBillLadingNo,
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
            'shipmentCross_trade'          => $this->shipmentCross_trade,
            'shipmentFreightTypeJob'       => $this->shipmentFreightTypeJob,
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
            'id_job'                        => $this->id_job,
            'shipmentsTypeJob'              => $this->shipmentType_job,
            'shipment_id'                   => $this->shipment_id,
            'shipmentClient_id'             => $this->shipmentClient_id,
            'shipmentClient_address'        => $this->shipmentClient_address,
            'shipmentShipper_id'            => $this->shipmentShipper_id,
            'shipmentConsignee_id'          => $this->shipmentConsignee_id,
            'shipmentNotify_id'             => $this->shipmentNotify_id,
            'shipmentCarrierAirline'        => $this->shipmentCarrierAirline,
            'shipmentContainerDeliveryAgent'=> $this->shipmentContainerDeliveryAgent,
            'shipmentCarrierAgent'          => $this->shipmentCarrierAgent,
            'shipmentDeliveryAgent'         => $this->shipmentDeliveryAgent,
            'employee_id'                   => $this->shipmentEmployee_id,
            'dataShipments'                 => $payload,
            'created_by'                    => Auth::user()->id

        ]);

        shipmentContainers::create([
            'id_shipments' => $shipment->id,
            'containersData' => $container,
            'created_by'        => Auth::user()->id

        ]);

       return redirect()->route('viewJob', ['id' => $this->job->id])->with('success', [
    'icon' => 'success',
    'title' => 'Success!',
]);

    }
    public function ocean_fcl_import()
    {
        $container = [
            'shipmentNoOfPackages'         => $this->shipmentNoOfPackages,
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
        ];
        $payload = [
            'shipmentFlightVesselName'     => $this->shipmentFlightVesselName,
            'shipmentFlightVesselNo'       => $this->shipmentFlightVesselNo,
            'shipmentCustomerCodeJob'      => $this->shipmentCustomerCodeJob,
            'shipmentBillLadingDate'       => $this->shipmentBillLadingDate,
            'shipmentHouseBillLadingNo'    => $this->shipmentHouseBillLadingNo,
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
            'shipmentCross_trade'            => $this->shipmentCross_trade,
            'shipmentFreightTypeJob'       => $this->shipmentFreightTypeJob,
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
            'id_job'                    => $this->id_job,
            'shipmentsTypeJob'          => $this->shipmentType_job,
            'shipment_id'              => $this->shipment_id,
            'shipmentClient_id'        => $this->shipmentClient_id,
            'shipmentClient_address'    => $this->shipmentClient_address,
            'shipmentShipper_id'     => $this->shipmentShipper_id,
            'shipmentConsignee_id'    => $this->shipmentConsignee_id,
            'shipmentNotify_id'         => $this->shipmentNotify_id,
            'shipmentCarrierAirline'      => $this->shipmentCarrierAirline,
            'shipmentContainerDeliveryAgent' => $this->shipmentContainerDeliveryAgent,
            'shipmentCarrierAgent'      => $this->shipmentCarrierAgent,
            'shipmentDeliveryAgent'     => $this->shipmentDeliveryAgent,
            'employee_id' => $this->shipmentEmployee_id,
            'dataShipments'              => $payload,
            'created_by'        => Auth::user()->id

        ]);


        shipmentContainers::create([
            'id_shipments' => $shipment->id,
            'containersData' => $container,
            'created_by'        => Auth::user()->id

        ]);

       return redirect()->route('viewJob', ['id' => $this->job->id])->with('success', [
    'icon' => 'success',
    'title' => 'Success!',
]);

    }
    public function ocean_lcl_export()
    {
        // dd($this->shipmentPlace_of_delivery)
        $container = [
            'shipmentNoOfPackages'         => $this->shipmentNoOfPackages,
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
        ];
        $payload = [
            'shipmentFlightVesselName'     => $this->shipmentFlightVesselName,
            'shipmentFlightVesselNo'       => $this->shipmentFlightVesselNo,
            'shipmentCustomerCodeJob'      => $this->shipmentCustomerCodeJob,
            'shipmentBillLadingDate'       => $this->shipmentBillLadingDate,
            'shipmentHouseBillLadingNo'    => $this->shipmentHouseBillLadingNo,
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
            'shipmentCross_trade'            => $this->shipmentCross_trade,
            'shipmentFreightTypeJob'       => $this->shipmentFreightTypeJob,
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
            'id_job'                    => $this->id_job,
            'shipmentsTypeJob'          => $this->shipmentType_job,
            'shipment_id'              => $this->shipment_id,
            'shipmentClient_id'        => $this->shipmentClient_id,
            'shipmentClient_address'    => $this->shipmentClient_address,
            'shipmentShipper_id'     => $this->shipmentShipper_id,
            'shipmentConsignee_id'    => $this->shipmentConsignee_id,
            'shipmentNotify_id'         => $this->shipmentNotify_id,
            'shipmentCarrierAirline'      => $this->shipmentCarrierAirline,
            'shipmentContainerDeliveryAgent' => $this->shipmentContainerDeliveryAgent,
            'shipmentCarrierAgent'      => $this->shipmentCarrierAgent,
            'shipmentDeliveryAgent'     => $this->shipmentDeliveryAgent,
            'employee_id' => $this->shipmentEmployee_id,
            'dataShipments'              => $payload,
            'created_by'        => Auth::user()->id

        ]);


        shipmentContainers::create([
            'id_shipments' => $shipment->id,
            'containersData' => $container,
            'created_by'        => Auth::user()->id

        ]);

       return redirect()->route('viewJob', ['id' => $this->job->id])->with('success', [
    'icon' => 'success',
    'title' => 'Success!',
]);

    }
    public function ocean_lcl_import()
    {
        $container = [
            'shipmentNoOfPackages'         => $this->shipmentNoOfPackages,
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
        ];
        $payload = [
            'shipmentFlightVesselName'     => $this->shipmentFlightVesselName,
            'shipmentFlightVesselNo'       => $this->shipmentFlightVesselNo,
            'shipmentCustomerCodeJob'      => $this->shipmentCustomerCodeJob,
            'shipmentBillLadingDate'       => $this->shipmentBillLadingDate,
            'shipmentHouseBillLadingNo'    => $this->shipmentHouseBillLadingNo,
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
            'shipmentCross_trade'            => $this->shipmentCross_trade,
            'shipmentFreightTypeJob'       => $this->shipmentFreightTypeJob,
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
            'id_job'                    => $this->id_job,
            'shipmentsTypeJob'          => $this->shipmentType_job,
            'shipment_id'              => $this->shipment_id,
            'shipmentClient_id'        => $this->shipmentClient_id,
            'shipmentClient_address'    => $this->shipmentClient_address,
            'shipmentShipper_id'     => $this->shipmentShipper_id,
            'shipmentConsignee_id'    => $this->shipmentConsignee_id,
            'shipmentNotify_id'         => $this->shipmentNotify_id,
            'shipmentCarrierAirline'      => $this->shipmentCarrierAirline,
            'shipmentContainerDeliveryAgent' => $this->shipmentContainerDeliveryAgent,
            'shipmentCarrierAgent'      => $this->shipmentCarrierAgent,
            'shipmentDeliveryAgent'     => $this->shipmentDeliveryAgent,
            'employee_id' => $this->shipmentEmployee_id,
            'dataShipments'              => $payload,
            'created_by'        => Auth::user()->id

        ]);


        shipmentContainers::create([
            'id_shipments' => $shipment->id,
            'containersData' => $container,
            'created_by'        => Auth::user()->id

        ]);

       return redirect()->route('viewJob', ['id' => $this->job->id])->with('success', [
    'icon' => 'success',
    'title' => 'Success!',
]);

    }
    public function air_inbound()
    {
        $container = [
            'shipmentNoOfPackages'         => $this->shipmentNoOfPackages,
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
        ];
        $payload = [
            'shipmentFlightVesselName'     => $this->shipmentFlightVesselName,
            'shipmentFlightVesselNo'       => $this->shipmentFlightVesselNo,
            'shipmentCustomerCodeJob'      => $this->shipmentCustomerCodeJob,
            'shipmentBillLadingDate'       => $this->shipmentBillLadingDate,
            'shipmentHouseBillLadingNo'    => $this->shipmentHouseBillLadingNo,
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
            'shipmentCross_trade'            => $this->shipmentCross_trade,
            'shipmentFreightTypeJob'       => $this->shipmentFreightTypeJob,
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
            'id_job'                    => $this->id_job,
            'shipmentsTypeJob'          => $this->shipmentType_job,
            'shipment_id'              => $this->shipment_id,
            'shipmentClient_id'        => $this->shipmentClient_id,
            'shipmentClient_address'    => $this->shipmentClient_address,
            'shipmentShipper_id'     => $this->shipmentShipper_id,
            'shipmentConsignee_id'    => $this->shipmentConsignee_id,
            'shipmentNotify_id'         => $this->shipmentNotify_id,
            'shipmentCarrierAirline'      => $this->shipmentCarrierAirline,
            'shipmentContainerDeliveryAgent' => $this->shipmentContainerDeliveryAgent,
            'shipmentCarrierAgent'      => $this->shipmentCarrierAgent,
            'shipmentDeliveryAgent'     => $this->shipmentDeliveryAgent,
            'employee_id' => $this->shipmentEmployee_id,
            'dataShipments'              => $payload,
            'created_by'        => Auth::user()->id

        ]);


        shipmentContainers::create([
            'id_shipments' => $shipment->id,
            'containersData' => $container,
            'created_by'        => Auth::user()->id

        ]);

       return redirect()->route('viewJob', ['id' => $this->job->id])->with('success', [
    'icon' => 'success',
    'title' => 'Success!',
]);

    }
    public function air_outbound()
    {
        $container = [
            'shipmentNoOfPackages'         => $this->shipmentNoOfPackages,
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
        ];
        $payload = [
            'shipmentFlightVesselName'     => $this->shipmentFlightVesselName,
            'shipmentFlightVesselNo'       => $this->shipmentFlightVesselNo,
            'shipmentCustomerCodeJob'      => $this->shipmentCustomerCodeJob,
            'shipmentBillLadingDate'       => $this->shipmentBillLadingDate,
            'shipmentHouseBillLadingNo'    => $this->shipmentHouseBillLadingNo,
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
            'shipmentCross_trade'            => $this->shipmentCross_trade,
            'shipmentFreightTypeJob'       => $this->shipmentFreightTypeJob,
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
            'id_job'                    => $this->id_job,
            'shipmentsTypeJob'          => $this->shipmentType_job,
            'shipment_id'              => $this->shipment_id,
            'shipmentClient_id'        => $this->shipmentClient_id,
            'shipmentClient_address'    => $this->shipmentClient_address,
            'shipmentShipper_id'     => $this->shipmentShipper_id,
            'shipmentConsignee_id'    => $this->shipmentConsignee_id,
            'shipmentNotify_id'         => $this->shipmentNotify_id,
            'shipmentCarrierAirline'      => $this->shipmentCarrierAirline,
            'shipmentContainerDeliveryAgent' => $this->shipmentContainerDeliveryAgent,
            'shipmentCarrierAgent'      => $this->shipmentCarrierAgent,
            'shipmentDeliveryAgent'     => $this->shipmentDeliveryAgent,
            'employee_id'           => $this->shipmentEmployee_id,
            'dataShipments'              => $payload,
            'created_by'        => Auth::user()->id

        ]);


        shipmentContainers::create([
            'id_shipments' => $shipment->id,
            'containersData' => $container,
            'created_by'        => Auth::user()->id

        ]);

       return redirect()->route('viewJob', ['id' => $this->job->id])->with('success', [
    'icon' => 'success',
    'title' => 'Success!',
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
        return view('livewire.job.job-create-shipment', [
            'carriers' => $carriers,
            'airlines' => $airlines,
        ]);
    }
}
