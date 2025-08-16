<?php

namespace App\Imports;

use App\Models\Ogohlantirish;
use App\Models\Ogohtantirish;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OgohlantirishImport implements ToModel, WithStartRow
{
    public function startRow(): int
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

        $hududMap = [
            'Андижон вилояти' => 'Andijon viloyati',
            'Бухоро вилояти' => 'Buxoro viloyati',
            'Жиззах вилояти' => 'Jizzax viloyati',
            'Қашқадарё вилояти' => 'Qashqadaryo viloyati',
            'Қорақолпоғистон Республикаси' => 'Qoraqalpog‘iston Respublikasi',
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

        $kirillHudud = trim($row[1] ?? '');
        $lotinHudud =    $hududMap[$kirillHudud] ?? null;

        $hududId = null;
        if ($lotinHudud) {
            $hudud = \App\Models\Region::where('name', $lotinHudud)->first();
            $hududId = $hudud?->id;
        }


        $normalizedUserMap = [
            'A.Mo’sajonov'      => "A.Mo'sajonov",
            'A.Toshqulov'       => 'A.Toshqulov',
            'B.Ibodullayev'     => 'B.Ibodullayev',
            'F.Latipov'         => 'F.Latipov',
            'F.Nametjanov'      => 'F.Nametjanov',
            'F.Xakimova'        => 'F.Xakimova',
            "M.Bo'riyev"        => "M.Bo'riyev",
            'Sh.Mirshohidov'    => 'Sh.Mirshohidov',
            'А,Тожибаев'        => 'A.Tadjibayev',
            'А.Бўронов'         => 'A.Buronov',
            'А.Марасулов'       => 'A.Marasulov',
            'А.Мўсажонов'       => "A.Mo'sajonov",
            'А.Остонаев'        => 'A.Ostonaev',
            'А.Пердебаев'       => 'A.Perdebayev',
            'А.Рахимов'         => 'A.Rahimov',
            'А.Таджибаев'       => 'A.Tadjibayev',
            'А.Тожибаев'        => 'A.Tadjibayev',
            'А.Хасанов'         => 'A.Khasanov',
            'А.Худайбердиев'    => 'A.Khudayberdiyev',
            'Б.Ибодуллаев'      => 'B.Ibodullayev',
            'Б.Худайбергенов'   => 'B.Khudaybergenov',
            'Г.Мусаева'         => 'G.Musaeva',
            'Д.Алимджанов'      => 'D.Alimjanov',
            'Д.Каримов'         => 'D.Karimov',
            'Ж. Отаёров'        => 'J.Otayorov',
            'Ж.Тухтаев'         => 'J.Tukhtaev',
            'З. Маматкулов'     => 'Z.Mamatqulov',
            'З.Жумаев'          => 'Z.Jumaev',
            'И.Негматулаев'     => 'I.Negmatullaev',
            'Ибодуллаев'        => 'Ibodullayev',
            'М.Бўриев'          => 'M.Buriyev',
            'М.Нурбоев'         => 'M.Nurboev',
            'М.Хафизов'         => 'M.Khafizov',
            'М.Холматов'        => 'M.Kholmatov',
            'Н.Хикматов'        => 'N.Hikmatov',
            'Н.Юлдашев'         => 'N.Yuldashev',
            'П. Шавкатов'       => 'P.Shavkatov',
            'Р.Маджиханов'      => 'R.Madjikhanov',
            'С.Васитов'         => 'S.Vasitov',
            'С.Очилов'          => 'S.Ochilov',
            'Тухтаев'           => 'Tukhtaev',
            'У.Тажибаев'        => 'U.Tadjibayev',
            'Ў.Тажибаев'        => "O'.Tadjibayev",
            'У. Эргашова'       => 'U.Ergashova',
            'У.Эргашева'        => 'U.Ergasheva',
            'Ф.Қаҳҳоров'        => 'F.Qahhorov',
            'Ф.Латипов'         => 'F.Latipov',
            'Х.Эшкараев'        => 'Kh.Eshkaraev',
            'Ш.Атаев'           => 'Sh.Ataev',
        ];

        $fish = trim($row[14] ?? '');
        $lotinName= $normalizedUserMap[$fish] ?? null;

        $userId = null;

        if ($lotinName) {
            $user = \App\Models\User::where('name', $lotinName)->first();
            $userId = $user?->id;
        }


        return new Ogohlantirish([
            'id'=>$row[0],
            'region_id'=>$hududId,
            'stir'=>$row[2],
            'korxona_nomi'=>$row[3],
            'mahsulot_nomi'=>$row[4],
            'soha_nomi'=>$row[5],
            'faoliyat_turi'=>$row[6],
            'metralogiya'=>$row[7],
            'standart'=>$row[8],
            'sertifikat'=>$row[9],
            'ogohlantirish_xati_sanasi'=>$this->excelDateToDate($row[10]),
            'ogohlantirish_xati_raqami'=>$row[11],
            'javob_sanasi'=>$this->excelDateToDate($row[12]),
            'javob_raqami'=>$row[13],
            'user_id'=>$userId,
        ]);
    }
}
