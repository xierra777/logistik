<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\CreateShipments;
use App\Livewire\ViewShipments;
use App\Livewire\EditShipments;
use App\Livewire\Shipmment;
use App\Livewire\Accounting\Accountant;
use App\Livewire\Accounting\PurchaseInvoice;
use App\Livewire\Accounting\Tranksaksi;
use App\Livewire\Accounting\SaleInvoice;
use App\Livewire\ChartOfAccounts;
use App\Livewire\Users;
use App\Livewire\JournalEntries;
use App\Livewire\Customers\CreateCustomer;
use App\Livewire\Customers\EditCustomer;
use App\Livewire\Customers\ViewCustomer;
use App\Livewire\Customers\ListCustomer;
use App\Livewire\Dashboard;
use App\Livewire\HouseBL;
use App\Livewire\Pdfhbl;
use App\Livewire\Job\CreateJob;
use App\Livewire\Job\EditJob;
use App\Livewire\Job\ListJob;
use App\Livewire\Job\ViewJob;
use Illuminate\Http\Request;



Route::redirect('/', '/login'); // Redirect otomatis ke halaman login
Route::get('/dashboard', Dashboard::class)->middleware([
    'auth',
    'verified'
])->name('dashboard');
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');
Route::view('shipments', 'shipments.index',)
    ->middleware(['auth', 'verified'])
    ->name('shipments');
Route::view('shipment', 'shipments.create',)
    ->middleware(['auth'])
    ->name('shipment');
Route::get('/chart-of-accounts', ChartOfAccounts::class)->middleware([
    'auth',
    'verified'
]);
Route::get('create-job', CreateJob::class)->middleware([
    'auth',
    'verified'
])->name('Createjob');

Route::get('edit-job', EditJob::class)->middleware([
    'auth',
    'verified'
])->name('EditJob');

Route::get('list-job', ListJob::class)->middleware([
    'auth',
    'verified'
])->name('listJob');

Route::get('/view-job/{id}', ViewJob::class)->middleware([
    'auth',
    'verified'
])->name('viewJob');

Route::get('/house-b-l/{shipmentId}', HouseBL::class)
    ->middleware(['auth', 'verified'])
    ->name('house-b-l');

Route::get('pdfhbl/{shipmentId}', PdfHbl::class)->middleware(['auth', 'verified']);

Route::get('/journal-entries', JournalEntries::class)->middleware([
    'auth',
    'verified'
]);

Route::get('create-shipments', CreateShipments::class)->middleware([
    'auth',
    'verified'
]);

Route::get('shipmment', Shipmment::class)->middleware([
    'auth',
    'verified'
]);
Route::get('/view-shipments/{id}', ViewShipments::class)->middleware([
    'auth',
    'verified'
]);
Route::get('/edit-shipments/{id}', EditShipments::class)->middleware([
    'auth',
    'verified'
]);

Route::get('/view-customers/{id}', ViewCustomer::class)->middleware([
    'auth',
    'verified'
]);

Route::get('/edit-customers/{id}', EditCustomer::class)->middleware([
    'auth',
    'verified'
]);
Route::get('/customers/create', CreateCustomer::class)->middleware(['auth', 'verified'])->name('customers.create');

Route::get('/customers', ListCustomer::class)
    ->middleware(['auth', 'verified'])
    ->name('customers.list');

Route::get('users', Users::class)
    ->middleware(['auth', 'verified'])
    ->name('user.list');

Route::get('accountant', Accountant::class)
    ->middleware(['auth', 'verified'])
    ->name('accountant.list');

Route::get('/accounting/tranksaksi', Tranksaksi::class)
    ->middleware(['auth', 'verified'])
    ->name('Tranksaksi');

Route::get('/purchase-invoice/{shipmentId}', PurchaseInvoice::class)
    ->middleware(['auth', 'verified'])
    ->name('purchase-invoice');

Route::get('/sale-invoice/{shipmentId}', SaleInvoice::class)
    ->middleware(['auth', 'verified'])
    ->name('sale-invoice');
Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf-token');



require __DIR__ . '/auth.php';
