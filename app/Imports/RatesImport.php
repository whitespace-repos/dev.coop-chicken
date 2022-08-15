<?php

namespace App\Imports;

use App\Models\Rate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use \Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class RatesImport implements ToModel , WithStartRow , WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Rate([
                            //
                            'id'    => $row[0],
                            'date'    => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[1]),
                            'product_id'    => $row[2],
                            'whole_sale_rate'    => $row[3],
                            'retail_rate'    => $row[4],
                            'type'          => 'Regular',
                            'status'          => 'Active',
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
