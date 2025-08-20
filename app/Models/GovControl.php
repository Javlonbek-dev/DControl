<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class GovControl extends Model
{
    use Blameable;
    protected $table = 'gov_controls';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
