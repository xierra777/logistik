<?php

namespace App\Livewire\Shipment;

use Livewire\Component;
use App\Models\TShipments;
use App\Models\Customer;
use App\Models\shipmentContainers;
use App\Models\customerAddress;
use App\Models\User;
use Carbon\Carbon;

class EditShipment extends Component
{
    public $step = 1;

    public $shipmentId;
    public $shipment;
    public $shipmentClientAddresses = [];

    // Semua properti seperti di CreateShipment
    public $shipmentType_job, $shipment_id, $shipmentClient_id, $shipmentClient_address;
    public $shipmentShipper_id, $shipmentConsignee_id, $shipmentNotify_id;
    public $shipmentCarrierAirline, $shipmentCarrierAgent, $shipmentDeliveryAgent;
    public $shipmentContainerDeliveryAgent, $containerShipmentCarrierAirline, $shipmentEmployee_id;

    public $shipmentFlightVesselName, $shipmentFlightVesselNo, $shipmentCustomerCodeJob;
    public $shipmentBillLadingDate, $shipmentPort_of_loading, $shipmentPort_of_final;
    public $shipmentPlace_of_receipt, $shipmentPort_of_receipt, $shipmentPort_of_discharge;
    public $shipmentPlace_of_delivery, $shipmentOcean_vessel_feeder;
    public $shipmentEstimearrival, $shipmentEstimedelivery, $shipmentPayableAtJob;
    public $shipmentServices_type, $shipmentIncoTerms, $shipmentFreightTypeJob;
    public $shipmentCross_trade, $shipmentRemarksJobDetailJobs;

    public $shipmentNoOfPackages, $shipmentGrossWeight, $shipmentVolumeWeight;
    public $shipmentVolume, $shipmentChargableWeight, $shipmentHsCode, $shipmentHsCodeDesc;
    public $shipmentContainerRemarks, $shipmentTypeOfVolumeWeight, $shipmentTypeOfGrossWeight;
    public $shipmentTypeOfPackages, $typeOfShipmentVolume;

