<?php

namespace App\Livewire\Bank;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewBank extends Component
{

    public $customers, $chartAccounts, $bank_code, $bank_name, $swift_code, $bank_account_number, $currency, $branch_name, $bank, $coa_id;
    public function mount($id)
    {
        $this->bank = Bank::with('customer', 'accounts')->findOrFail($id);
        $this->customers = Customer::all();
        $this->chartAccounts = ChartOfAccount::where('is_payment', true)->get();
    }

    public function openCreateForm()
    {
        $this->generateCodeBank();
    }
    public function generateCodeBank()
    {

        $count = BankAccount::count() + 1;
        $date = now()->format('ym');
        $prefix = 'BANK-' . 'BRN-' . $date . str_pad($count, 3, 0, STR_PAD_LEFT);

        $this->bank_code = $prefix;
    }

    public function createViewBank()
    {
        // dd($this->currency);
        // $this->validate([
        //     'bank_code' => 'required',
        //     'bank_name' => 'required',
        //     'swift_code' => 'required',
        //     'bank_account_number' => 'required',
        //     'currency' => 'required',
        //     'branch_name' => 'required',
        // ]);

        BankAccount::create([
            'bank_id' => $this->bank->id,
            'bank_code' => $this->bank_code,
            'swift_code' => $this->swift_code,
            'bank_account_number' => $this->bank_account_number,
            'currency' => $this->currency,
            'branch_name' => $this->branch_name,
            'bank_coa_id' => $this->coa_id,
            'created_by' => Auth::user()->id,
        ]);
        session()->flash('message', 'Bank account created successfully.');
        BankAccount::all();
        $this->dispatch('close-create-container');
        $this->reset(['bank_code', 'swift_code', 'bank_account_number', 'currency', 'branch_name', 'coa_id']);
    }
    public function confirmDelete($get_id)
    {
        try {
            BankAccount::destroy($get_id);
            session()->flash('message', 'Shipment deleted successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting shipment: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.bank.view-bank');
    }
}
