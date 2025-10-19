<?php

//namespace App\Filament\Widgets;
//
//use Filament\Widgets\ChartWidget;
//use Illuminate\Support\Facades\DB;
//
//class AdministrativeLiabilityChart extends ChartWidget
//{
//    protected static ?string $heading = "Jarimalar";
//    protected int|string|array $columnSpan = 'full';
//    protected static ?string $maxHeight = '360px'; // ba'zida 0px bo'lib qolmasin
//
//    protected function getType(): string
//    {
//        return 'bar';
//    }
//
//    protected function getData(): array
//    {
//        // Payments agregati
//        $paymentsAgg = DB::table('payments')
//            ->select('administrative_liability_id', DB::raw('SUM(COALESCE(payment_amount,0)) AS paid_sum'))
//            ->groupBy('administrative_liability_id');
//
//        $row = DB::table('administrative_liabilities as al')
//            ->leftJoinSub($paymentsAgg, 'p', 'p.administrative_liability_id', '=', 'al.id')
//            ->selectRaw("
//                COUNT(*) AS total,
//                COUNT(al.decision_type_id) AS decision_made,
//                SUM(CASE WHEN COALESCE(p.paid_sum,0) >= COALESCE(al.imposed_fine,0) AND COALESCE(al.imposed_fine,0) > 0 THEN 1 ELSE 0 END) AS fully_paid_count,
//                SUM(CASE WHEN al.decision_type_id = 2 THEN 1 ELSE 0 END) AS warning_count,
//
//                SUM(COALESCE(al.imposed_fine,0)) AS total_amount,
//                SUM(CASE WHEN al.decision_type_id IS NOT NULL THEN COALESCE(al.imposed_fine,0) ELSE 0 END) AS decision_amount,
//                SUM(LEAST(COALESCE(p.paid_sum,0), COALESCE(al.imposed_fine,0))) AS paid_amount,
//                SUM(CASE WHEN al.decision_type_id = 2 THEN COALESCE(al.imposed_fine,0) ELSE 0 END) AS warning_amount
//            ")
//            ->first();
//
//        $total         = (int) ($row->total ?? 0);
//        $decisionMade  = (int) ($row->decision_made ?? 0);
//        $fullyPaid     = (int) ($row->fully_paid_count ?? 0);
//        $warning       = (int) ($row->warning_count ?? 0);
//
//        $totalAmount    = (float) ($row->total_amount ?? 0) / 1_000_000;
//        $decisionAmount = (float) ($row->decision_amount ?? 0) / 1_000_000;
//        $paidAmount     = (float) ($row->paid_amount ?? 0) / 1_000_000;
//        $warningAmount  = (float) ($row->warning_amount ?? 0) / 1_000_000;
//
//        return [
//            'labels' => ['Jami', 'Qaror chiqarilgan', 'Undirilgan', 'Ogohlantirilgan'],
//            'datasets' => [
//                [
//                    'type' => 'bar',
//                    'label' => 'Soni',
//                    'data' => [$total, $decisionMade, $fullyPaid, $warning],
//                    'backgroundColor' => '#60a5fa',
//                    'yAxisID' => 'y',
//                ],
//                [
//                    'type' => 'line',
//                    'label' => 'Pul summasi (mln UZS)',
//                    'data' => [$totalAmount, $decisionAmount, $paidAmount, $warningAmount],
//                    'borderColor' => '#ef4444',
//                    'backgroundColor' => '#ef4444',
//                    'yAxisID' => 'y1',
//                ],
//            ],
//        ];
//    }
//
//    protected function getOptions(): array
//    {
//        // Hech qanday JS callback YO'Q — grafikaning o'zi chiqayotganini tekshiramiz
//        return [
//            'scales' => [
//                'y' => [
//                    'beginAtZero' => true,
//                    'title' => ['display' => true, 'text' => 'Soni'],
//                ],
//                'y1' => [
//                    'beginAtZero' => true,
//                    'position' => 'right',
//                    'title' => ['display' => true, 'text' => 'mln UZS'],
//                    'grid' => ['drawOnChartArea' => false],
//                ],
//            ],
//        ];
//    }
//}
