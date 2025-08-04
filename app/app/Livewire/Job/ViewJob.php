<?php

namespace App\Livewire\Job;

use Livewire\Component;
use App\Models\TJob;
use App\Models\jobContainer;
use App\Models\shipmentContainers;
use App\Models\Transaction;
use App\Models\TShipments;
use Illuminate\Support\Facades\Auth;

class ViewJob extends Component
{
    public $editingJobId = false;
    public $job;
    public $refreshKey, $transactionId, $jobId;
    public $type_job = '';
    public array $selectedShipments = [];
    public $organizationFields = [];
    public array $selectedAssignedShipments = [];
    public $modalContainer = true;
    public $containerType, $noOfPackages, $containerReleaseNo, $containerReleaseDate, $typeOfPackages, $grossWeight, $typeOfGrossWeight, $volumeWeight, $typeOfVolumeWeight, $volume, $chargableWeight, $containerRemarks, $containerNo, $containerSealNo, $noOfPallet, $netOfWeight, $typeNetOfWeight, $totalWeight, $typeOfTotalWeight, $hsCode, $hsCodeDesc;
    public $editingTransactionId, $transactions;
    public $isEditing = false;

    protected $listeners = [
        'transactionSaved' => 'refreshJob',
        'close-modal' => 'closeEditTransaction',
    ];
    public function mount($id)
    {
        $this->loadJob($id); // cukup panggil method ini, tidak perlu cari ulang shipment ID

        $this->type_job = $this->job->type_job; // <-- Assign langsung dari relasi job
        $this->organizationFields = [
            'Client' => 'client',
            'Shipper' => 'shipper',
            'Consignee' => 'consignee',
            'Notify Party' => 'notify',
        ];
    }

    public function refreshJob()
    {
        $this->loadJob($this->job->id);
    }
    public function refreshTransaction($id)
    {
        $this->refreshKey = now()->timestamp;
        $this->loadJob($id); // cukup panggil method ini, tidak perlu cari ulang shipment ID
    }
    public function trancsationsDispatchclear()
    {
        $this->refreshKey = now()->timestamp;
    }


    public function loadJob($id)
    {
        $this->job = TJob::with([
            'client',
            'TjobContainer',
            'carrierModel',
            'ogents',
            'dagents',
            'employee',
        ])->findOrFail($id);
    }
    public function detachSelectedShipments()
    {
        if (empty($this->selectedAssignedShipments)) {
            return;
        }

        TShipments::whereIn('id', $this->selectedAssignedShipments)
            ->update(['id_job' => null]);
        shipmentContainers::whereIn('id_shipments', $this->selectedAssignedShipments)
            ->update(['id_jobContainer' => null]);
        $this->job->refresh();
        $this->selectedAssignedShipments = [];
        $this->dispatch('close-detach-assigned');
    }

