<?php

namespace App\Imports;

use App\Models\StockRequestedProduct;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ProductRequestProductImport implements ToModel , WithStartRow , WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new StockRequestedProduct([
                                                //
                                                'date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0]),
                                                'product_id'    => $row[1],
                                                'stock_request_id'    => $row[2],
                                                'stock_request'    => $row[3],
                                                'current_stock'    => $row[4],
                                                'status'    => "Completed",
                                                'supply_rate'    => $row[5],
                                                'stock_sent'    => $row[6],
                                                'stock_received'    => $row[7]
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
