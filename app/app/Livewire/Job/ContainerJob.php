<?php

namespace App\Livewire\Job;

use Livewire\Component;
use App\Models\jobContainer;
use App\Models\TJob;

class ContainerJob extends Component
{
    public $job;
    public $jobContainer;

    /**
     * Mount menggunakan dua parameter yang
     * sesuai dengan nama placeholder di route:
     * - {id}           → $id
     * - {jobContainer_id} → $jobContainer_id
     */
    public function mount($id, $jobContainer_id)
    {
        // Ambil TJob
        $this->job = TJob::with('TjobContainer')->findOrFail($id);

        $this->jobContainer = jobContainer::where('id', $jobContainer_id)
            ->where('id_job', $id)
            ->firstOrFail();
    }

    public function render()
    {
        return view('livewire.job.container-job', ['job' => $this->job, 'jobContainer' => $this->jobContainer]);
    }
}
