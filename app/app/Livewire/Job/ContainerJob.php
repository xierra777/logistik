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

        // Optionally, refresh the jobContainer property or redirect
        session()->flash('message', 'Container deleted successfully.');
        return redirect()->route('viewJob', ['id' => $this->job->id]);

        // Refresh the jobContainer list or redirect as needed
        // For example, you might want to emit an event or update the property
        return redirect('ViewJob');
    }

    public function render()
    {
        return view('livewire.job.container-job', ['job' => $this->job, 'jobContainer' => $this->jobContainer]);
    }
}
