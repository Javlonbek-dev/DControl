<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdministrativeCode extends Model
{
    protected  $table = 'administrative_codes';
    protected $guarded = [];

    public function normative_act():BelongsTo
    {
        return $this->belongsTo(NormativeAct::class, 'normative_act_id');
    }

}
