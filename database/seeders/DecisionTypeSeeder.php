<?php

namespace Database\Seeders;

use App\Models\DecisionType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DecisionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $decisionTypes = [
          'Undirilsin',
          'Ogohlantirilsin'
        ];
        foreach ($decisionTypes as $decisionType) {
            DecisionType::create([
                'name' => $decisionType,
            ]);
        }
    }
}
