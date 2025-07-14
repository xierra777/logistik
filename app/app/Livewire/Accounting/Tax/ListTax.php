<?php

namespace App\Livewire\Accounting\Tax;

use App\Models\ChartOfAccount;
use Livewire\Component;
use App\Models\transaction\tax;
use Illuminate\Support\Facades\Auth;

class ListTax extends Component
{
    public $name, $description, $type, $rate, $context, $coa_id, $taxAccount, $is_active = true;
    public function save()
    {
        tax::create([
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'rate' => $this->rate,
            'context' => $this->context,
            'coa_id' => $this->coa_id,
            'is_active' => $this->is_active,
            'created_by' => Auth::user()->id
        ]);
        $this->reset(); // Optional, reset semua input

        $this->dispatch('closeModal');
    }
    public function render()
    {
        $this->taxAccount = ChartOfAccount::whereIn('account_type', ['Liability', 'Expense'])->get();
        return view(
            'livewire.accounting.tax.list-tax',
            ['accounts' => tax::orderBy('context')->get(),]
        );
    }
}
