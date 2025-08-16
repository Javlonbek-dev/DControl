<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    use Blameable;
    protected $guarded;
    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
