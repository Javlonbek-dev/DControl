<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class Services extends Model
{
    protected $guarded =[];
    public function gov_control()
    {
        return $this->belongsTo(GovControl::class);
    }


}