    public function assignSelectedShipments()
    {
        if (empty($this->selectedShipments)) {
            return;
        }
        $jobContainer = JobContainer::firstOrCreate([
            'id_job' => $this->job->id,
        ]);
        TShipments::whereIn('id', $this->selectedShipments)
            ->update(['id_job' => $this->job->id]);
        $shipmentContainers = shipmentContainers::whereIn('id_shipments', $this->selectedShipments)->get();

        foreach ($shipmentContainers as $container) {
            $container->update([
                'id_jobContainer' => $jobContainer->id,
            ]);
        }
        $this->job->refresh();
        $this->selectedShipments = [];
        $this->dispatch('close-detach-shipment');
    }
    public function confirmDelete($get_id)
    {
        // dd('niggas');
        // Ambil transaksi beserta relasi invoice-nya
        $transaction = Transaction::with('invs')->find($get_id);

        if (!$transaction) {
            $this->dispatch('swal', [
                'title' => 'Gagal',
                'text' => 'Transaksi tidak ditemukan.',
                'icon' => 'error',
            ]);
            return;
        }

        // Kalau invoice-nya sudah issued, tolak penghapusan
        if ($transaction->invs && $transaction->invs->status === 'issued') {
            $this->dispatch('swal', [
                'title' => 'Tidak Bisa Dihapus',
                'text' => 'Transaksi ini sudah masuk ke invoice yang telah issued.',
                'icon' => 'error',
            ]);
            return;
        }

        // Kalau belum issued, lanjut hapus
        try {
            Transaction::destroy($get_id);
            $this->dispatch('swal', [
                'title' => 'Success!',
                'text' => 'Transaction Deleted',
                'icon' => 'success',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
    public function editTransaction($jobId, $transactionId)
    {
        $this->isEditing = true;
        $this->editingTransactionId = $transactionId;
        $this->editingJobId = $jobId;
    }
    public function closeEditTransaction()
    {
        $this->isEditing = false;
    }
    public function getAssignedShipmentsProperty()
    {
        return $this->job->shipments;
    }

    public function getOrphanShipmentsProperty()
    {
        return TShipments::whereNull('id_job')->get();
    }

    public function createContainer()
    {
        $container = [
            'containerType'       => $this->containerType,
            'containerReleaseNo'  => $this->containerReleaseNo,
            'containerNo'         => $this->containerNo,
            'containerReleaseDate' => $this->containerReleaseDate,
            'noOfPackages'        => $this->noOfPackages,
            'typeOfPackages'      => $this->typeOfPackages,
            'grossWeight'         => $this->grossWeight,
            'typeOfGrossWeight'   => $this->typeOfGrossWeight,
            'volumeWeight'        => $this->volumeWeight,
            'typeOfVolumeWeight'  => $this->typeOfVolumeWeight,
            'volume'              => $this->volume,
            'containerSealNo'     => $this->containerSealNo,
            'noOfPallet'          => $this->noOfPallet,
            'netOfWeight'         => $this->netOfWeight,
            'typeNetOfWeight'     => $this->typeNetOfWeight,
            'totalWeight'         => $this->totalWeight,
            'typeOfTotalWeight'   => $this->typeOfTotalWeight,
            'hsCode'              => $this->hsCode,
            'hsCodeDesc'          => $this->hsCodeDesc,
        ];

        jobContainer::create([
            'id_job' => $this->job->id,
            'containers' => $container,
            'created_by' => Auth::user()->id
        ]);
        $this->resetContainerFields();
        $this->dispatch('close-create-container');
    }
    public function resetContainerFields()
    {
        $this->reset([
            'containerType',
            'containerReleaseNo',
            'containerNo',
            'containerReleaseDate',
            'noOfPackages',
            'typeOfPackages',
            'grossWeight',
            'typeOfGrossWeight',
            'volumeWeight',
            'typeOfVolumeWeight',
            'volume',
            'chargableWeight',
            'containerRemarks',
            'containerSealNo',
            'noOfPallet',
            'netOfWeight',
            'typeNetOfWeight',
            'totalWeight',
            'typeOfTotalWeight',
            'hsCode',
            'hsCodeDesc',
        ]);
    }
    public function getOrganizationsProperty()
    {

        $carrierLabel = in_array($this->type_job, ['air_inbound', 'air_outbound']) ? 'Airlines' : 'Carrier';


        return collect([
            [
                'label' => 'Client',
                'data' => optional($this->job->client) ? (object)[
                    'id' => $this->job->client->id,
                    'name' => $this->job->client->name,
                    'email' => $this->job->client->email,
                    'contact' => $this->job->client->contact,
                    'address' => optional($this->job->client->addresses->first())->address,
                ] : null,
            ],
           [
    'label' => $carrierLabel,
    'data' => $this->job->carrierModel
        ? (object)[
            'id' => $this->job->carrierModel->id,
            'name' => $this->job->carrierModel->name,
            'email' => $this->job->carrierModel->email,
            'contact' => $this->job->carrierModel->contact,
            'address' => optional($this->job->carrierModel->addresses->first())->address,
        ]
        : (object)[
            'id' => null,
            'name' => null,
            'email' => null,
            'contact' => null,
            'address' => null,
        ],
],

        ])->filter(fn($item) => !is_null($item['data']));
    }

    public function render()
    {
        $this->transactions = Transaction::where('id_job', $this->job->id)
            ->get();
        return view('livewire.job.view-job',);
    }
}
