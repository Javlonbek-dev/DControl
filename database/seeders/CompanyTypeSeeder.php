<?php

namespace Database\Seeders;

use App\Models\CompanyType;
use Illuminate\Database\Seeder;

class CompanyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companyTypes = [
            'Ishlab chiqarish',
            'Xizmat ko\'rsatish',
            'Tibbiyot',
            'Import',
            'Organ',
            'Savdo',
            'Metrologiya',
            'Avtoyo\'l',
            'Veterinariya',
            'Maktabgacha ta\'lim',
            'Laboratoriya',
            'SES',
            'Sanatoriya'
        ];

        foreach ($companyTypes as $companyType) {
            CompanyType::create([
                'name' => $companyType,
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}
