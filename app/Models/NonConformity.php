<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class NonConformity extends Model
{
    use Blameable;

    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function metrology_instrument()
    {
        return $this->belongsTo(MetrologyInstrument::class, 'metrology_instrument_id');
    }

    public function certificate()
    {
        return $this->belongsTo(Certificate::class, 'certificate_id');
    }

    public function normative_act()
    {
        return $this->belongsTo(NormativeAct::class, 'normative_act_id');
    }

    public function written_directive()
    {
        return $this->belongsTo(WrittenDirective::class, 'written_directive_id');
    }

    public function administrative_liability()
    {
        return $this->belongsTo(AdministrativeLiability::class, 'administrative_liability_id');
    }

    public function economic_sanction()
    {
        return $this->belongsTo(EconomicSanction::class, 'economic_sanction_id');
    }

    public function sanction()
    {
        return $this->belongsTo(SanctionPaymentRequest::class , 'sanction_payment_request_id');
    }
}
