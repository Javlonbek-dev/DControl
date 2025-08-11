<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profilaktika extends Model
{
    protected $guarded = [];

    protected $table = 'profilaktikas';

    public function hudud()
    {
        return $this->belongsTo(Hudud::class, 'hudud_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
