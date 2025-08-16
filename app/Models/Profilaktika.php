<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profilaktika extends Model
{
    protected $guarded = [];

    protected $table = 'profilaktikas';

    public function region()
    {
        return $this->belongsTo(Region::class, 'hudud_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
