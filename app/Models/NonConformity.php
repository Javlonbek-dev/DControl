<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NonConformity extends Model
{
    use Blameable;
    protected $casts = [
        'normative_act_id' => 'array',
        'finalized_at'     => 'datetime',
    ];

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
    public function getNormativeActsNamesAttribute()
    {
        if (!$this->normative_act_id) {
            return null;
        }

        $ids = is_array($this->normative_act_id) ? $this->normative_act_id : json_decode($this->normative_act_id, true);

        return NormativeAct::whereIn('id', $ids)->pluck('name')->join(', ');
    }


    public function scopeHasNormativeAct($q, int $actId)
    {
        return $q->whereJsonContains('normative_act_id', $actId);
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

    public function service()
    {
        return $this->belongsTo(Services::class, 'service_id');
    }

    public function criteria()
    {
        return $this->belongsToMany(
            \App\Models\Criteria::class,   // yoki Criterion::class
            'nonconformity_criterion',     // pivot jadval nomi
            'nonconformity_id',            // << to'g'ri: current model FK (underscoresiz)
            'criteria_id'                 // related model FK
        );
    }

    protected $fillable = [
        'product_id', 'metrology_instrument_id', 'certificate_id', 'service_id',
        'normative_act_id', 'written_directive_id', 'administrative_liability_id',
        'economic_sanction_id', 'sanction_payment_request_id',
        'created_by', 'updated_by',
        'normative_documents',
        'final_description', 'finalized_at', 'finalized_by',
    ];


    public function findings(): HasMany
    {
        return $this->hasMany(NonConformityFinding::class);
    }

    public function finalizer()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }
    public function getSubjectTypeLabelAttribute(): string
    {
        return match ($this->choice) {
            'product'     => 'Mahsulot',
            'metrology'   => 'Metrologiya',
            'certificate' => 'Sertifikat',
            'service'     => 'Xizmat',
            default       => '—',
        };
    }

    public function getSubjectNameAttribute(): ?string
    {
        return match ($this->choice) {
            'product'     => $this->product->name ?? null,
            'service'     => $this->service->name ?? null,
            default       => null,
        };
    }
    public function isFinalized(): bool
    {
        return ! empty($this->finalized_at) || ! empty($this->final_description);
    }

    public function getCriteriaNamesAttribute(): array
    {
        return $this->criteria()->pluck('name')->all();
    }

}
