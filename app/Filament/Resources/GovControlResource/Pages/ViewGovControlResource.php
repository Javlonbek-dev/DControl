<?php

namespace App\Filament\Resources\GovControlResource\Pages;

use App\Filament\Resources\GovControlResource;
use Carbon\Carbon;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewGovControlResource extends ViewRecord
{
    protected static string $resource = GovControlResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Tekshiruv ma’lumotlari')
                    ->schema([
                        TextEntry::make('order.company.name')
                            ->label('Tashkilot nomi'),
                        TextEntry::make('order.company.district.name')
                            ->label('Tashkilot joylashgan hududi')
                            ->state(function ($record) {
                                return optional($record->order->company->district->region)->name
                                    . ' ' .
                                    optional($record->order->company->district)->name;
                            }),
                        TextEntry::make('order.number')
                            ->label('Buyruq raqami'),

                        TextEntry::make('number')
                            ->label('Tekshiruv raqami'),

                        TextEntry::make('real_date_from')
                            ->date()
                            ->label('Tekshiruvni boshlanish sanasi'),

                        TextEntry::make('real_date_to')
                            ->date()
                            ->label('Tekshiruvni tugatish sanasi'),

                        TextEntry::make('is_finished')
                            ->label('Tekshiruv tugatilganmi')
                            ->badge()
                            ->icon(fn($state) => $state
                                ? 'heroicon-o-check-circle'
                                : 'heroicon-o-x-circle'
                            )
                            ->color(fn($state) => $state ? 'success' : 'danger')
                            ->formatStateUsing(fn($state) => $state ? 'Tugatilgan' : 'Tugatilmagan'),
                    ])
                    ->columns(2),
                        RepeatableEntry::make('administrative') // hasMany relation nomi
                        ->label('Ma’muriy javobgarliklar')
                            ->schema([
                                TextEntry::make('registration_date')
                                    ->date('d-m-Y')
                                    ->label('Registratsiya qilingan sana'),

                                TextEntry::make('court_date')
                                    ->date('d-m-Y')
                                    ->label('Sudga kiritilgan sanasi'),

                                TextEntry::make('deadline_status')
                                    ->label('Sudga topshirish muddati')
                                    ->badge()
                                    ->state(function ($record) {
                                        if (!$record->registration_date && !$record->court_date) {
                                            return "Ma'lumot yo‘q";
                                        }

                                        $deadline = $record->court_date
                                            ? Carbon::parse($record->court_date)
                                            : Carbon::parse($record->registration_date)->copy()->addDays(3);

                                        $today = Carbon::today();

                                        if ($today->gt($deadline)) {
                                            $over = $deadline->diffInDays($today);
                                            return "{$over} kun kechikgan";
                                        }

                                        $daysLeft = $today->diffInDays($deadline, false);
                                        if ($daysLeft >= 2) {
                                            return "{$daysLeft} kun qoldi";
                                        }
                                        if ($daysLeft === 1) {
                                            return "1 kun qoldi";
                                        }
                                        return "Bugun oxirgi kun";
                                    })
                                    ->color(function ($record) {
                                        if (!$record->registration_date && !$record->court_date) {
                                            return 'gray';
                                        }

                                        $deadline = $record->court_date
                                            ? Carbon::parse($record->court_date)
                                            : Carbon::parse($record->registration_date)->copy()->addDays(3);

                                        $today = Carbon::today();

                                        if ($today->gt($deadline)) {
                                            return 'danger';
                                        }

                                        $daysLeft = $today->diffInDays($deadline, false);
                                        if ($daysLeft >= 2) {
                                            return 'success';
                                        }
                                        return 'warning';
                                    })
                                    ->tooltip(function ($record) {
                                        if (!$record->registration_date && !$record->court_date) {
                                            return null;
                                        }

                                        $reg = $record->registration_date
                                            ? Carbon::parse($record->registration_date)->format('d-m-Y')
                                            : '—';

                                        $deadline = $record->court_date
                                            ? Carbon::parse($record->court_date)->format('d-m-Y')
                                            : Carbon::parse($record->registration_date)->addDays(3)->format('d-m-Y');

                                        $today = Carbon::today()->format('d-m-Y');

                                        return "Registratsiya: {$reg}\nOxirgi muddat (court_date): {$deadline}\nBugun: {$today}";
                                    }),

                                TextEntry::make('decision_type.name')
                                    ->label('Qaror turi'),

                                TextEntry::make('decision_date')
                                    ->date('d-m-Y')
                                    ->label('Qaror sanasi'),

                                TextEntry::make('imposed_fine')
                                    ->numeric()
                                    ->label('Jarima miqdori (so‘m)'),

                                TextEntry::make('is_paid')
                                    ->label('To‘langanlik statusi')
                                    ->badge()
                                    ->icon(fn($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                                    ->color(fn($state) => $state ? 'success' : 'danger')
                                    ->formatStateUsing(fn($state) => $state ? 'To‘langan' : 'To‘lanmagan'),

                                TextEntry::make('bxm.quantity')
                                    ->label('BXM miqdori'),

                                TextEntry::make('person_full_name')
                                    ->label('Ma’muriy qo‘llangan shaxs'),

                                TextEntry::make('person_passport')
                                    ->color('primary')
                                    ->label('Pasport ma’lumotlari'),

                                TextEntry::make('profession.name')
                                    ->label('Kasbi')
                                    ->color('primary'),
                            ])
                            ->columns(2)
            ]);
    }
}
