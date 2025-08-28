<?php

namespace App\Livewire\Customers;

use Livewire\Component;
use App\Models\Customer;
use App\Models\ChartOfAccount;
use App\Models\customerAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CreateCustomer extends Component
{
    public $name, $email, $contact, $address, $web, $coa_id;
    public $roles = [];
    public $country_code;
    public $country;
    public $customer_code;
    public $chartOfAccounts;

    protected $rules = [
        'name' => 'required|min:3|max:255',
        'email' => 'required',
        'address' => 'required',
        'coa_id' => 'required|exists:chart_of_accounts,id',
        'country_code' => 'required|size:2',
        'roles' => 'required|array|min:1',
        'customer_code' => 'required|unique:customers,customer_code'
    ];


    public function mount()
    {
        $this->chartOfAccounts = Cache::remember('chart-of-accounts', 3600, function () {
            return ChartOfAccount::orderBy('account_code')->get();
        });
    }

    public function updated($property)
    {
        if (in_array($property, ['name', 'country_code'])) {
            $this->generateCustomerCode();
        }
    }

    public function generateCustomerCode()
    {
        if (!$this->country_code || !$this->name) return;

        $year = Carbon::now()->format('y');

        // Generate base code
        $cleanName = preg_replace(['/PT[\s\.]*/i', '/[^A-Za-z0-9]/'], ['', ''], $this->name);
        $custPart = strtoupper(substr(str_replace(' ', '', $cleanName) ?: 'CUST', 0, 5));
        $baseCode = $this->country_code . $custPart . $year;

        // Cari nomor terakhir dengan pattern yang lebih spesifik
        $lastNumber = Customer::where('customer_code', 'like', $baseCode . '-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(customer_code, "-", -1) AS UNSIGNED) DESC')
            ->value('customer_code');

        $number = 1;
        if ($lastNumber) {
            $parts = explode('-', $lastNumber);
            $number = (int)end($parts) + 1;
        }

        $this->customer_code = $baseCode . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function save()
    {

        $this->validate();

        $customer = Customer::create([
            'name' => $this->name,
            'email' => $this->email,
            'contact' => $this->contact,
            'country' => $this->country,
            'web' => $this->web,
            'roles' => $this->roles,
            'coa_id' => $this->coa_id,
            'customer_code' => $this->customer_code,
            'created_by' => Auth::user()->id
        ]);

        customerAddress::create([
            'address' => $this->address,
            'customer_id' => $customer->id,
        ]);

        return redirect()->route('listCust')->with('success', [
            'icon' => 'success', // Type of alert: 'success', 'error', 'warning', etc.
            'title' => 'Success!', // Toast title

        ]);
    }

    public function render()
    {
        return view('livewire.customers.create-customer');
    }
}
