<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Job;

class CustomerOutstandingDebts extends Component
{
    public $customers;
    public $customerDebts = [];

    public function mount()
    {
        $this->customers = Customer::whereJsonContains('roles', 'client')
            ->with([
                'jobs.jobTransactions' => function ($q) {
                    $q->whereNotNull('samountidr'); // hanya transaksi sales
                },
                'jobs.paymentAllocations'
            ])->get();

        $this->customerDebts = $this->customers->map(function ($customer) {
            $totalInvoice = 0;
            $totalPaid = 0;

            foreach ($customer->jobs as $job) {
                $totalInvoice += $job->jobTransactions->sum('samountidr');
                $totalPaid += $job->paymentAllocations->sum('allocated_amount');
            }

            return [
                'customer_id'    => $customer->id,
                'customer_name'  => $customer->name,
                'total_invoice'  => $totalInvoice,
                'total_paid'     => $totalPaid,
                'outstanding'    => max(0, $totalInvoice - $totalPaid),
            ];
        })->values();
    }


    public function render()
    {
        return view('livewire.customer-outstanding-debts');
    }
}
