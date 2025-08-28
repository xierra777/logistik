<?php

use App\Livewire\CustomerOutstandingDebts;
use Illuminate\Support\Facades\Route;

use App\Livewire\Accounting\Accountant;
use App\Livewire\Accounting\ChartOfAccount\ChargeCoaSetting;
use App\Livewire\Accounting\ChartOfAccount\ChartOfAccountDetails;
use App\Livewire\Accounting\Payment\CreatePayment;
use App\Livewire\Accounting\Payment\PaymentTransaction;
use App\Livewire\Accounting\Payment\ViewPayment;
use App\Livewire\Accounting\PurchaseInvoice;
use App\Livewire\Accounting\Tranksaksi;
use App\Livewire\Accounting\SaleInvoice;
use App\Livewire\Accounting\Tax\CreateTax;
use App\Livewire\Accounting\Tax\ListTax;
use App\Livewire\Bank\CreateBank;
use App\Livewire\Bank\ListBank;
use App\Livewire\Bank\ViewBank;
use App\Livewire\JournalEntries;
use App\Livewire\Customers\CreateCustomer;
use App\Livewire\Customers\EditCustomer;
use App\Livewire\Customers\ViewCustomer;
use App\Livewire\Customers\ListCustomer;
use App\Livewire\Dashboard;
use App\Livewire\HouseBL;
use App\Livewire\Job\ContainerJob;
use App\Livewire\Pdfhbl;
use App\Livewire\Job\CreateJob;
use App\Livewire\Job\EditJob;
use App\Livewire\Job\Invoice\JobSaleInvoice;
use App\Livewire\Job\JobCreateShipment;
use App\Livewire\Job\ListJob;
use App\Livewire\Job\ViewJob;
use App\Livewire\Shipment\ContainerShipment;
use App\Livewire\Shipment\CreateShipment;
use App\Livewire\Shipment\EditShipment;
use App\Livewire\Shipment\ListShipment;
use App\Livewire\Shipment\ViewShipment;
use App\Livewire\Users\UserList;
use App\Livewire\Users\UserView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Livewire\Job\Invoice\PurchaseInvoice as jobPurchaseInvoice;
use App\Livewire\Shipment\Invoice\ShipmentPurchaseInvoice;
use App\Livewire\Shipment\Invoice\ShipmentSaleInvoice;

Route::redirect('/', '/login');
Route::get('/dashboard', Dashboard::class)->middleware([
    'auth',
    'verified'
])->name('dashboard');
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('customer-outstanding-debts', CustomerOutstandingDebts::class)->middleware('auth')->name('customerDebt');
Route::view('shipments', 'shipments.index',)
    ->middleware(['auth', 'verified'])
    ->name('shipments');
Route::view('shipment', 'shipments.create',)
    ->middleware(['auth'])
    ->name('shipment');
Route::get('/data/airports-ajax', function () {
    $token = 'dfa43c42-594a-44ed-8752-0909a8dfba7e';
    $search = request('q', '');
    $page = request('page', 1);
    $size = 5;

    $normalized = strtolower(substr($search, 0, 3)); // ambil prefix 3 huruf
    $cacheKey = "aviowiki-airports:{$normalized}:page:{$page}";


    $data = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($token, $search, $page, $size) {
        $response = Http::withToken($token)->get('https://api.aviowiki.com/free/airports/search', [
            'query' => $search,
            'page' => $page - 1,
            'size' => $size,
        ]);

        if (!$response->successful()) return null;

        return $response->json();
    });

    if (!$data) {
        return response()->json([
            'results' => [],
            'pagination' => ['more' => false],
        ]);
    }

    return response()->json([
        'results' => collect($data['content'])->filter(fn($airport) => !empty($airport['iata']))->map(function ($airport) {
            return [
                'id' => strtoupper("{$airport['name']}, {$airport['country']['name']}"),
                'text' => strtoupper("{$airport['name']} ({$airport['iata']})" .
                    (!empty($airport['city']) ? " - {$airport['city']}" : '') .
                    " - {$airport['country']['name']}")
            ];
        })->values(), // important!
        'pagination' => [
            'more' => $data['page']['number'] + 1 < $data['page']['totalPages']
        ],
    ]);
});

// Route Shipment
Route::get('create-shipment', CreateShipment::class)->middleware([
    'auth',
    'verified'
])->name('create-shipment');
Route::get('update-shipment/{id}', EditShipment::class)->middleware([
    'auth',
    'verified'
])->name('updateShipment');

