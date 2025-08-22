<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class EconomicSanction extends Model
{
    use Blameable;

    protected $guarded;

    public function decision_type()
    {
        return $this->belongsTo(DecisionType::class, 'decision_type_id');
    }

    public function sanction()
    {
        return $this->belongsTo(SanctionPaymentRequest::class, 'sanction_id');
    }

    public function non_conformity()
    {
        return $this->hasMany(NonConformity::class, 'economic_sanction_id');
    }

}
