<?php

namespace App\Livewire\Job;

use Livewire\Component;
use App\Models\TJob;
use App\Models\jobContainer;

class ViewJob extends Component
{

    public $job;
    public $type_job = '';
    public $organizationFields = [];
    public $modalContainer = true;
    public $containerType, $noOfPackages, $containerReleaseNo, $containerReleaseDate, $typeOfPackages, $grossWeight, $typeOfGrossWeight, $volumeWeight, $typeOfVolumeWeight, $volume, $chargableWeight, $containerRemarks, $containerNo, $containerSealNo, $noOfPallet, $netOfWeight, $typeNetOfWeight, $totalWeight, $typeOfTotalWeight, $hsCode, $hsCodeDesc;

    public function mount($id)
    {
        $this->job = TJob::with([
            'client',
            'TjobContainer',
            'carrierModel',      // relasi ke Customer
            'ogents',
            'dagents',
            'employee',
        ])->findOrFail($id);

        $this->type_job = $this->job->type_job; // <-- Assign langsung dari relasi job
        $this->organizationFields = [
            'Client' => 'client',
            'Shipper' => 'shipper',
            'Consignee' => 'consignee',
            'Notify Party' => 'notify',

        ];
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
