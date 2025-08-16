<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use Blameable;
    protected $guarded = [];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
