<?php

namespace App\Exports;

use App\Models\Shipment;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class ShipmentExport implements FromQuery, WithHeadings, WithMapping, WithChunkReading
{
    use Exportable;

    public function __construct(
        protected ?string $start   = null,
        protected ?string $end     = null,
        protected string $search = '',
        protected string $searchField = 'shipment_id',
    ) {}

    public function query()
    {
        $allowedFields = ['shipment_id', 'shipper', 'consignee', 'notify']; // tambahkan sesuai kebutuhan

        return Shipment::query()
            ->when(
                $this->start && $this->end,
                fn($q) =>
                $q->whereBetween('created_at', [$this->start, $this->end])
            )
            ->when(
                $this->search !== '' &&
                    in_array($this->searchField, $allowedFields),
                fn($q) =>
                $q->where($this->searchField, 'like', "%{$this->search}%")
            );
    }

    public function headings(): array
    {
        return [
            'Job No',
            'B/L',
            'Shipper',
            'Consignee',
            'Notify',
            'ETA',
            'ETD',
            'Mother Vessel',
            'Feeder Vessel',
            'Port Of Discharge',
            'Place Of Receipt',
            'Port Of Loading',
        ];
    }

    public function map($shipment): array
    {
        return [
            $shipment->shipment_no,
            $shipment->shipment_id,
            $shipment->shipper,
            $shipment->consignee,
            $shipment->notify,
            optional($shipment->estimearrival)->format('Y-m-d'),
            optional($shipment->estimedelivery)->format('Y-m-d'),
            $shipment->ocean_vessel_mother,
            $shipment->ocean_vessel_feeder,
            $shipment->port_of_discharge,
            $shipment->place_of_receipt,
            $shipment->port_of_loading,
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
