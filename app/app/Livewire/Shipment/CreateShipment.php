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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\customerAddress;
use Livewire\Attributes\On;

class CreateShipment extends Component
{
    public $step = 1;
    public $shipmentType_job = '';
    public $shipment_id = '';


    public $shippers;
    public $consignees;
    public $notifys;
    public $clients;
    public $agentsJob;
    public $carrierModel;
    public $deliveryAgent;
    public $carrierAgent;
    public $employe;
    public $shipmentEmployee_id;
    public $shipmentClient_id, $shipmentShipper_id, $shipmentConsignee_id, $shipmentNotify_id, $shipmentCarrierAgent, $shipmentDeliveryAgent, $shipmentCarrierAirline, $shipmentClient_address, $shipmentClientAddresses = [];


    // Detail Shipment
    public $shipmentCustomerCodeJob, $shipmentBillLadingDate, $shipmentPort_of_loading, $shipmentPort_of_final, $shipmentPlace_of_receipt, $shipmentPort_of_receipt, $shipmentPort_of_discharge, $shipmentPlace_of_delivery, $shipmentOcean_vessel_feeder, $shipmentEstimearrival, $shipmentEstimedelivery, $shipmentPayableAtJob, $shipmentServices_type, $shipmentIncoTerms, $shipmentFreightTypeJob = "Prepaid", $shipmentCross_trade, $shipmentRemarksJobDetailJobs, $shipmentHouseBillLadingNo;

    // Container Detail
    public $shipmentFlightVesselName, $shipmentFlightVesselNo, $shipmentNoOfPackages, $shipmentContainerDeliveryAgent, $shipmentGrossWeight, $shipmentVolumeWeight, $shipmentVolume, $shipmentChargableWeight, $ShipmentHsCode, $shipmentContainerRemarks, $shipmentHsCodeDesc, $shipmentTypeOfVolumeWeight, $shipmentTypeOfGrossWeight, $shipmentTypeOfPackages, $typeOfShipmentVolume, $shipmentHsCode;

    // Container Details select
    public function mount()
    {

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
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $countThisMonth = TShipments::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
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
            'shipmentIncoTerms'            => $this->shipmentIncoTerms,
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

        return redirect()->route('listShipment')->with('success', [
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
            'shipmentIncoTerms'            => $this->shipmentIncoTerms,
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

        return redirect()->route('listShipment')->with('success', [
            'icon' => 'success',
            'title' => 'Success!',

        ]);
    }
    public function ocean_lcl_export()
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
            'shipmentIncoTerms'            => $this->shipmentIncoTerms,
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

        return redirect()->route('listShipment')->with('success', [
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
            'shipmentIncoTerms'            => $this->shipmentIncoTerms,
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

        return redirect()->route('listShipment')->with('success', [
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
            'shipmentIncoTerms'            => $this->shipmentIncoTerms,
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

        return redirect()->route('listShipment')->with('success', [
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
            'shipmentIncoTerms'            => $this->shipmentIncoTerms,
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

        return redirect()->route('listShipment')->with('success', [
            'icon' => 'success',
            'title' => 'Success!',

        ]);
    }
    public function render()
    {
        $carriers = [];
        $airlines = [];
        $agents = [];

        if (in_array($this->shipmentType_job, ['air_inbound', 'air_outbound'])) {
            $airlines = Customer::whereJsonContains('roles', 'airline')->get();
        } else {
            $carriers = Customer::whereJsonContains('roles', 'carrier')->get();
            $agents = Customer::whereJsonContains('roles', 'agent')->get();
        }

        return view('livewire.shipment.create-shipment', [
            'carriers' => $carriers,
            'airlines' => $airlines,
            'agents'   => $agents,
        ]);
    }
}
