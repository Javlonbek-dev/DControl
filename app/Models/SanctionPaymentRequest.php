<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class SanctionPaymentRequest extends Model
{
    use Blameable;
    protected $guarded;
}
