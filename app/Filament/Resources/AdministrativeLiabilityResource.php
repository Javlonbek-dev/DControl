<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdministrativeLiabilityResource\Pages;
use App\Filament\Resources\AdministrativeLiabilityResource\RelationManagers;
use App\Models\AdministrativeLiability;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdministrativeLiabilityResource extends Resource
{
    protected static ?string $model = AdministrativeLiability::class;
    protected static ?string $pluralLabel = "Ma'muriy bayonnoma";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('registration_date')
                    ->required()
                    ->label('Registratsiya qilingan sana'),
                Forms\Components\Select::make('decision_type_id')
                    ->label('Qaror turi')
                    ->relationship('decision_type', 'name'),
                Forms\Components\DatePicker::make('decision_date')
                    ->label('Qaror sanasi'),
                Forms\Components\Select::make('gov_control_id')
                    ->label('Tekshiruv raqami')
                    ->relationship('gov_control', 'number'),
                Forms\Components\DatePicker::make('court_date')
                    ->label('Sudga kiritilgan sanasi'),
                Forms\Components\TextInput::make('imposed_fine')
                    ->required()
                    ->label('jarima miqdori')
                    ->numeric(),
                Forms\Components\Toggle::make('is_paid')
                    ->required()
                    ->label('To\'langanmi'),
                Forms\Components\Select::make('bxm_id')
                    ->required()
                    ->label('Bxm qiymati')
                    ->relationship('bxm', 'quantity'),
                Forms\Components\TextInput::make('person_full_name')
                    ->required()
                    ->label('Ma\'muriy qo\'llangan shaxsni to\'liq F.I.SH')
                    ->maxLength(255),
                Forms\Components\TextInput::make('person_passport')
                    ->required()
                    ->label('Pasport malumotlari')
                    ->maxLength(255),
                Forms\Components\Select::make('profession_id')
                    ->required()
                    ->searchable()
                    ->label('Kasbi')
                    ->relationship('profession', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Kasb nomi'),
                        Forms\Components\Toggle::make('is_director')
                            ->required()
                            ->label('Derektormi'),
                        Forms\Components\Toggle::make('is_official')
                            ->required()
                            ->label('Rasmiymi')
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_date')
                    ->date('d-m-Y')
                    ->label('Registratsiya qilingan sana')
                    ->sortable(),
                Tables\Columns\TextColumn::make('court_date')
                    ->date('d-m-Y')
                    ->label('Sudga kiritilgan sanasi'),
                Tables\Columns\TextColumn::make('deadline_status')
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

                        $daysLeft = $today->diffInDays($deadline, false); // 0 => bugun oxirgi kun
                        if ($daysLeft >= 2) {
                            return "{$daysLeft} kun qoldi"; // 2 yoki undan ko'p
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
                            return 'danger'; // qizil
                        }

                        $daysLeft = $today->diffInDays($deadline, false);
                        if ($daysLeft >= 2) {
                            return 'success'; // yashil
                        }
                        return 'warning'; // 1 kun yoki bugun — sariq
                    })
                    ->tooltip(function ($record) {
                        if (!$record->registration_date && !$record->court_date) {
                            return null;
                        }

                        $reg = $record->registration_date
                            ? Carbon::parse($record->registration_date)->format('d-m-Y')
                            : '—';

                        $deadline = $record->court_date
                            ? Carbon::parse($record->court_date)->format('d-m-Y') // court_date — oxirgi muddat
                            : Carbon::parse($record->registration_date)->addDays(3)->format('d-m-Y');

                        $today = Carbon::today()->format('d-m-Y');

                        return "Registratsiya: {$reg}\nOxirgi muddat (court_date): {$deadline}\nBugun: {$today}";
                    }),
                Tables\Columns\TextColumn::make('decision_type.name')
                    ->searchable()
                    ->label('Qaror turi'),
                Tables\Columns\TextColumn::make('decision_date')
                    ->date('d-m-Y')
                    ->label('Qaror sanasi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('imposed_fine')
                    ->numeric()
                    ->label('jarima miqdori (s\'om)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_paid')
                    ->label('To\'langanlik statusi')
                    ->badge()
                    ->icon(fn($record) => $record->is_paid
                        ? 'heroicon-o-check-circle'
                        : 'heroicon-o-x-circle'
                    )
                    ->color(fn($record) => $record->is_paid ? 'success' : 'danger')
                    ->formatStateUsing(fn($state) => $state ? 'To\'langan' : 'To\'lanmagan'),
                Tables\Columns\TextColumn::make('bxm.quantity')
                    ->sortable()
                    ->label('BXM miqdori'),
                Tables\Columns\TextColumn::make('person_full_name')
                    ->searchable()
                    ->label('Ma\'muriy qo\'llangan shaxs'),
                Tables\Columns\TextColumn::make('person_passport')
                    ->searchable()
                    ->label('Pasport malumotlari'),
                Tables\Columns\TextColumn::make('profession.name')
                    ->numeric()
                    ->label('Kasbi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdministrativeLiabilities::route('/'),
            'create' => Pages\CreateAdministrativeLiability::route('/create'),
            'edit' => Pages\EditAdministrativeLiability::route('/{record}/edit'),
        ];
    }
}
