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

    public function products() { return $this->hasMany(Product::class, 'gov_control_id'); }
    public function certificate() { return $this->hasMany(Certificate::class, 'gov_control_id'); }
    public function metrology_instrument() { return $this->hasMany(MetrologyInstrument::class, 'gov_control_id'); }
    public function service() { return $this->hasMany(Services::class, 'gov_control_id'); }


    public function getDeficiencyItemsAttribute()
    {
        return collect([
            $this->products->map(fn ($p) => [
                'type' => 'Mahsulot',
                'name' => $p->name,
            ]),
            $this->certificate->map(fn ($c) => [
                'type' => 'Sertifikat',
                'name' => $c->number ?? $c->name ?? '—',
            ]),
            $this->metrology_instrument->map(fn ($m) => [
                'type' => 'O‘lchov asbobi',
                'name' => $m->name,
            ]),
            $this->service->map(fn ($s) => [
                'type' => 'Xizmat',
                'name' => $s->name,
            ]),
        ])->flatten(1)->values();
    }

}
