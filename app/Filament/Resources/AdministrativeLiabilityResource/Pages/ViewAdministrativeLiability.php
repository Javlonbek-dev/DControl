<?php

namespace App\Filament\Resources\AdministrativeLiabilityResource\Pages;

use App\Filament\Resources\AdministrativeLiabilityResource;
use Carbon\Carbon;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewAdministrativeLiability extends ViewRecord
{
    protected static string $resource = AdministrativeLiabilityResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make('Tekshiruv ma’lumotlari')
                ->schema([
                    TextEntry::make('orders.company.name')
                        ->label('Tashkilot nomi')
                        ->color('primary'),
                    TextEntry::make('orders.company.stir')
                        ->label('Tashkilot stiri')
                        ->color('primary'),
                    TextEntry::make('location')
                        ->label('Tashkilot joylashgan hududi:')
                        ->state(function ($record) {
                            $order = $record->orders()->with('company.district.region')->first(); // <— Collection emas, Model
                            return optional($order?->company?->district?->region)->name
                                . ' ' .
                                optional($order?->company?->district)->name;
                        }),
                    TextEntry::make('number')
                        ->label("Ma'muriy bayonnoma raqami")
                        ->columnSpan(1), // chapda
                    TextEntry::make('registration_date')
                        ->date('d-m-Y')
                        ->label("Ro'yxatga olingan sana")
                        ->columnSpan(1), // o‘ngda

                    TextEntry::make('court_date')
                        ->date('d-m-Y')
                        ->label('Sudga kiritilgan sanasi'),

                    TextEntry::make('decision_type_id')
                        ->label('Sud qarorining turi')
                        ->badge()
                        ->state(fn($record) => $record->decision_type?->name ?? 'Sud qarori chiqarmagan')
                        ->color(fn($record) => $record->decision_type_id ? 'success' : 'danger'),

                    TextEntry::make('decision_date')
                        ->label('Sud qarorining sanasi')
                        ->badge()
                        ->state(fn($record) => $record->decision_date
                            ? Carbon::parse($record->decision_date)->format('d-m-Y')
                            : 'Sud qarori chiqarmagan'
                        )
                        ->color(fn($record) => $record->decision_date ? 'success' : 'danger'),
                    TextEntry::make('imposed_fine')
                        ->label('Sud qo\'llagan jarima miqdori (s\'om)')
                        ->badge()
                        ->state(fn($record) => filled($record->imposed_fine)
                            ? number_format($record->imposed_fine, 0, '.', ' ')
                            : 'Sud qarori chiqarmagan'
                        )
                        ->color(fn($record) => filled($record->imposed_fine) ? 'success' : 'danger'),

                    TextEntry::make('is_paid')
                        ->label('To\'langanlik statusi')
                        ->badge()
                        ->icon(fn($record) => $record->is_paid ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                        ->state(fn($record) => is_null($record->is_paid)
                            ? 'Sud qarori chiqarmagan'
                            : ($record->is_paid ? 'To\'langan' : 'To\'lanmagan')
                        )
                        ->color(fn($record) => is_null($record->is_paid)
                            ? 'danger'
                            : ($record->is_paid ? 'success' : 'warning')
                        ),
                    TextEntry::make('bxm.quantity')
                        ->badge()
                        ->color('info')
                        ->label('BXM qiymati'),

                    TextEntry::make('person_full_name')
                        ->label('Ma\'muriy qo\'llangan shaxs')
                        ->color('primary')
                        ->weight('bold'), // qalin yozuv

                    TextEntry::make('person_passport')
                        ->label('Pasport ma’lumotlari')
                        ->copyable(), // nusxalash tugmasi chiqadi

                    TextEntry::make('profession.name')
                        ->label('Lavozimi')
                        ->badge()
                        ->color('success'),
                ])
                ->columns(2),

            RepeatableEntry::make('non_conformity')
                ->label('Kamchilik turlari:')
                ->schema([
                    TextEntry::make('product.name'),
//                    TextEntry::make(),
                ])
        ]);
    }
}