    public function mount($id)
    {
        // $this->shipmentId = $id;
        $this->shipment = TShipments::with('container')->findOrFail($id);

        // Populate form fields
        $this->shipmentType_job = $this->shipment->shipmentsTypeJob;
        $this->shipment_id = $this->shipment->shipment_id;
        $this->shipmentClient_id = $this->shipment->shipmentClient_id;
        $this->shipmentClient_address = $this->shipment->shipmentClient_address;
        $this->shipmentShipper_id = $this->shipment->shipmentShipper_id;
        $this->shipmentConsignee_id = $this->shipment->shipmentConsignee_id;
        $this->shipmentNotify_id = $this->shipment->shipmentNotify_id;
        $this->shipmentCarrierAirline = $this->shipment->shipmentCarrierAirline;
        $this->shipmentCarrierAgent = $this->shipment->shipmentCarrierAgent;
        $this->shipmentDeliveryAgent = $this->shipment->shipmentDeliveryAgent;
        $this->shipmentContainerDeliveryAgent = $this->shipment->shipmentContainerDeliveryAgent;
        $this->containerShipmentCarrierAirline = $this->shipment->containerShipmentCarrierAirline;
        $this->shipmentEmployee_id = $this->shipment->employee_id;

        $data = $this->shipment->dataShipments ?? [];
        $this->shipmentFlightVesselName = $data['shipmentFlightVesselName'] ?? null;
        $this->shipmentFlightVesselNo = $data['shipmentFlightVesselNo'] ?? null;
        $this->shipmentCustomerCodeJob = $data['shipmentCustomerCodeJob'] ?? null;
        $this->shipmentBillLadingDate = $data['shipmentBillLadingDate'] ?? null;
        $this->shipmentPort_of_loading = $data['shipmentPort_of_loading'] ?? null;
        $this->shipmentPort_of_final = $data['shipmentPort_of_final'] ?? null;
        $this->shipmentPlace_of_receipt = $data['shipmentPlace_of_receipt'] ?? null;
        $this->shipmentPort_of_receipt = $data['shipmentPort_of_receipt'] ?? null;
        $this->shipmentPort_of_discharge = $data['shipmentPort_of_discharge'] ?? null;
        $this->shipmentPlace_of_delivery = $data['shipmentPlace_of_delivery'] ?? null;
        $this->shipmentOcean_vessel_feeder = $data['shipmentOcean_vessel_feeder'] ?? null;
        $this->shipmentEstimearrival = $data['shipmentEstimearrival'] ?? null;
        $this->shipmentEstimedelivery = $data['shipmentEstimedelivery'] ?? null;
        $this->shipmentPayableAtJob = $data['shipmentPayableAtJob'] ?? null;
        $this->shipmentServices_type = $data['shipmentServices_type'] ?? null;
        $this->shipmentIncoTerms = $data['shipmentIncoTerms'] ?? null;
        $this->shipmentFreightTypeJob = $data['shipmentFreightTypeJob'] ?? null;
        $this->shipmentCross_trade = $data['shipmentCross_trade'] ?? null;
        $this->shipmentRemarksJobDetailJobs = $data['shipmentRemarksJobDetailJobs'] ?? null;

        $container = $this->shipment->container->first()?->containersData ?? [];
        $this->shipmentNoOfPackages = $container['shipmentNoOfPackages'] ?? null;
        $this->shipmentGrossWeight = $container['shipmentGrossWeight'] ?? null;
        $this->shipmentVolumeWeight = $container['shipmentVolumeWeight'] ?? null;
        $this->shipmentVolume = $container['shipmentVolume'] ?? null;
        $this->shipmentChargableWeight = $container['shipmentChargableWeight'] ?? null;
        $this->shipmentHsCode = $container['shipmentHsCode'] ?? null;
        $this->shipmentHsCodeDesc = $container['shipmentHsCodeDesc'] ?? null;
        $this->shipmentContainerRemarks = $container['shipmentContainerRemarks'] ?? null;
        $this->shipmentTypeOfVolumeWeight = $container['shipmentTypeOfVolumeWeight'] ?? null;
        $this->shipmentTypeOfGrossWeight = $container['shipmentTypeOfGrossWeight'] ?? null;
        $this->shipmentTypeOfPackages = $container['shipmentTypeOfPackages'] ?? null;
        $this->typeOfShipmentVolume = $container['typeOfShipmentVolume'] ?? null;

        $this->shipmentClientAddresses = customerAddress::where('customer_id', $this->shipmentClient_id)->get();
    }
    public function previousStep()
    {
        $this->step--;
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
    public function nextStep()
    {
        // dd($this->shipmentClient_address);

        // $this->validateCurrentStep();
        $this->step++;
    }
    public function updateShipment()
    {
        $container = [
            'shipmentNoOfPackages'           => $this->shipmentNoOfPackages,
            'shipmentGrossWeight'            => $this->shipmentGrossWeight,
            'shipmentVolumeWeight'           => $this->shipmentVolumeWeight,
            'shipmentVolume'                 => $this->shipmentVolume,
            'shipmentChargableWeight'        => $this->shipmentChargableWeight,
            'shipmentHsCode'                 => $this->shipmentHsCode,
            'shipmentHsCodeDesc'             => $this->shipmentHsCodeDesc,
            'shipmentContainerRemarks'       => $this->shipmentContainerRemarks,
            'shipmentTypeOfVolumeWeight'     => $this->shipmentTypeOfVolumeWeight,
            'shipmentTypeOfGrossWeight'      => $this->shipmentTypeOfGrossWeight,
            'shipmentTypeOfPackages'         => $this->shipmentTypeOfPackages,
            'typeOfShipmentVolume'           => $this->typeOfShipmentVolume,
            'containerShipmentCarrierAirline' => $this->containerShipmentCarrierAirline,
        ];
        $payload = [
            'shipmentFlightVesselName'         => $this->shipmentFlightVesselName,
            'shipmentFlightVesselNo'           => $this->shipmentFlightVesselNo,
            'shipmentCustomerCodeJob'          => $this->shipmentCustomerCodeJob,
            'shipmentBillLadingDate'           => $this->shipmentBillLadingDate,
            'shipmentPort_of_loading'          => $this->shipmentPort_of_loading,
            'shipmentPort_of_final'            => $this->shipmentPort_of_final,
            'shipmentPlace_of_receipt'         => $this->shipmentPlace_of_receipt,
            'shipmentPort_of_receipt'          => $this->shipmentPort_of_receipt,
            'shipmentPort_of_discharge'        => $this->shipmentPort_of_discharge,
            'shipmentPlace_of_delivery'        => $this->shipmentPlace_of_delivery,
            'shipmentOcean_vessel_feeder'      => $this->shipmentOcean_vessel_feeder,
            'shipmentEstimearrival'            => $this->shipmentEstimearrival,
            'shipmentEstimedelivery'           => $this->shipmentEstimedelivery,
            'shipmentPayableAtJob'             => $this->shipmentPayableAtJob,
            'shipmentServices_type'            => $this->shipmentServices_type,
            'shipmentIncoTerms'                => $this->shipmentIncoTerms,
            'shipmentFreightTypeJob'           => $this->shipmentFreightTypeJob,
            'shipmentCross_trade'              => $this->shipmentCross_trade,
            'shipmentRemarksJobDetailJobs'     => $this->shipmentRemarksJobDetailJobs,
        ];
        $this->shipment->update([
            'shipmentClient_id' => $this->shipmentClient_id,
            'shipmentClient_address' => $this->shipmentClient_address,
            'shipmentCarrierAirline' => $this->shipmentCarrierAirline,
            'shipmentDeliveryAgent' => $this->shipmentDeliveryAgent,
            'shipmentCarrierAgent' => $this->shipmentCarrierAgent,
            'shipmentNotify_id' => $this->shipmentNotify_id,
            'shipmentShipper_id' => $this->shipmentShipper_id,
            'shipmentConsignee_id' => $this->shipmentConsignee_id,
            'employee_id' => $this->shipmentEmployee_id,
            'dataShipments' => $payload,
        ]);

        $this->shipment->container()->first()?->update([
            'containersData' => $container,
        ]);

        session()->flash('success', 'Shipment updated successfully.');
        return redirect()->route('listShipment')->with('success', [
            'icon' => 'success',
            'title' => 'Updated!',
        ]);
    }
    public function render()
    {
        $clients = Customer::whereJsonContains('roles', 'client')->get();
        $shippers = Customer::whereJsonContains('roles', 'shipper')->get();
        $consignees = Customer::whereJsonContains('roles', 'consignee')->get();
        $notifys = Customer::whereJsonContains('roles', 'notify')->get();
        $agents = Customer::whereJsonContains('roles', 'agent')->get();
        $carriers = Customer::whereJsonContains('roles', 'carrier')->get();
        $airlines = Customer::whereJsonContains('roles', 'airline')->get();
        $employe = User::all('id', 'name');
        $deliveryAgent = Customer::whereJsonContains('roles', 'delivery_agent')->get();
        $carrierAgent = Customer::whereJsonContains('roles', 'carrier_agent')->get();
        return view('livewire.shipment.edit-shipment', compact(
            'clients',
            'shippers',
            'consignees',
            'notifys',
            'agents',
            'carriers',
            'airlines',
            'employe',
            'carrierAgent',
            'deliveryAgent'
        ));
    }
}
