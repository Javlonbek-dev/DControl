<?php

namespace App\Exports;

use App\Models\Ogohlantirish;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class OgohlantirishExport implements FromCollection,  WithHeadings
{
    /**
    */
    public function collection()
    {
        return DB::table('ogohlantirish')
            ->leftJoin('regions', 'ogohlantirish.region_id', '=', 'regions.id')
            ->leftJoin('users', 'ogohlantirish.user_id', '=', 'users.id')
            ->select(
                'ogohlantirish.id',
                'regions.name',
                'ogohlantirish.stir',
                'ogohlantirish.korxona_nomi',
                'ogohlantirish.mahsulot_nomi',
                'ogohlantirish.soha_nomi',
                'ogohlantirish.faoliyat_turi',
                'ogohlantirish.metralogiya',
                'ogohlantirish.standart',
                'ogohlantirish.sertifikat',
                'ogohlantirish.ogohlantirish_xati_sanasi',
                'ogohlantirish.ogohlantirish_xati_raqami',
                'ogohlantirish.javob_sanasi',
                'ogohlantirish.javob_raqami',
                'users.name as user_name'
            )
            ->get();
    }
    public function headings(): array
    {
        return [
           [ 'ID',
            'STIR',
            'Korxona nomi',
            'Mahsulot nomi',
            'Soha nomi',
            'Faoliyat turi',
            'Metrologiya',
            'Standart',
            'Sertifikat',
            'Ogohlantirish xati sanasi',
            'Ogohlantirish xati raqami',
            'Javob sanasi',
            'Javob raqami',
            'Foydalanuvchi ID',
            'Hudud ID',],
            [
                    'ID',
                    'STIR',
                    'Korxona nomi',
                    'Soha nomi',
                    'Faoliyat turi',
                    'Metrologiya',
                    'Standart',
                    'Sertifikat',
                    'Sana',
                    'Raqami',
                    'Sana2',
                    'Raqami / izoh',
                    'Foydalanuvchi ID',
                    'Hudud ID',
                ]
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                // Merge parent headings (1st row)
                $sheet->mergeCells('D1:E1'); // Soha ma'lumotlari
                $sheet->mergeCells('F1:H1'); // Kamchilik turi
                $sheet->mergeCells('I1:J1'); // Ogohlantirish xati
                $sheet->mergeCells('K1:L1'); // Javob xati

                // Mark remaining single headers to look like merged ones
                $sheet->mergeCells('A1:A2');
                $sheet->mergeCells('B1:B2');
                $sheet->mergeCells('C1:C2');
                $sheet->mergeCells('M1:M2');
                $sheet->mergeCells('N1:N2');

                // Optional: Make text bold and centered
                $sheet->getStyle('A1:N1')->getFont()->setBold(true);
                $sheet->getStyle('A1:N2')->getAlignment()->setHorizontal('center');
                $sheet->getStyle('A1:N2')->getAlignment()->setVertical('center');
            },
        ];
    }

}
