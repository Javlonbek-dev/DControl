<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class AdministrativeLiability extends Model
{
    use Blameable;
    protected $guarded;

    public function decision_type()
    {
        return $this->belongsTo(DecisionType::class, 'decision_type_id');
    }

    public function bxm()
    {
        return $this->belongsTo(Bxm::class, 'bxm_id');
    }

    public function profession()
    {
        return $this->belongsTo(Profession::class, 'profession_id');
    }

    public function non_conformity()
    {
        return $this->hasMany(NonConformity::class, 'administrative_liability_id', 'id');
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

    public function payments()
    {
        return $this->hasMany(Payment::class, 'administrative_liability_id', 'id');
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
