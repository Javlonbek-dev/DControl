<?php

namespace App\Imports;

use App\Models\Hudud;
use App\Models\Ogohlantirish;
use App\Models\Profilaktika;
use App\Models\Region;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use function PHPUnit\Framework\isEmpty;

class ProfilaktikaImport implements ToModel, WithStartRow
{
    public function startRow():int
    {
        return 2;
    }

    private function excelDateToDate($value)
    {
        if(empty($value)) return null;

        if(is_numeric($value)){
            return Carbon::createFromDate(1900,1,1)->addDay($value-2)->format('Y-m-d');
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        }
        catch (\Exception $exception){
            return null;
        }
    }

    public function model(array $row)
    {
        $hududmap=[
            'Андижон вилояти' => 'Andijon viloyati',
            'Бухоро вилояти' => 'Buxoro viloyati',
            'Жиззах вилояти' => 'Jizzax viloyati',
            'Қашқадарё вилояти' => 'Qashqadaryo viloyati',
            'Қорақалпоғистон Республикаси' => 'Qoraqalpog‘iston Respublikasi',
            'Навоий вилояти' => 'Navoiy viloyati',
            'Наманган вилояти' => 'Namangan viloyati',
            'Самарқанд вилояти' => 'Samarqand viloyati',
            'Сирдарё вилояти' => 'Sirdaryo viloyati',
            'Сурхондарё вилояти' => 'Surxondaryo viloyati',
            'Тошкент вилояти' => 'Toshkent viloyati',
            'Тошкент шаҳри' => 'Toshkent shahri',
            'Фарғона вилояти' => 'Farg‘ona viloyati',
            'Хоразм вилояти' => 'Xorazm viloyati',
        ];

        $krillHudud = trim($row[1] ?? '');
        $lotinHudud = $hududmap[$krillHudud] ?? null;
        $hududId = null;
        if ($lotinHudud)
        {
            $hudud = Region::where('name', $lotinHudud)->first();
            $hududId = $hudud->id;
        }

        return new Profilaktika([
            'id'=>$row[0],
            'region_id' => $hududId,
            'korxona_nomi' => $row[2],
            'stir'=> $row[3],
            'mahsulot_nomi'=>$row[4],
            'soha_nomi'=>$row[5],
            'prof_sanasi' => $this->excelDateToDate($row[6]),
            'xat_raqami' => $row[7],
        ]);
    }
}
