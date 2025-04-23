<?php

namespace App\Imports;

use App\Models\Shipment;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class ShipmentImport implements ToModel, WithHeadingRow, WithValidation
{

    /**
     * Aturan validasi untuk tiap baris data.
     */
    public function rules(): array
    {
        return [
            'bl'                  => 'required|unique:shipments,shipment_id',
            'job'                 => 'required|unique:shipments,shipment_no',
            'shipper'             => 'required',
            'notify'              => 'required',
            'ocean_vessel_mother' => 'required',
            'port_of_discharge'   => 'required',
            'place_of_receipt'    => 'required',
            'port_of_loading'     => 'required',

        ];
    }

    /**
     * Mapping tiap baris data ke model Shipment.
     */
    public function model(array $row)
    {
        // Convert Excel dates to Carbon instances
        $estimearrival = null;
        $estimedelivery = null;

        if (!empty($row['estimearrival']) && is_numeric($row['estimearrival'])) {
            $estimearrival = ExcelDate::excelToDateTimeObject((float) $row['estimearrival']);
        }

        if (!empty($row['estimedelivery']) && is_numeric($row['estimedelivery'])) {
            $estimedelivery = ExcelDate::excelToDateTimeObject((float) $row['estimedelivery']);
        }

        return new Shipment([
            'shipment_id'         => $row['bl'] ?? null,
            'shipment_no'         => $row['job'] ?? null,
            'shipper'             => $row['shipper'] ?? null,
            'consignee'           => $row['consignee'] ?? null,
            'notify'              => $row['notify'] ?? null,
            'ocean_vessel_feeder' => $row['ocean_vessel_feeder'] ?? null,
            'ocean_vessel_mother' => $row['ocean_vessel_mother'] ?? null,
            'port_of_discharge'   => $row['port_of_discharge'] ?? null,
            'place_of_receipt'    => $row['place_of_receipt'] ?? null,
            'port_of_loading'     => $row['port_of_loading'] ?? null,
            'estimearrival'       => $estimearrival ? $estimearrival->format('Y-m-d H:i:s') : null,
            'estimedelivery'      => $estimedelivery ? $estimedelivery->format('Y-m-d H:i:s') : null,
        ]);
    }
    public function customValidationMessages()
    {
        return [
            'bl.required'      => 'The BL field is required',
            'job.required'     => 'The Job field is required',
            'shipper.required' => 'The Shipper field is required',
            // ... add more custom messages as needed
        ];
    }
}
