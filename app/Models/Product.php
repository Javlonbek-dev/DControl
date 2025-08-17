<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use Blameable;

    protected $guarded = [];
    public function gov_control()
    {
        return $this->belongsTo(GovControl::class, 'gov_control_id');
    }
}
