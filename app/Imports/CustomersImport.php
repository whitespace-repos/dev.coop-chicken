<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CustomersImport implements ToModel , WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
                                //
                                'id'    => $row[0],
                                'name'    => $row[1],
                                'email'    => $row[2],
                                'phone'    => $row[3],
                                'location'    => $row[4]
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
