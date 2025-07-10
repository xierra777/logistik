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
                'jobs.jobTransactions.invoices' => function ($q) {
                    $q->whereNotNull('total_amount')
                        ->where('status', '=', 'issued'); // lebih eksplisit
                },
                'jobs.paymentAllocations'
            ])->get();

        $this->customerDebts = $this->customers->map(function ($customer) {
            $totalInvoice = $customer->jobs->sum(function ($job) {
                return $job->jobTransactions->sum(function ($transaction) {
                    // Karena sudah difilter di eager loading, semua invoices di sini sudah status 'issued'
                    return $transaction->invoices->sum('total_amount');
                });
            });

            $totalPaid = $customer->jobs->sum(function ($job) {
                return $job->paymentAllocations->sum('allocated_amount');
            });

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
