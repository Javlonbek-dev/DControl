<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;

class WrittenDirective extends Model
{
    protected $guarded = [];
    use Blameable;
}
