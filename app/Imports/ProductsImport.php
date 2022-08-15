<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToModel , WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
                                //
                                'id'    => $row[0],
                                'product_name'    => $row[1],
                                'supplier_id'    => $row[2],
                                'weight_unit'    => $row[3],
                                'stock'    => $row[4],
                                'conversion_rate'    => $row[5],
                                'parent_product_id'    => $row[6],
                                'wholesale_weight_range'    => $row[7],

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
