<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use Blameable;

    protected $guarded = [];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function company_type()
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id');
    }

}
