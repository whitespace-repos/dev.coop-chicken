<?php

namespace App\Imports;

use App\Models\StockRequest;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;


class StockRequestImport implements ToModel , WithStartRow , WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new StockRequest([
            //
            'id' => $row[0],
            'shop_id'    => $row[1],
            'date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[2]),
            'payment_method'    => $row[3],
            'status'    => $row[4],
            'supply_rate'    => $row[5],
            'total_stock_sent'    => $row[6],
            'total_stock_received'    => $row[7],
            'total_stock_wastage'    => $row[8],
            'actual_payment'    => $row[9],
            'payment_received'    => $row[10],
            'type'     =>  'Direct',
            'stock_requested_by' => auth()->id()
        ]);
    }

    /**
     * @return int
    */
    public function startRow(): int
    {
        return 2;
    }
}
