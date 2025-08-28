<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory;
    // use softDeletes;

    protected $fillable = [
        'invoice_number',
        'shipment_id',
        'job_id',
        'customer_id',
        'bank_id',
        'invoice_date',
        'due_date',
        'currency',
        'total_amount',
        'status',
        'void_reason',
        'type_invoice',
        'created_by',
        'updated_by',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }
    public function shipment()
    {
        return $this->belongsTo(TShipments::class);
    }
    public function job()
    {
        return $this->belongsTo(TJob::class);
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function paymentAllocations()
    {
        return $this->hasMany(PaymentAllocation::class, 'invoice_id');
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'invoice_transaction')
            ->using(InvoiceTransaction::class) // Pake custom pivot model
            ->withPivot('amountInvoice', 'amountInvoiceUsd', 'quantityInvoice', 'vatInvoice', 'vatInvoiceUsd', 'whtInvoice', 'whtInvoiceUsd', 'remarks')
            ->withTimestamps();
    }
    public function invtrx()
    {
        return $this->hasMany(InvoiceTransaction::class, 'invoice_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
