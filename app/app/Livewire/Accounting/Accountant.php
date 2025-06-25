<?php

namespace App\Livewire\Accounting;

use App\Models\ChargeSetting;
use Livewire\Component;
use App\Models\ChartOfAccount;
use App\Models\Transaction;
use App\Models\Shipment;
use App\Models\JournalEntry;
use App\Models\transaction\tax;
use Illuminate\Support\Facades\DB;

class Accountant extends Component
{
    public $coa, $tax, $chargeCoa;
    public $totaltransaksi;
    public $shipmentWithTransactionsCount;

    public function mount()
    {
        $this->coa = ChartOfAccount::count();
        $this->totaltransaksi = JournalEntry::count();
        $this->chargeCoa = ChargeSetting::count();
        $this->tax = tax::count();
    }
    public function getCombinedLineData()
    {
        // Ambil akun-akun Revenue & Expense yang punya parent
        $accounts = ChartOfAccount::whereNotNull('parent_account_id')
            ->whereIn('account_type', ['Revenue', 'Expense'])
            ->pluck('id');

        // Ambil journal entry yang relevan
        $journals = JournalEntry::with('chartOfAccount')
            ->whereIn('coa_id', $accounts)
            ->orderBy('date')
            ->get()
            ->groupBy(fn($j) => $j->created_at->format('Y-m-d s')); // group per jam

        $categories = [];
        $revenues = [];
        $expenses = [];

        foreach ($journals as $date => $entries) {
            $categories[] = $date;
            $revenues[] = $entries->filter(fn($e) => $e->chartOfAccount->account_type === 'Revenue')
                ->sum('credit');
            $expenses[] = $entries->filter(fn($e) => $e->chartOfAccount->account_type === 'Expense')
                ->sum('debit');
        }

        return [
            'categories' => $categories,
            'revenues' => $revenues,
            'expenses' => $expenses
        ];
    }

    public function render()
    {
        $data = $this->getCombinedLineData();

        return view('livewire.accounting.accountant', [
            'categories' => $data['categories'],
            'revenues' => $data['revenues'],
            'expenses' => $data['expenses'],
        ]);
    }
}
