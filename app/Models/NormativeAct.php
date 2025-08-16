<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class NormativeAct extends Model
{
    use Blameable;
    protected $table = 'normative_acts';
    protected $primaryKey = 'id';
    protected $guarded = [];
}
