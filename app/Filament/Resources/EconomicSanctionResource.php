<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EconomicSanctionResource\Pages;
use App\Filament\Resources\EconomicSanctionResource\RelationManagers;
use App\Models\EconomicSanction;
use App\Models\NonConformity;
use App\Models\Order;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EconomicSanctionResource extends Resource
{
    protected static ?int $navigationSort = 7;
    protected static ?string $model = EconomicSanction::class;
    protected static ?string $pluralLabel = "Moliyaviy Jarima";
//    protected static ?string $navigationGroup = "Sanksiyaga oid malumotlar";


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('registration_date')
                    ->required(),
                Forms\Components\TextInput::make('assessed_fine')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('court_name')
                    ->required(),
                Forms\Components\Select::make('sanction_id')
                    ->required()
                    ->relationship('sanction', 'number'),
                Forms\Components\Select::make('order_id')
                    ->label('Buyruq raqami')
                    ->dehydrated(false)
                    ->options(Order::pluck('number', 'id'))
                    ->searchable()
                    ->preload(),
                Forms\Components\MultiSelect::make('selected_nc_ids')
                    ->label('Nomuvofiqliklar')
                    ->options(function (Get $get) {
                        $orderId = $get('order_id');
                        if (!$orderId) return [];

                        $byProduct = NonConformity::query()
                            ->whereHas('product.gov_control', fn($q) => $q->where('order_id', $orderId))
                            ->with('product:id,name')
                            ->get()
                            ->mapWithKeys(fn($nc) => [
                                $nc->id => ($nc->product?->name ? $nc->product->name . ' ' : '') . "(NC #{$nc->id})",
                            ])
                            ->toArray();

                        $byMetrology = NonConformity::query()
                            ->whereHas('metrology_instrument.gov_control', fn($q) => $q->where('order_id', $orderId))
                            ->with('metrology_instrument:id,name')
                            ->get()
                            ->mapWithKeys(fn($nc) => [
                                $nc->id => ($nc->metrology_instrument?->name ? $nc->metrology_instrument->name . ' ' : '') . "(NC #{$nc->id})",
                            ])
                            ->toArray();

                        $byCertificate = NonConformity::query()
                            ->whereHas('certificate.gov_control', fn($q) => $q->where('order_id', $orderId))
                            ->with('certificate:id,name')
                            ->get()
                            ->mapWithKeys(fn($nc) => [
                                $nc->id => ($nc->certificate?->name ? $nc->certificate->name . ' ' : '') . "(NC #{$nc->id})",
                            ])
                            ->toArray();
                        $byServices = NonConformity::query()
                            ->whereHas('service.gov_control', fn($q) => $q->where('order_id', $orderId))
                            ->with('service:id,name')
                            ->get()
                            ->mapWithKeys(fn($nc) => [
                                $nc->id => ($nc->service?->name ? $nc->service->name . ' ' : '') . "(NC #{$nc->id})",
                            ])
                            ->toArray();

                        return array_filter([
                            'Mahsulot' => $byProduct,
                            'O‘lchov vositasi' => $byMetrology,
                            'Sertifikat' => $byCertificate,
                            'Xizmatlar' => $byServices
                        ], fn($arr) => !empty($arr));
                    })
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->dehydrated(false)
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if (!$record) return;
                        $selected = \App\Models\NonConformity::where('economic_sanction_id', $record->id)
                            ->pluck('id')
                            ->all();
                        $component->state($selected);
                    }),
                Forms\Components\DatePicker::make('decision_date')
                    ->visible(Filament::auth()->user()->hasRole('moderator')),
                Forms\Components\TextInput::make('decision_number')
                    ->numeric()
                    ->visible(Filament::auth()->user()->hasRole('moderator')),
                Forms\Components\Select::make('decision_type_id')
                    ->visible(Filament::auth()->user()->hasRole('moderator'))
                    ->relationship('decision_type', 'name'),
                Forms\Components\TextInput::make('imposed_fine')
                    ->visible(Filament::auth()->user()->hasRole('moderator'))
                    ->numeric(),
                Forms\Components\Toggle::make('is_paid')
                    ->required()
                    ->visible(Filament::auth()->user()->hasRole('moderator')),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->numeric()
                    ->label('Moliyaviy jarima raqami')
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->date('d-m-Y')
                    ->label('Moliyaviy jarima regisratsiya qilingan sana')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assessed_fine')
                    ->numeric()
                    ->label("Qo'llangan jarima")
                    ->sortable(),
                Tables\Columns\TextColumn::make('court_name')
                    ->label("Sud nomi")
                    ->sortable(),
                Tables\Columns\TextColumn::make('decision_date')
                    ->label('Sud qarorining sanasi')
                    ->sortable()
                    ->badge()
                    ->state(fn ($record) => $record->decision_date
                        ? Carbon::parse($record->decision_date)->format('d-m-Y')
                        : 'Sud qarori chiqarmagan'
                    )
                    ->color(fn ($record) => $record->decision_date ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('decision_number')
                    ->label('Sud qarorining raqami')
                    ->sortable()
                    ->badge()
                    ->state(fn ($record) => $record->decision_number ?? 'Sud qarori chiqarmagan'
                    )->color(fn ($record) => $record->decision_number ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('decision_type_id')
                    ->label('Sud qarorining turi')
                    ->badge()
                    ->state(fn($record) => $record->decision_type?->name ?? 'Sud qarori chiqarmagan')
                    ->color(fn($record) => $record->decision_type_id ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('imposed_fine')
                    ->label('Sud qo\'llagan jarima miqdori (s\'om)')
                    ->sortable()
                    ->badge()
                    ->state(fn($record) => filled($record->imposed_fine)
                        ? number_format($record->imposed_fine, 0, '.', ' ')
                        : 'Sud qarori chiqarmagan'
                    )
                    ->color(fn($record) => filled($record->imposed_fine) ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('is_paid')
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
                Tables\Columns\TextColumn::make('sanction_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Kim tomonidan yaratilgan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->label('Kim tomonidan o\'zgartirilgan')
                    ->searchable(),
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
                Tables\Actions\Action::make('enterCourtDecision')
                    ->label('Sud qarorlarini kiritish')
                    ->icon('heroicon-m-scale')
                    ->visible(fn($record) => blank($record->decision_type_id) || blank($record->decision_date))
                    ->form([
                        Forms\Components\Select::make('decision_type_id')
                            ->label('Qaror turi')
                            ->relationship('decision_type', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('decision_number')
                            ->label('Sud qarori raqami')
                            ->required(),
                        Forms\Components\DatePicker::make('decision_date')
                            ->label('Sud qarori sanasi')
                            ->required(),
                        Forms\Components\TextInput::make('imposed_fine')
                            ->label('Jarima miqdori')
                            ->numeric()
                            ->required(),

                        Forms\Components\Toggle::make('is_paid')
                            ->label('To‘langanmi')
                            ->default(false)
                            ->required(),
                    ])
                    ->modalHeading('Sud qarorlarini kiritish')
                    ->modalSubmitActionLabel('Saqlash')
                    ->modalWidth('lg')
                    ->action(function ($record, array $data) {
                        $record->update($data);

                        \Filament\Notifications\Notification::make()
                            ->title('Sud qarorlari saqlandi')
                            ->success()
                            ->send();
                    }),

                // "Undirilgan so'mma"
                Tables\Actions\Action::make('enterPaidInfo')
                    ->label('Undirilgan summa')
                    ->icon('heroicon-m-banknotes')
                    ->modalHeading('Undirilgan summani kiritish')
                    ->modalSubmitActionLabel('Saqlash')
                    ->modalWidth('lg')
                    ->visible(fn($record) => $record?->imposed_fine > 0 && $record->remaining > 0)
                    ->form([
                        Forms\Components\TextInput::make('imposed_fine_view')
                            ->label('Sud belgilagan jarima (so‘m)')
                            ->default(fn($record) => (int)$record->imposed_fine)
                            ->disabled()
                            ->dehydrated(false), // <-- Muhim!

                        Forms\Components\TextInput::make('paid_total_view')
                            ->label('Hozirgacha undirildi (so‘m)')
                            ->default(fn($record) => (int)$record->paid_total)
                            ->disabled()
                            ->dehydrated(false), // <-- Muhim!

                        Forms\Components\TextInput::make('remaining_view')
                            ->label('Qolgan (so‘m)')
                            ->default(fn($record) => (int)$record->remaining)
                            ->disabled()
                            ->dehydrated(false), // <-- Muhim!

                        Forms\Components\TextInput::make('payment_amount')
                            ->label('Ushbu to‘lov summasi (so‘m)')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        Forms\Components\DatePicker::make('paid_date')
                            ->label('To‘langan sana')
                            ->default(now()->toDateString())
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $imposed = (float)$record->imposed_fine;
                        $paidTotalBefore = (float)$record->payments()->sum('payment_amount');

                        $remaining = max(0, $imposed - $paidTotalBefore);
                        $amount = (float)($data['payment_amount'] ?? 0);

                        if ($amount <= 0) {
                            throw ValidationException::withMessages([
                                'payment_amount' => 'To‘lov summasi musbat bo‘lishi kerak.',
                            ]);
                        }
                        if ($amount > $remaining) {
                            throw ValidationException::withMessages([
                                'payment_amount' => 'Kiritilgan summa qolgan qarzdan oshmasligi kerak.',
                            ]);
                        }

                        // Yangi to‘lovni payments jadvaliga yozamiz
                        Payment::create([
                            'economic_sanction_id' => $record->id,
                            'paid_date' => Carbon::parse($data['paid_date'])->toDateString(),
                            'payment_amount' => $amount,
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);

                        // Holatni yangilash (to‘liq yopildimi?)
                        $paidTotalAfter = (float)$record->payments()->sum('payment_amount');
                        $isFullyPaid = $paidTotalAfter >= $imposed && $imposed > 0;

                        $record->update([
                            'is_paid' => $isFullyPaid, // imposed_fine-ni HECH QACHON o‘zgartirmaymiz!
                        ]);

                        Notification::make()
                            ->title('To‘lov saqlandi')
                            ->body('Qolgan summa: ' . number_format(max(0, $imposed - $paidTotalAfter), 0, '.', ' '))
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListEconomicSanctions::route('/'),
            'create' => Pages\CreateEconomicSanction::route('/create'),
            'edit' => Pages\EditEconomicSanction::route('/{record}/edit'),
        ];
    }
}
