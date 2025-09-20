<?php

namespace App\Models;

use App\CriterionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Criteria extends Model
{
    protected $fillable = ['name', 'type'];
    protected $casts = [
        'type' => CriterionType::class, // enum cast (Laravel 9/10+)
    ];

    public function scopeForType(Builder $q, string|CriterionType $type): Builder
    {
        return $q->where('type', $type instanceof CriterionType ? $type->value : $type);
    }

    public function nonconformities()
    {
        return $this->belongsToMany(
            \App\Models\Nonconformity::class,
            'nonconformity_criterion',
            'criteria_id',
            'nonconformity_id'             // << to'g'ri: underscoresiz
        );
    }


}
