<?php

namespace Database\Seeders;

use App\CriterionType;
use App\Models\Criteria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $criteriaProduct = [
            'Saqlash sharoiti talablari buzilishi',
            'Davriy sinovlar o\'tkazilmaganligi',
            'Texnologik jarayon',
            'Sifatsiz',
            "476-bo'yicha Markirovka",
            "Markirovka",
            'Davlat tilida markirovkalanmagan',


        ];

        foreach ($criteriaProduct as $key => $value) {
            Criteria::create([
                'name' => $value,
                'type' => CriterionType::Product,
            ]);
            Criteria::create([
                'name' => $value,
                'type' => CriterionType::Service,
            ]);
        }
    }
}
