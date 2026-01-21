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
    public $searchField   = 'job_id';  // default column
    public $searchTerm    = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function confirmDelete($get_id)
    {
        try {
            TJob::destroy($get_id);
            session()->flash('message', 'Shipment deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting shipment: ' . $e->getMessage());
        }
    }
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    public function render()
    {
        $query = TJob::with('client');

        // daftar field JSON
        $jsonFields = [
            'pol' => 'port_of_loading',
            'pod' => 'place_of_delivery',
            'etd' => 'estimedelivery',
            'eta' => 'estimearrival',
        ];

        if (array_key_exists($this->sortField, $jsonFields)) {
            $key = $jsonFields[$this->sortField];
            $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.\"$key\"')) {$this->sortDirection}");
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }


        // filter tanggal
        if ($this->start_date && $this->end_date) {
            $query->whereBetween("data->jobBillLadingDate", [
                $this->start_date,
                $this->end_date
            ]);
        }

        // search
        if ($this->searchTerm) {
            if ($this->searchField === 'client') {
                $query->whereHas(
                    'client',
                    fn($q) =>
                    $q->where('name', 'like', '%' . $this->searchTerm . '%')
                );
            } else {
                $query->whereRaw("LOWER(REPLACE(`{$this->searchField}`, '_', ' ')) LIKE ?", [
                    '%' . strtolower($this->searchTerm) . '%'
                ]);
            }
        }

        $job = $query->paginate($this->perPage);

        return view('livewire.job.list-job', compact('job'));
    }
}
