<?php

namespace App\Livewire\Accounting\ChartOfAccount;

use Livewire\Component;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class ChartOfAccountDetails extends Component
{
    use WithPagination;

    public $account_code, $account_name, $account_type, $term_type, $is_payment;
    public $parent_account_id;
    public $coa_id, $perPage = 5;
    public $isEditing = false;

    protected $rules = [
        'account_code' => 'required|unique:chart_of_accounts,account_code',
        'account_name' => 'required',
        'account_type' => 'required|in:Asset,Liability,Equity,Revenue,Expense',
        // term_type harus diisi jika diperlukan, tapi bisa dibuat nullable jika tidak wajib
        'term_type'    => 'nullable|in:CR,DR',
    ];

    public function save()
    {
        $this->validate();
        // dd($this->is_payment);
        ChartOfAccount::create([
            'account_code'      => $this->account_code,
            'account_name'      => $this->account_name,
            'account_type'      => $this->account_type,
            'term_type'         => $this->term_type,
            'parent_account_id' => $this->parent_account_id,
            'is_payment'        => $this->is_payment,
            'created_by'        => Auth::user()->id
        ]);

        $this->resetForm();
        session()->flash('message', 'Akun COA berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $coa = ChartOfAccount::findOrFail($id);
        $this->coa_id = $coa->id;
        $this->account_code = $coa->account_code;
        $this->account_name = $coa->account_name;
        $this->account_type = $coa->account_type;
        $this->term_type = $coa->term_type;
        $this->is_payment = $coa->is_payment;
        $this->parent_account_id = $coa->parent_account_id;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate([
            'account_code' => 'required|unique:chart_of_accounts,account_code,' . $this->coa_id,
            'account_name' => 'required',
            'account_type' => 'required|in:Asset,Liability,Equity,Revenue,Expense',
            'term_type'    => 'nullable|in:CR,DR',
        ]);

        ChartOfAccount::find($this->coa_id)->update([
            'account_code'      => $this->account_code,
            'account_name'      => $this->account_name,
            'account_type'      => $this->account_type,
            'term_type'         => $this->term_type,
            'parent_account_id' => $this->parent_account_id,
        ]);

        $this->resetForm();
        session()->flash('message', 'Akun COA berhasil diperbarui!');
    }

    public function delete($id)
    {
        ChartOfAccount::findOrFail($id)->delete();
        session()->flash('message', 'Akun COA berhasil dihapus!');
    }

    public function resetForm()
    {
        $this->account_code = null;
        $this->account_name = null;
        $this->account_type = null;
        $this->term_type = null;
        $this->parent_account_id = null;
        $this->coa_id = null;
        $this->isEditing = false;
    }
    public function render()
    {
        $query = ChartOfAccount::query();

        if ($this->account_type) {
            $query->where('account_type', $this->account_type);
        }



        $accounts = $query->orderBy('account_code')->paginate($this->perPage);

        return view('livewire.accounting.chart-of-account.chart-of-account-details', [
            'accounts' => $accounts,
            'parents'  => ChartOfAccount::all(),
        ]);
    }
}