Route::get('list-shipment', ListShipment::class)->middleware([
    'auth',
    'verified'
])->name('listShipment');
Route::get('view-shipment/{id}', ViewShipment::class)->middleware([
    'auth',
    'verified'
])->name('viewShipment');
Route::get('view-shipment/{id}/container-shipment/{container_id}', ContainerShipment::class)
    ->middleware(['auth', 'verified'])
    ->name('shipment.container');


Route::get('/purchase-invoice/{shipmentId}', ShipmentPurchaseInvoice::class)
    ->middleware(['auth', 'verified'])
    ->name('purchaseInvoice');

Route::get('/sale-invoice/{shipmentId}', ShipmentSaleInvoice::class)
    ->middleware(['auth', 'verified'])
    ->name('saleInvoice');
// End Shipment Route

// Route Job Route
Route::get('create-job', CreateJob::class)->middleware([
    'auth',
    'verified'
])->name('Createjob');
Route::get('edit-job/{id}', EditJob::class)->middleware([
    'auth',
    'verified'
])->name('EditJob');

Route::get('list-job', ListJob::class)->middleware([
    'auth',
    'verified'
])->name('listJob');

Route::get('view-job/{id}', ViewJob::class)->middleware([
    'auth',
    'verified'
])->name('viewJob');
Route::get('view-job/{id}/container-job/{jobContainer_id}', ContainerJob::class)
    ->middleware(['auth', 'verified'])
    ->name('jobContainer');

Route::get('view-job/{id}/Create-Shipment', JobCreateShipment::class)
    ->middleware(['auth', 'verified'])
    ->name('viewJobCreateShipment');


Route::get('jobpurchase-invoice/{jobId}', jobPurchaseInvoice::class)
    ->middleware(['auth', 'verified'])
    ->name('jobPurchaseInvoice');

Route::get('jobsale-invoice/{jobId}', JobSaleInvoice::class)
    ->middleware(['auth', 'verified'])
    ->name('jobSaleInvoice');

// End Job Route



// Users route
Route::get('user', UserList::class)
    ->middleware(['auth', 'verified'])
    ->name('userList');
Route::get('user-view/{id}', UserView::class)
    ->middleware(['auth', 'verified'])
    ->name('userView');
// End Users Route


Route::get('/house-b-l/{shipmentId}', HouseBL::class)
    ->middleware(['auth', 'verified'])
    ->name('house-b-l');

Route::get('pdfhbl/{shipmentId}', PdfHbl::class)->middleware(['auth', 'verified']);

Route::get('/journal-entries', JournalEntries::class)->middleware([
    'auth',
    'verified'
]);
Route::get('/list-tax', ListTax::class)->middleware([
    'auth',
    'verified'
])->name('listTax');

Route::get('/view-customers/{id}', ViewCustomer::class)->middleware([
    'auth',
    'verified'
])->name('viewCust');

Route::get('/edit-customers/{id}', EditCustomer::class)->middleware([
    'auth',
    'verified'
])->name('editCust');
Route::get('/customers/create', CreateCustomer::class)->middleware(['auth', 'verified'])->name('createCust');


Route::get('/customers', ListCustomer::class)
    ->middleware(['auth', 'verified'])
    ->name('listCust');


Route::get('accountant', Accountant::class)
    ->middleware(['auth', 'verified'])
    ->name('accountant.list');

Route::get('/accounting/tranksaksi', Tranksaksi::class)
    ->middleware(['auth', 'verified'])
    ->name('Tranksaksi');


Route::get('charge-coa', ChargeCoaSetting::class)
    ->middleware(['auth', 'verified'])
    ->name('coaSetting');
Route::get('chart-of-accont', ChartOfAccountDetails::class)
    ->middleware(['auth', 'verified'])
    ->name('chartOfAccount');

// Payment
Route::get('paymentTransaction', PaymentTransaction::class)
    ->middleware(['auth', 'verified'])
    ->name('paymentTrans');

Route::get('createPayment', CreatePayment::class)
    ->middleware(['auth', 'verified'])
    ->name('createPay');

Route::get('viewPayment/{payId}', ViewPayment::class)
    ->middleware(['auth', 'verified'])
    ->name('viewPay');
// End Payment

Route::get('list-bank', ListBank::class)
    ->middleware(['auth', 'verified'])
    ->name('listBank');
Route::get('create-bank', CreateBank::class)
    ->middleware(['auth', 'verified'])
    ->name('createBank');
Route::get('view-bank/{id}', ViewBank::class)
    ->middleware(['auth', 'verified'])
    ->name('viewBank');


Route::get('/csrf-token', function (Request $request) {
    return response()->json(['csrf_token' => csrf_token()]);
})->name('csrf-token');



require __DIR__ . '/auth.php';
