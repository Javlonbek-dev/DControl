<?php

namespace App\Exports;

use App\Models\Profilaktika;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProfilaktikaExport implements FromCollection
{

    public function collection()
    {
        return Db::table('profilaktikas')
            ->leftJoin('regions', 'profilaktikas.region_id', '=', 'regions.id')
            ->leftJoin('users', 'profilaktikas.user_id', '=', 'users.id')
            ->select(
                'profilaktikas.id',
                'regions.name',
                'profilaktikas.stir',
                'profilaktikas.korxona_nomi',
                'profilaktikas.soha_nomi',
                'profilaktikas.prof_sanasi',
                'profilaktikas.xat_raqami',
                'users.name'
            )->get();
    }
}
