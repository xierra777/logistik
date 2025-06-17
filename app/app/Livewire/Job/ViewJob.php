<?php

namespace App\Livewire\Job;

use Livewire\Component;
use App\Models\TJob;
use App\Models\jobContainer;
use App\Models\shipmentContainers;
use App\Models\Transaction;
use App\Models\TShipments;
use Illuminate\Support\Facades\Auth;

class ViewJob extends Component
{

    public $job;
    public $refreshKey;
    public $type_job = '';
    public array $selectedShipments = [];
    public $organizationFields = [];
    public array $selectedAssignedShipments = [];
    public $modalContainer = true;
    public $containerType, $noOfPackages, $containerReleaseNo, $containerReleaseDate, $typeOfPackages, $grossWeight, $typeOfGrossWeight, $volumeWeight, $typeOfVolumeWeight, $volume, $chargableWeight, $containerRemarks, $containerNo, $containerSealNo, $noOfPallet, $netOfWeight, $typeNetOfWeight, $totalWeight, $typeOfTotalWeight, $hsCode, $hsCodeDesc;


    public function mount($id)
    {
        $this->loadJob($id); // cukup panggil method ini, tidak perlu cari ulang shipment ID

        $this->type_job = $this->job->type_job; // <-- Assign langsung dari relasi job
        $this->organizationFields = [
            'Client' => 'client',
            'Shipper' => 'shipper',
            'Consignee' => 'consignee',
            'Notify Party' => 'notify',

        ];
    }
    public function refreshTransaction($id)
    {

        $this->refreshKey = now()->timestamp;
        $this->loadJob($id); // cukup panggil method ini, tidak perlu cari ulang shipment ID

    }
    public function loadJob($id)
    {
        $this->job = TJob::with([
            'client',
            'TjobContainer',
            'carrierModel',
            'jobTransactions',     // relasi ke Customer
            'ogents',
            'dagents',
            'employee',
        ])->findOrFail($id);
    }
    public function detachSelectedShipments()
    {
        if (empty($this->selectedAssignedShipments)) {
            return;
        }

        TShipments::whereIn('id', $this->selectedAssignedShipments)
            ->update(['id_job' => null]);
        shipmentContainers::whereIn('id_shipments', $this->selectedAssignedShipments)
            ->update(['id_jobContainer' => null]);
        $this->job->refresh();
        $this->selectedAssignedShipments = [];
        $this->dispatch('close-detach-assigned');
    }

    public function assignSelectedShipments()
    {
        if (empty($this->selectedShipments)) {
            return;
        }
        $jobContainer = JobContainer::firstOrCreate([
            'id_job' => $this->job->id,
        ]);
        TShipments::whereIn('id', $this->selectedShipments)
            ->update(['id_job' => $this->job->id]);
        $shipmentContainers = shipmentContainers::whereIn('id_shipments', $this->selectedShipments)->get();

        foreach ($shipmentContainers as $container) {
            $container->update([
                'id_jobContainer' => $jobContainer->id,
            ]);
        }
        $this->job->refresh();
        $this->selectedShipments = [];
        $this->dispatch('close-detach-shipment');
    }
    public function confirmDelete($get_id)
    {
        try {
            Transaction::destroy($get_id);
            session()->flash('message', 'Shipment deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting shipment: ' . $e->getMessage());
        }
    }

    public function getAssignedShipmentsProperty()
    {
        return $this->job->shipments;
    }

    public function getOrphanShipmentsProperty()
    {
        return TShipments::whereNull('id_job')->get();
    }

    public function createContainer()
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

        jobContainer::create([
            'id_job' => $this->job->id,
            'containers' => $container,
            'created_by' => Auth::user()->id
        ]);
        $this->reset([
            'containerType',
            'noOfPackages',
            'containerReleaseNo',
            'containerReleaseDate',
            'typeOfPackages',
            'grossWeight',
            'typeOfGrossWeight',
            'volumeWeight',
            'typeOfVolumeWeight',
            'volume',
            'chargableWeight',
            'containerRemarks',
            'containerNo',
            'containerSealNo',
            'noOfPallet',
            'netOfWeight',
            'typeNetOfWeight',
            'totalWeight',
            'typeOfTotalWeight',
            'hsCode',
            'hsCodeDesc'
        ]);
        $this->dispatch('close-create-container');
    }
    public function getOrganizationsProperty()
    {
        return collect([
            [
                'label' => 'Client',
                'data' => $this->job->client,
            ],
            [
                'label' => match ($this->type_job) {
                    'ocean_fcl_export', 'ocean_lcl_export', 'air_outbound' => 'Delivery Agent',
                    'air_inbound', 'ocean_fcl_import', 'ocean_lcl_import' => 'Origin Agent'
                },
                'data' => match ($this->type_job) {
                    'ocean_fcl_export', 'ocean_lcl_export', 'air_outbound' => $this->job->dagents,
                    'air_inbound', 'ocean_fcl_import', 'ocean_lcl_import' => $this->job->oagents,
                    default => null,
                },
            ],
            [
                'label' => 'Shipper',
                'data' => optional($this->job->shipment)->shipper,
            ],
            [
                'label' => 'Consignee',
                'data' => optional($this->job->shipment)->consignee,
            ],
            [
                'label' => 'Notify',
                'data' => optional($this->job->shipment)->notify,
            ],
        ])->filter(fn($item) => !is_null($item['data'])); // buang yang null
    }

    public function render()
    {
        return view('livewire.job.view-job',);
    }
}
