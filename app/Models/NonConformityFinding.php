<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NonConformityFinding extends Model
{
    protected $fillable = [
        'non_conformity_id', 'detected_at', 'day_no', 'description', 'created_by',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->detected_at)) {
                $model->detected_at = now()->toDateString();
            }
            if (empty($model->created_by) && auth()->id()) {
                $model->created_by = auth()->id();
            }
        });
    }

    public function nonConformity()
    {
        return $this->belongsTo(NonConformity::class);
    }
}
