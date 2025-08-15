<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talabnoma extends Model
{
    protected $fillable = [
        'korxona_nomi','inn','faoliyat_turi','hudud_id','tuman',
        'start_tekshiruv','end_tekshiruv','yuborilgan_vaqti',
        'talabnoma_raq','jarima_sum','jarima_foizi','tekshiruv_holati',
        'tulangan_sum','tulangan_foizi','end_date','huquqbuzarlik_mazmuni',
        'qounun_moddasi','user_id',
    ];

    public function hudud()
    {
        return $this->belongsTo(Hudud::class, 'hudud_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
