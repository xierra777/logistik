<?php

namespace App\Exports;

use App\Models\Shipment;
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
            ->get([
                'shipment_no',
                'shipment_id',
                'shipper',
                'consignee',
                'notify',
                'estimearrival',
                'estimedelivery',
                'ocean_vessel_mother',
                'ocean_vessel_feeder',
                'port_of_discharge',
                'place_of_receipt',
                'port_of_loading',
            ]);
    }

    public function headings(): array
    {
        return [
            'Job No',
            'B/L',
            'Shipper',
            'Consignee',
            'notify',
            'ETA',
            'ETD',
            'Mother Vessel ',
            'Feeder Vessel',
            'Port Of Discharge',
            'Place Of Receipt',
            'Port of Loading',
        ];
    }
}
