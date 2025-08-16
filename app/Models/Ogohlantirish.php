<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ogohlantirish extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function region()
    {
        return $this->belongsTo(Region::class, 'hudud_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
