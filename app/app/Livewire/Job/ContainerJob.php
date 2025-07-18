<?php

namespace App\Livewire\Job;

use Livewire\Component;
use App\Models\jobContainer;
use App\Models\TJob;

class ContainerJob extends Component
{
    public $job;
    public $jobContainer;


    public function mount($id, $jobContainer_id)
    {
        // Ambil TJob
        $this->job = TJob::with('TjobContainer')->findOrFail($id);

        $this->jobContainer = jobContainer::where('id', $jobContainer_id)
            ->where('id_job', $id)
            ->firstOrFail();
    }

    public function deleteContainer($jobContainer_id)
    {
        $container = jobContainer::where('id', $jobContainer_id)
            ->where('id_job', $this->job->id)
            ->firstOrFail();
        $container->delete();
        return redirect()->route('viewJob', ['id' => $this->job->id])->with('success', [
            'icon' => 'success', // 
            'title' => 'Success!', //
        ]);
    }

    public function render()
    {
        return view('livewire.job.container-job', ['job' => $this->job, 'jobContainer' => $this->jobContainer]);
    }
}
