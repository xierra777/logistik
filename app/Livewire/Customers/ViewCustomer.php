<?php

namespace App\Livewire\Customers;

use App\Models\ChartOfAccount;
use App\Models\Customer;
use Livewire\Component;

class ViewCustomer extends Component
{
    public $customer;
    public $chartOfAccount;


    public function mount(Customer $id)
    {
        $this->customer = $id->load('coa');

        // Ambil object ChartOfAccount, bukan cuma ID
        $this->chartOfAccount = ChartOfAccount::where('id', $this->customer->coa_id)->first();
    }

    public function render()
    {
        return view('livewire.customers.view-customer');
    }
}
