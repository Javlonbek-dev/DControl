<?php
//
//namespace App\Filament\Widgets;
//
//use App\Models\Certificate;
//use App\Models\MetrologyInstrument;
//use App\Models\Product;
//use Filament\Widgets\ChartWidget;
//
//class GovControlChart extends ChartWidget
//{
//    protected static ?string $heading = 'Nazorat obyektlari ulushi';
//
//    protected function getType(): string
//    {
//        return 'doughnut'; // yoki 'doughnut'
//    }
//
//    protected function getData(): array
//    {
//        $productsCount = Product::count();
//        $instrumentsCount = MetrologyInstrument::count();
//        $certificatesCount = Certificate::count();
//
//        return [
//            'labels' => ['Mahsulotlar:'.$productsCount, 'Metrologiya asboblari:'. $instrumentsCount, 'Sertifikatlar:'.$certificatesCount],
//            'datasets' => [
//                [
//                    'data' => [
//                        $productsCount,
//                        $instrumentsCount,
//                        $certificatesCount,
//                    ],
//                    // Ranglarni o'zing belgilasang ham bo‘ladi:
//                    'backgroundColor' => ['#3b82f6', '#10b981', '#f59e0b'],
//                ],
//            ],
//        ];
//    }
//}
