<?php

namespace App\Imports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class SalesImport implements ToModel , WithStartRow , WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Sale([
                            //
                            'id'    => $row[0],
                            'date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1]),
                            'total'    => $row[2],
                            'receive'    => $row[3],
                            'quantity'    => $row[4],
                            'sold_by'    => $row[5],
                            'shop_id'    => $row[6],
                            'product_id'    => $row[7],
                            'customer_id'    => $row[8]
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
