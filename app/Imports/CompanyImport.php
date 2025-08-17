<?php

namespace App\Imports;

use App\Models\Company;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class CompanyImport implements WithStartRow, ToModel
{
    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
        return new Company([
            'name' => $row[0],
            'stir' => $row[1],
            'district_id' => $row[2],
            'is_business' => $row[3],
            'created_by' => 1,
            'updated_by' => 1,
        ]);
    }
}
