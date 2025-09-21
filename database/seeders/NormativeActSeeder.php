<?php

namespace Database\Seeders;

use App\Models\NormativeAct;
use Illuminate\Database\Seeder;

class NormativeActSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $normativeActs = [
            'Texnik jihatdan tartibga solish to\'g\'risidagi qonun',
            'Standartlashtirish to\'g\'risidagi qonun',
            'Metralogiya to\'g\'risidagi qonun',
            'Iste\'molchilarni huquqini himoya qilish to\'g\'risidagi qonun',

        ];
        foreach ($normativeActs as $normativeAct) {
            NormativeAct::create([
                'name' => $normativeAct,
            ]);
        }
    }
}
