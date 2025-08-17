<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use Blameable;

    protected $guarded = [];
    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }
}
