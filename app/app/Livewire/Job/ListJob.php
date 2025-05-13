<?php

namespace App\Livewire\Job;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TJob;

class ListJob extends Component
{
    use WithPagination;
    public $perPage = 5;
    public $start_date, $end_date;
    public $searchField   = 'shipment_id';  // default column
    public $searchTerm    = '';

    public function confirmDelete($get_id)
    {
        try {
            TJob::destroy($get_id);
            session()->flash('message', 'Shipment deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting shipment: ' . $e->getMessage());
        }
    }
    public function render()
    {
        $query = TJob::latest(); // orderBy created_at desc

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [
                $this->start_date,
                $this->end_date
            ]);
        }

        if ($this->searchTerm) {
            if ($this->searchField === 'client') {
                $query->whereHas('client', function ($q) {
                    $q->where('name', 'like', '%' . $this->searchTerm . '%');
                });
            } else {
                $query->where($this->searchField, 'like', '%' . $this->searchTerm . '%');
            }
        }
        $job = $query->paginate($this->perPage);

        return view('livewire.job.list-job', compact('job'));
    }
}
