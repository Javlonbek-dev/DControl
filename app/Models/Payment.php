<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, Blameable;

    protected $guarded;
    public function sanction()
    {
        return $this->belongsTo(SanctionPaymentRequest::class , 'sanction_id');
    }

    public function economic_sanction()
    {
        return $this->belongsTo(EconomicSanction::class , 'economic_sanction_id');
    }

    public function administrative_liability()
    {
        return $this->belongsTo(AdministrativeLiability::class , 'administrative_liability_id');
    }
}
