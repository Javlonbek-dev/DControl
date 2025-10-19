<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class GovControlByCompanyTypeChart extends ChartWidget
{
    protected static ?string $heading = 'Tekshiruvlar taqsimoti (kompaniya turi bo‘yicha)';
    protected int|string|array $columnSpan = 'part';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $row = DB::table('gov_controls as gc')
            ->leftJoin('orders as o', 'o.id', '=', 'gc.order_id')
            ->leftJoin('companies as c', 'c.id', '=', 'o.company_id')
            ->selectRaw("
                SUM(CASE WHEN c.is_business IN ('1','true','yes','biz','business') THEN 1 ELSE 0 END) AS business_cnt,
                SUM(CASE WHEN c.is_business IN ('0','false','no','gov','state','budget') THEN 1 ELSE 0 END) AS state_cnt,
                SUM(CASE WHEN c.id IS NULL THEN 1 ELSE 0 END) AS unknown_cnt
            ")
            ->first();

        $business = 35;
        $state    = 8;

        return [
            'labels' => ['Tadbirkorlik subyekti: '.$business, 'Davlat tashkiloti: '.$state],
            'datasets' => [
                [
                    'data' => [$business, $state],
                     'backgroundColor' => ['#22c55e', '#6366f1', '#9ca3af'],
                ],
            ],
        ];
    }
}
