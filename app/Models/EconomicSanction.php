<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class EconomicSanction extends Model
{
    use Blameable;

    protected $guarded;

    public function court()
    {
        return $this->belongsTo(Court::class, 'court_id');
    }

    public function decision_type()
    {
        return $this->belongsTo(DecisionType::class, 'decision_type_id');
    }

    public function sanction()
    {
        return $this->belongsTo(SanctionPaymentRequest::class, 'sanction_payment_request_id');
    }

}
