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

    public function gov_control()
    {
        return $this->belongsTo(GovControl::class, 'gov_control_id');
    }
}
