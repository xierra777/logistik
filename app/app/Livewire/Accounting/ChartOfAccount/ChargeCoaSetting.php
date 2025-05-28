<?php

namespace App\Livewire\Accounting\ChartOfAccount;

use App\Models\ChargeSetting;
use App\Models\ChartOfAccount;
use Livewire\Component;

class ChargeCoaSetting extends Component
{
    public $revenueAccounts = [];
    public $expenseAccounts = [];

    public $charge_code, $charge_name, $coa_sale_id, $coa_cost_id;

    public function mount()
    {
        // dd($this->revenueAccounts);
        $this->revenueAccounts = ChartOfAccount::where('account_type', 'Revenue')->get();
        $this->expenseAccounts = ChartOfAccount::where('account_type', 'expense')->get();
    }
    public function save()
    {
        // dd($this->charge_code, $this->charge_name, $this->coa_sale_id, $this->coa_cost_id);
        ChargeSetting::create([
            'charge_code' => $this->charge_code,
            'charge_name' => $this->charge_name,
            'coa_sale_id' => $this->coa_sale_id,
            'coa_cost_id' => $this->coa_cost_id,
        ]);
        $this->reset(); // Optional, reset semua input

        $this->dispatch('closeModal');
    }
    public function render()
    {
        return view(
            'livewire.accounting.chart-of-account.charge-coa-setting',
            ['accounts' => ChargeSetting::orderBy('charge_code')->get(),]
        );
    }
}
