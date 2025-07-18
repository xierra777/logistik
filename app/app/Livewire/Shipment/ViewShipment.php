<?php

namespace App\Livewire\Shipment;

use App\Models\shipmentContainers;
use Livewire\Component;
use App\Models\TShipments;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class ViewShipment extends Component
{
    public $shipmentsTypeJob = '', $shipmentId;
    public $shipment;
    public $type_shipments = '';
    public $organizationFields = [];
    public $refreshKey = null;
    public $shipmentNoOfPackages, $shipmentGrossWeight, $shipmentVolumeWeight, $shipmentVolume, $shipmentChargableWeight, $ShipmentHsCode, $shipmentContainerRemarks, $shipmentHsCodeDesc, $shipmentTypeOfVolumeWeight, $shipmentTypeOfGrossWeight, $shipmentTypeOfPackages, $typeOfShipmentVolume, $shipmentHsCode, $parentContainer;
    public $isEditing = false;
    public $editingTransactionId, $editingShipmentId;


    protected $listeners = [
        'transactionSaved' => 'refreshShipment',
        'close-modal' => 'closeEditTransaction',

    ];
    public function mount($id)
    {
        $this->shipmentId = $id;
        $this->loadShipment($id);
        $this->organizationFields = [
            'Client' => 'client.addresses',
            'Shipper' => 'shipper.addresses',
            'Consignee' => 'consignee.addresses',
            'Notify Party' => 'notify.addresses',

        ];
    }
    public function editTransaction($shipmentId, $transactionId)
    {
        $this->isEditing = true;
        $this->editingTransactionId = $transactionId;
        $this->editingShipmentId = $shipmentId;
    }
    public function closeEditTransaction()
    {
        $this->isEditing = false;
    }
    public function confirmDelete($get_id)
    {
        // dd('niggas');
        // Ambil transaksi beserta relasi invoice-nya
        $transaction = Transaction::with('invs')->find($get_id);

        if (!$transaction) {
            $this->dispatch('swal', [
                'title' => 'Gagal',
                'text' => 'Transaksi tidak ditemukan.',
                'icon' => 'error',
            ]);
            return;
        }

        // Kalau invoice-nya sudah issued, tolak penghapusan
        if ($transaction->invs && $transaction->invs->status === 'issued') {
            $this->dispatch('swal', [
                'title' => 'Tidak Bisa Dihapus',
                'text' => 'Transaksi ini sudah masuk ke invoice yang telah issued.',
                'icon' => 'error',
            ]);
            return;
        }

        // Kalau belum issued, lanjut hapus
        try {
            Transaction::destroy($get_id);
            $this->dispatch('swal', [
                'title' => 'Success!',
                'text' => 'Transaction Deleted',
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function createContainer()
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
            'shipmentContainerNo'          => $this->parentContainer
        ];
        // dd($this->parentContainer);
        shipmentContainers::create([
            'id_jobContainer' => $this->shipment->job && $this->shipment->job->Tjobcontainer->isNotEmpty()
                ? $this->parentContainer
                : null,
            'containersData' => $container,
            'id_shipments'   => $this->shipmentId,
            'created_by'     => Auth::user()->id,
        ]);

        $this->resetContainerFields();
        $this->dispatch('close-create-container');
    }
    public function resetContainerFields()
    {
        $this->reset([
            'shipmentNoOfPackages',
            'shipmentGrossWeight',
            'shipmentVolumeWeight',
            'shipmentVolume',
            'shipmentChargableWeight',
            'shipmentHsCode',
            'shipmentHsCodeDesc',
            'shipmentContainerRemarks',
            'shipmentTypeOfVolumeWeight',
            'shipmentTypeOfGrossWeight',
            'shipmentTypeOfPackages',
            'typeOfShipmentVolume',
            'parentContainer',
        ]);
    }

    public function refreshTransaction()
    {

        $this->refreshKey = now()->timestamp;
    }

    public function loadShipment($id)
    {
        $this->shipment = TShipments::with([
            'job',
            'container.jobContainer',
            'client.addresses',
            'shipper.addresses',
            'consignee.addresses',
            'notify.addresses',
            'deliveryAgent.addresses',
            'carrierModel',
            'carrierAgent',
            'shipmentTransaction',
        ])->findOrFail($id);
    }
    public function getOrganizationsProperty()
    {

        return collect([
            [
                'label' => 'Client',
                'dataShipments' => optional($this->shipment->client) ? (object)[
                    'id' => $this->shipment->client->id,
                    'name' => $this->shipment->client->name,
                    'email' => $this->shipment->client->email,
                    'contact' => $this->shipment->client->contact,
                    'address' => $this->shipment->shipmentClient_address,
                ] : null,
            ],
            [
                'label' => 'Shipper',
                'dataShipments' => optional($this->shipment->shipper) ? (object)[
                    'id' => $this->shipment->shipper->id,
                    'name' => $this->shipment->shipper->name,
                    'email' => $this->shipment->shipper->email,
                    'contact' => $this->shipment->shipper->contact,
                    'address' => optional($this->shipment->shipper->addresses->first())->address,
                ] : null,
            ],
            [
                'label' => 'Consignee',
                'dataShipments' => optional($this->shipment->consignee) ? (object)[
                    'id' => $this->shipment->consignee->id,
                    'name' => $this->shipment->consignee->name,
                    'email' => $this->shipment->consignee->email,
                    'contact' => $this->shipment->consignee->contact,
                    'address' => optional($this->shipment->consignee->addresses->first())->address,
                ] : null,
            ],
            [
                'label' => 'Notify',
                'dataShipments' => optional($this->shipment->notify) ? (object)[
                    'id' => $this->shipment->notify->id,
                    'name' => $this->shipment->notify->name,
                    'email' => $this->shipment->notify->email,
                    'contact' => $this->shipment->notify->contact,
                    'address' => optional($this->shipment->notify->addresses->first())->address,
                ] : null,
            ],
        ])->filter(fn($item) => !is_null($item['dataShipments']));
    }




    public function render()
    {
        return view('livewire.shipment.view-shipment');
    }
}
