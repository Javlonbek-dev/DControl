<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ogohlantirish extends Model
{
    use HasFactory;

    protected $table = 'ogohlantirish';
    protected $guarded = [];

    public function hudud()
    {
        return $this->belongsTo(Hudud::class, 'hudud_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
