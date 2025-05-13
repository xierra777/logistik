<?php

namespace App\Livewire\Job;

use Livewire\Component;
use App\Models\TJob;

class ViewJob extends Component
{
    public $job;

    public function mount($id)
    {
        $this->job = TJob::with(['TjobContainer'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.job.view-job');
    }
}
