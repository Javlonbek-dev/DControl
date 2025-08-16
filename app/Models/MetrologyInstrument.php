<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class MetrologyInstrument extends Model
{
    use Blameable;
    public function gov_control()
    {
        return $this->belongsTo(GovControl::class, 'gov_control_id');
    }
}
