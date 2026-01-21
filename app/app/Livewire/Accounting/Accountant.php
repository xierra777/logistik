<?php

namespace App\Livewire\Accounting;

use App\Models\ChargeSetting;
use Livewire\Component;
use App\Models\ChartOfAccount;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\JournalEntry;
use App\Models\transaction\tax;
use Illuminate\Support\Facades\DB;

class Accountant extends Component
{
    public $coa, $tax, $chargeCoa, $invoices, $extra;
    public $totaltransaksi;
    public $totalOutstanding = 0;
    public $shipmentWithTransactionsCount;
    public $customers, $customerDebts;

    public function mount()
    {
        $this->coa = ChartOfAccount::count();
        $this->totaltransaksi = JournalEntry::count();
        $this->chargeCoa = ChargeSetting::count();
        $this->tax = tax::count();
        $this->invoices = Invoice::count();
        $this->extra = Invoice::sum('total_amount'); {
            // Load customers first


        }
    }
    public function getCombinedLineData()
    {
        // Ambil akun-akun Revenue & Expense yang punya parent
        $accounts = ChartOfAccount::whereNotNull('parent_account_id')
            ->whereIn('account_type', ['Revenue', 'Expense'])
            ->pluck('id');

        // Ambil journal entry yang relevan
        $journals = JournalEntry::with('chartOfAccount')
            ->whereIn('coa_id', $accounts)->where('is_reversal', '0')
            ->orderBy('date')
            ->get()
            ->groupBy(fn($j) => $j->created_at->format('Y-m')); // group per jam

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
