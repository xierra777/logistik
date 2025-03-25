<?php

namespace App\Exports;

use App\Models\shipment;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShipmentExport implements FromCollection, WithHeadings
{
    use Exportable;


    protected $start_date, $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        return Shipment::query()
            ->when($this->start_date && $this->end_date, function ($query) {
                $query->whereBetween('created_at', [$this->start_date, $this->end_date]);
            })
            ->get(['id', 'shipment_id', 'container_id', 'shipper', 'consignee', 'created_at']);
    }

    public function headings(): array
    {
        return [
            'ID',
            'B/L',
            'Container ID',
            'Shipper',
            'Consignee',
            'Created At',
        ];
    }
}
