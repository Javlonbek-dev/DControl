<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory, Blameable;
    protected $guarded = [];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    public function scopeBusiness($q)
    {
        return $q->whereIn('is_business', ['1','true']);
    }

    public function scopeState($q)
    {
        return $q->whereIn('is_business', ['0','false']);
    }
}
