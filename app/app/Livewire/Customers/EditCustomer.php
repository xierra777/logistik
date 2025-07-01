<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;
use App\Models\ChartOfAccount;

class EditCustomer extends Component
{

    public $name, $email, $contact, $address, $web, $coa_id;
    public $roles = [];
    public $country;
    public $customer_id;
    public $chartOfAccounts;
    protected $listeners = [
        'updateCountry',
        'setCountry' => 'setCountry', // Mendapatkan data dari Select2
    ];

    public function updateCountry($data)
    {
        $this->country = $data['value'];
        $this->dispatch('syncCountry', $this->country); // Buat sinkronisasi ke Select2
    }
    public function mount(customer $id)
    {
        $this->customer_id = $id->id;
        $this->name = $id->name;
        $this->email = $id->email;
        $this->contact = $id->contact;
        $this->country = $id->country;
        $this->address = $id->address;
        $this->web = $id->web;
        $this->coa_id = $id->coa_id;
        $this->roles = $id->roles;
        $this->chartOfAccounts = ChartOfAccount::orderBy('account_code')->get();
        $this->dispatch('syncCountry', $this->country);
    }


    public function setCountry($value)
    {
        $this->country = $value;
    }
    protected $rules = [
        'name'    => 'required|min:3',
        'email'   => 'required|email',
        'roles'   => 'min:1|array',
        'contact' => 'required',
        'address' => 'required',
        'country' => 'required',
        'web'     => 'required',
        'coa_id'  => 'required|exists:chart_of_accounts,id',

    ];
    public function updateCustomer()
    {
        customer::where('id', $this->customer_id)->update([
            'name'    => $this->name,
            'email'   => $this->email,
            'contact' => $this->contact,
            'country' => $this->country,
            'web'     => $this->web,
            'coa_id'  => $this->coa_id,
            'roles'   => $this->roles,
        ]);
        session()->flash('message', 'Customer updated successfully');
        return redirect()->route('listCust')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success Updating', // Toast title
        ]);
    }
    public function render()
    {
        return view('livewire.customers.edit-customer');
    }
}
