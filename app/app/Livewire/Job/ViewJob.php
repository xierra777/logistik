<?php

namespace App\Livewire\Job;

use Livewire\Component;
use App\Models\TJob;
use Illuminate\Support\Str;

class ViewJob extends Component
{

    public $job;
    public $type_job = '';
    public $organizationFields = [];

    public function mount($id)
    {
        $this->job = TJob::with(['client', 'TjobContainer'])->findOrFail($id);

        $this->type_job = $this->job->type_job; // <-- Assign langsung dari relasi job
        $this->organizationFields = [
            'Client' => 'client',
            'Shipper' => 'shipper',
            'Consignee' => 'consignee',
            'Notify Party' => 'notify',
        ];
    }
    public function getOrganizationsProperty()
    {
        return collect([
            [
                'label' => 'Client',
                'data' => $this->job->client,
            ],
            [
                'label' => $this->type_job === 'ocean_fcl_export' ? 'Delivery Agent' : 'Origin Agent',
                'data' => $this->type_job === 'ocean_fcl_export'
                    ? $this->job->dagents
                    : $this->job->oagents,
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
