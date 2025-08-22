<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class SanctionPaymentRequest extends Model
{
    use Blameable;
    protected $guarded;

    public function non_conformity()
    {
        return $this->hasMany(NonConformity::class, 'sanction_payment_request_id', 'id');
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
}
