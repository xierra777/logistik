<?php

namespace App\Livewire\Bank;

use Livewire\Component;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CreateBank extends Component
{

    public $customers, $chartAccounts, $bank_code, $bank_name, $swift_code, $bank_account_number, $currency, $branch_name;
    public $customer_id, $coa_id;
    public function mount()
    {
        $this->generateCodeBank();

        $this->customers = Customer::all();
        $this->chartAccounts = ChartOfAccount::where('is_payment', true)->get();
    }


    public function createBank()
    {
        $this->validate([
            'customer_id' => 'required',
            'coa_id' => 'required',
        ]);

        $bank = Bank::create([
            'customer_id' => $this->customer_id,
            'bank_name' => $this->bank_name,
            'created_by' => Auth::user()->id,
        ]);
        BankAccount::create([
            'bank_id' => $bank->id,
            'bank_coa_id' => $this->coa_id,
            'swift_code' => $this->swift_code,
            'bank_code' => $this->bank_code,
            'bank_account_number' => $this->bank_account_number,
            'currency' => $this->currency,
            'branch_name' => $this->branch_name,
            'created_by' => Auth::user()->id,

        ]);

        session()->flash('message', 'Bank created successfully.');

        return redirect()->route('listBank');
    }
    public function generateCodeBank()
    {

        $count = BankAccount::count() + 1;
        $date = now()->format('ym');
        $prefix = 'BANK-' . 'BRN-' . $date . str_pad($count, 3, 0, STR_PAD_LEFT);

        $this->bank_code = $prefix;
    }
    public function render()
    {
        return view('livewire.bank.create-bank');
    }
}
