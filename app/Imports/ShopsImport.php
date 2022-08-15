<?php

namespace App\Imports;

use App\Models\Shop;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ShopsImport implements ToModel , WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Shop([
                            //
                            'id'    => $row[0],
                            'shop_name'    => $row[1],
                            'shop_id'    => $this->generateUniqueCode(),
                            'address'    => $row[2],
                            'distance_from_cps'    => $row[3],
                            'shop_dimentions'    => $row[4],
                            'stock_capacity_per_day'    => $row[5],
                            'max_sale_estimate_per_day'    => $row[6],
                            'estimated_start_date'    => $row[7],
                            'status'    => 'Active',
                            'supplier_id'    => $row[8],
                            'phone'    => $row[9],
        ]);
    }

    /**
     * @return int
    */
    public function startRow(): int
    {
        return 2;
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function generateUniqueCode()
    {
        do {
            $code = 'CPS-'.random_int(100000, 999999);
        } while (Shop::where("shop_id", "=", $code)->first());

        return $code;
    }
}
