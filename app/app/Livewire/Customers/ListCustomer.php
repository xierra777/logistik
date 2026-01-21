<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class ListCustomer extends Component
{
    use WithPagination;
    public $perPage = 5;
    public $start_date, $end_date;
    public $searchField   = 'name';  // default column
    public $searchTerm    = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function confirmDelete($get_id)
    {
        try {
            customer::destroy($get_id);
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
        $query = Customer::query()->latest();

        if ($this->start_date && $this->end_date) {
            $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
        }

        if ($this->searchTerm) {
            if ($this->searchField === 'roles') {
                $query->whereJsonContains('roles', $this->searchTerm);
            } else {
                $query->where($this->searchField, 'like', '%' . $this->searchTerm . '%');
            }
        }

        $customers = $query->paginate($this->perPage);

        return view('livewire.customers.list-customer', compact('customers'));
    }
}
