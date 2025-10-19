<?php

namespace App\Filament\Widgets;

use App\Models\GovControl;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class GovControlOverviewStats extends BaseWidget
{
    protected function getStats(): array
    {
        $total          = GovControl::count();
        $withProduct    = GovControl::whereHas('products')->count();
//        $withInstrument = GovControl::whereHas('metrology_instrument')->count();
//        $withCert       = GovControl::whereHas('certificate')->count();

        return [
            Stat::make('Jami tekshiruvlar', number_format($total)),
            Stat::make('Mahsulotli tekshiruvlar', number_format($withProduct))
                ->description(sprintf('Ulushi: %s%%', $total ? round($withProduct / $total * 100, 1) : 0)),
//            Stat::make('Metrologiya asbobli tekshiruvlar', number_format($withInstrument))
//                ->description(sprintf('Ulushi: %s%%', $total ? round($withInstrument / $total * 100, 1) : 0)),
//            Stat::make('Sertifikatli tekshiruvlar', number_format($withCert))
//                ->description(sprintf('Ulushi: %s%%', $total ? round($withCert / $total * 100, 1) : 0)),
        ];
    }
}
