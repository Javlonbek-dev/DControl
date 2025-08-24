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

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function order()
    {
        return $this->hasManyThrough(
            Order::class,
            GovControl::class,
            'id',          // GovControl primary key
            'id',          // Order primary key
            'id',          // AdministrativeLiability key
            'order_id'     // GovControl foreign key to Order
        );
    }
    public function orders()
    {
        return $this->hasManyThrough(
            Order::class,
            GovControl::class,
            'id',          // GovControl primary key
            'id',          // Order primary key
            'id',          // AdministrativeLiability key
            'order_id'     // GovControl foreign key to Order
        );
    }
    public function getPaidTotalAttribute(): float
    {
        return (float) $this->payments()->sum('payment_amount');
    }

    public function getRemainingAttribute(): float
    {
        return max(0, ((float) $this->imposed_fine) - $this->paid_total);
    }

    public function getIsFullyPaidAttribute(): bool
    {
        return $this->paid_total >= (float) $this->imposed_fine && $this->imposed_fine > 0;
    }

}
