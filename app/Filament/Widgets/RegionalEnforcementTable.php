<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Region;

class RegionalEnforcementTable extends BaseWidget
{
    protected static ?string $heading = 'Viloyatlar kesimi — tekshiruvlar va jarimalar';
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {

        $gcAgg = DB::table('gov_controls as gc')
            ->join('orders as o', 'o.id', '=', 'gc.order_id')
            ->leftJoin('districts as od', 'od.id', '=', 'o.district_id')
            ->leftJoin('regions  as orr', 'orr.id', '=', 'od.region_id')
            ->leftJoin('companies as co', 'co.id', '=', 'o.company_id')
            ->leftJoin('districts as cd', 'cd.id', '=', 'co.district_id')
            ->leftJoin('regions  as crr', 'crr.id', '=', 'cd.region_id')
            ->selectRaw('COALESCE(orr.id, crr.id) as region_id, COUNT(DISTINCT gc.id) as inspections_count')
            ->groupByRaw('COALESCE(orr.id, crr.id)');


        $alRegion = DB::table('non_conformities as nc')
            ->join('administrative_liabilities as al', 'al.id', '=', 'nc.administrative_liability_id')

            ->leftJoin('products as p', 'p.id', '=', 'nc.product_id')
            ->leftJoin('metrology_instruments as mi', 'mi.id', '=', 'nc.metrology_instrument_id')
            ->leftJoin('certificates as c', 'c.id', '=', 'nc.certificate_id')

            ->leftJoin('gov_controls as gc_p', 'gc_p.id', '=', 'p.gov_control_id')
            ->leftJoin('gov_controls as gc_mi', 'gc_mi.id', '=', 'mi.gov_control_id')
            ->leftJoin('gov_controls as gc_c', 'gc_c.id', '=', 'c.gov_control_id')

            ->leftJoin('orders as o_p', 'o_p.id', '=', 'gc_p.order_id')
            ->leftJoin('orders as o_mi', 'o_mi.id', '=', 'gc_mi.order_id')
            ->leftJoin('orders as o_c', 'o_c.id', '=', 'gc_c.order_id')

            ->leftJoin('districts as d_p', 'd_p.id', '=', 'o_p.district_id')
            ->leftJoin('districts as d_mi', 'd_mi.id', '=', 'o_mi.district_id')
            ->leftJoin('districts as d_c', 'd_c.id', '=', 'o_c.district_id')
            ->leftJoin('regions as r_p', 'r_p.id', '=', 'd_p.region_id')
            ->leftJoin('regions as r_mi', 'r_mi.id', '=', 'd_mi.region_id')
            ->leftJoin('regions as r_c', 'r_c.id', '=', 'd_c.region_id')

            ->leftJoin('companies as co_p', 'co_p.id', '=', 'o_p.company_id')
            ->leftJoin('companies as co_mi', 'co_mi.id', '=', 'o_mi.company_id')
            ->leftJoin('companies as co_c', 'co_c.id', '=', 'o_c.company_id')

            ->leftJoin('districts as cd_p', 'cd_p.id', '=', 'co_p.district_id')
            ->leftJoin('districts as cd_mi', 'cd_mi.id', '=', 'co_mi.district_id')
            ->leftJoin('districts as cd_c', 'cd_c.id', '=', 'co_c.district_id')

            ->leftJoin('regions as cr_p', 'cr_p.id', '=', 'cd_p.region_id')
            ->leftJoin('regions as cr_mi', 'cr_mi.id', '=', 'cd_mi.region_id')
            ->leftJoin('regions as cr_c', 'cr_c.id', '=', 'cd_c.region_id')

            ->selectRaw("
                COALESCE(r_p.id, r_mi.id, r_c.id, cr_p.id, cr_mi.id, cr_c.id) as region_id,
                al.id as al_id,
                al.decision_type_id,
                COALESCE(al.imposed_fine, 0) as imposed_fine
            ")
            ->whereNotNull('al.id')
            ->groupByRaw('COALESCE(r_p.id, r_mi.id, r_c.id, cr_p.id, cr_mi.id, cr_c.id), al.id, al.decision_type_id, al.imposed_fine');

        $alPay = DB::table('payments')
            ->whereNotNull('administrative_liability_id')
            ->selectRaw('administrative_liability_id as al_id, SUM(COALESCE(payment_amount,0)) as paid_sum')
            ->groupBy('administrative_liability_id');

        $alAgg = DB::query()
            ->fromSub(
                DB::query()->fromSub($alRegion, 'alr')
                    ->leftJoinSub($alPay, 'alp', 'alp.al_id', '=', 'alr.al_id')
                    ->selectRaw("
                        alr.region_id,
                        alr.decision_type_id,
                        alr.imposed_fine,
                        COALESCE(alp.paid_sum,0) as paid_sum
                    "),
                'x'
            )
            ->selectRaw("
                region_id,
                SUM(CASE WHEN decision_type_id IS NOT NULL THEN 1 ELSE 0 END) as al_decision_count,
                SUM(imposed_fine) as al_imposed_sum,
                SUM(LEAST(paid_sum, imposed_fine)) as al_paid_sum
            ")
            ->groupBy('region_id');


        $esRegion = DB::table('non_conformities as nc')
            ->join('economic_sanctions as es', 'es.id', '=', 'nc.economic_sanction_id')

            ->leftJoin('products as p', 'p.id', '=', 'nc.product_id')
            ->leftJoin('metrology_instruments as mi', 'mi.id', '=', 'nc.metrology_instrument_id')
            ->leftJoin('certificates as c', 'c.id', '=', 'nc.certificate_id')

            ->leftJoin('gov_controls as gc_p', 'gc_p.id', '=', 'p.gov_control_id')
            ->leftJoin('gov_controls as gc_mi', 'gc_mi.id', '=', 'mi.gov_control_id')
            ->leftJoin('gov_controls as gc_c', 'gc_c.id', '=', 'c.gov_control_id')

            ->leftJoin('orders as o_p', 'o_p.id', '=', 'gc_p.order_id')
            ->leftJoin('orders as o_mi', 'o_mi.id', '=', 'gc_mi.order_id')
            ->leftJoin('orders as o_c', 'o_c.id', '=', 'gc_c.order_id')

            // order.district.region
            ->leftJoin('districts as d_p', 'd_p.id', '=', 'o_p.district_id')
            ->leftJoin('districts as d_mi', 'd_mi.id', '=', 'o_mi.district_id')
            ->leftJoin('districts as d_c', 'd_c.id', '=', 'o_c.district_id')
            ->leftJoin('regions as r_p', 'r_p.id', '=', 'd_p.region_id')
            ->leftJoin('regions as r_mi', 'r_mi.id', '=', 'd_mi.region_id')
            ->leftJoin('regions as r_c', 'r_c.id', '=', 'd_c.region_id')

            // fallback: order.company.district.region
            ->leftJoin('companies as co_p', 'co_p.id', '=', 'o_p.company_id')
            ->leftJoin('companies as co_mi', 'co_mi.id', '=', 'o_mi.company_id')
            ->leftJoin('companies as co_c', 'co_c.id', '=', 'o_c.company_id')

            ->leftJoin('districts as cd_p', 'cd_p.id', '=', 'co_p.district_id')
            ->leftJoin('districts as cd_mi', 'cd_mi.id', '=', 'co_mi.district_id')
            ->leftJoin('districts as cd_c', 'cd_c.id', '=', 'co_c.district_id')

            ->leftJoin('regions as cr_p', 'cr_p.id', '=', 'cd_p.region_id')
            ->leftJoin('regions as cr_mi', 'cr_mi.id', '=', 'cd_mi.region_id')
            ->leftJoin('regions as cr_c', 'cr_c.id', '=', 'cd_c.region_id')

            ->selectRaw("
                COALESCE(r_p.id, r_mi.id, r_c.id, cr_p.id, cr_mi.id, cr_c.id) as region_id,
                es.id as es_id,
                es.decision_type_id,
                COALESCE(es.imposed_fine, 0) as imposed_fine
            ")
            ->whereNotNull('es.id')
            ->groupByRaw('COALESCE(r_p.id, r_mi.id, r_c.id, cr_p.id, cr_mi.id, cr_c.id), es.id, es.decision_type_id, es.imposed_fine');

        $esPay = DB::table('payments')
            ->whereNotNull('economic_sanction_id')
            ->selectRaw('economic_sanction_id as es_id, SUM(COALESCE(payment_amount,0)) as paid_sum')
            ->groupBy('economic_sanction_id');

        $esAgg = DB::query()
            ->fromSub(
                DB::query()->fromSub($esRegion, 'esr')
                    ->leftJoinSub($esPay, 'esp', 'esp.es_id', '=', 'esr.es_id')
                    ->selectRaw("
                        esr.region_id,
                        esr.decision_type_id,
                        esr.imposed_fine,
                        COALESCE(esp.paid_sum,0) as paid_sum
                    "),
                'y'
            )
            ->selectRaw("
                region_id,
                SUM(CASE WHEN decision_type_id IS NOT NULL THEN 1 ELSE 0 END) as es_decision_count,
                SUM(imposed_fine) as es_imposed_sum,
                SUM(LEAST(paid_sum, imposed_fine)) as es_paid_sum
            ")
            ->groupBy('region_id');

        // ---- Final: Eloquent query (Region::query)
        $eloquent = Region::query()
            ->leftJoinSub($gcAgg, 'gc', 'gc.region_id', '=', 'regions.id')
            ->leftJoinSub($alAgg, 'alx', 'alx.region_id', '=', 'regions.id')
            ->leftJoinSub($esAgg, 'esx', 'esx.region_id', '=', 'regions.id')
            ->selectRaw("
                regions.id,
                regions.name as region_name,
                COALESCE(gc.inspections_count, 0) as inspections,
                COALESCE(alx.al_decision_count, 0) + COALESCE(esx.es_decision_count, 0) as decisions,
            COALESCE(alx.al_imposed_sum, 0) + COALESCE(esx.es_imposed_sum, 0) as imposed_total,
                COALESCE(alx.al_paid_sum, 0) + COALESCE(esx.es_paid_sum, 0) as paid_total
            ")
            ->orderBy('regions.name');

        return $table
            ->query($eloquent)
            ->columns([
                Tables\Columns\TextColumn::make('region_name')->label('Viloyat')->searchable()->sortable(),

                Tables\Columns\TextColumn::make('inspections')
                    ->label('Tekshiruvlar')->numeric()->sortable(),

//                Tables\Columns\TextColumn::make('decisions')
//                    ->label('Sud qarorlari')->numeric()->sortable(),

                Tables\Columns\TextColumn::make('imposed_total')
                    ->label('Jami jarima (so‘m)')
                    ->formatStateUsing(fn ($state) => number_format((float)$state, 0, '.', ' '))
                    ->sortable(),

                Tables\Columns\TextColumn::make('paid_total')
                    ->label('Undirilgan (so‘m)')
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 0, '.', ' '))
                    ->sortable()
                    ->color(fn ($record) => ($record->paid_total ?? 0) > 0 ? 'success' : null),

                Tables\Columns\TextColumn::make('remaining_total')
                    ->label('Qolgan (so‘m)')
                    ->getStateUsing(fn ($record) =>
                    max(0, (float) $record->imposed_total - (float) $record->paid_total)
                    )
                    ->formatStateUsing(fn ($state) => number_format((float) $state, 0, '.', ' '))
                    ->sortable()
                    ->color(fn ($record) =>
                    ((float) $record->imposed_total - (float) $record->paid_total) > 0
                        ? 'warning'
                        : 'success'
                    ),
            ])
            ->paginationPageOptions([10, 25, 50])
            ->defaultSort('region_name');
    }
}
