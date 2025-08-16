<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $viloyatlar = [
            "Toshkent shahri",
            "Toshkent viloyati",
            "Andijon viloyati",
            "Farg‘ona viloyati",
            "Namangan viloyati",
            "Samarqand viloyati",
            "Buxoro viloyati",
            "Xorazm viloyati",
            "Navoiy viloyati",
            "Qashqadaryo viloyati",
            "Surxondaryo viloyati",
            "Jizzax viloyati",
            "Sirdaryo viloyati",
            "Qoraqalpog‘iston Respublikasi"
        ];

        foreach ($viloyatlar as $nomi) {
            Region::create(['name' => $nomi]);
        }
    }
}
