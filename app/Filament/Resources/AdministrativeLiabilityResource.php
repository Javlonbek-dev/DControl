<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdministrativeLiabilityResource\Pages;
use App\Filament\Resources\AdministrativeLiabilityResource\RelationManagers;
use App\Models\AdministrativeLiability;
use App\Models\Bxm;
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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdministrativeLiabilityResource extends Resource
{
    protected static ?int $navigationSort = 5;
    protected static ?string $model = AdministrativeLiability::class;
    protected static ?string $pluralLabel = "Ma'muriy bayonnoma";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->label("Ma'muriy bayonnoma raqami"),

                Forms\Components\DatePicker::make('registration_date')
                    ->required()
                    ->label('Registratsiya qilingan sana'),
                Forms\Components\Select::make('order_id')
                    ->label('Buyruq raqami')
                    ->dehydrated(false)
                    ->options(Order::pluck('number', 'id'))
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('decision_type_id')
                    ->label('Qaror turi')
                    ->visible(Filament::auth()->user()->hasRole(['moderator']))
                    ->relationship('decision_type', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                    ]),
                Forms\Components\DatePicker::make('decision_date')
                    ->label('Sudni qaror sanasi')
                    ->visible(Filament::auth()->user()->hasRole(['moderator'])),
                Forms\Components\MultiSelect::make('selected_nc_ids')
                    ->label('Tekshiriv obektlari')
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
                    ->preload()          // closure emas!
                    ->reactive()
                    ->dehydrated(false)  // DB ga yozmaymiz; o‘zimiz sync qilamiz
                    ->afterStateHydrated(function ($component, $state, $record) {
                        if (!$record) return;
                        // Avval tanlangan NC larni record orqali yuklab beramiz
                        $selected = \App\Models\NonConformity::where('administrative_liability_id', $record->id)
                            ->pluck('id')
                            ->all();
                        $component->state($selected);
                    }),
                Forms\Components\DatePicker::make('court_date')
                    ->label('Sudga kiritilgan sanasi'),
                Forms\Components\TextInput::make('imposed_fine')
                    ->label('Jarima miqdori (so‘m)')
                    ->numeric()
                    ->minValue(0)
                    ->required(),
                Forms\Components\Toggle::make('is_paid')
                    ->visible(Filament::auth()->user()->hasRole(['moderator']))
                    ->label('To\'langanmi'),
                Forms\Components\TextInput::make('bxm_id')
                    ->label('Bxm qiymati')
                    ->default(fn () => Bxm::where('is_active', true)->orderByDesc('id')->value('quantity'))
                    ->disabled(),
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
                    ->label('Lavozimi')
                    ->relationship('profession', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Lavozim nomi'),
                        Forms\Components\Toggle::make('is_director')
                            ->required()
                            ->label('Derektormi'),
                        Forms\Components\Toggle::make('is_official')
                            ->required()
                            ->label('Mansabdormi')
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.company.name')
                    ->label('Tashkilot nomi'),
                Tables\Columns\TextColumn::make('registration_date')
                    ->date('d.m.Y')
                    ->label('Registratsiya qilingan sana')
                    ->sortable(),
                Tables\Columns\TextColumn::make('court_date')
                    ->date('d.m.Y')
                    ->label('Sudga kiritilgan sanasi'),
//                Tables\Columns\TextColumn::make('deadline_status')
//                    ->label('Sudga topshirish muddati')
//                    ->badge()
//                    ->state(function ($record) {
//                        if (!$record->registration_date && !$record->court_date) {
//                            return "Ma'lumot yo‘q";
//                        }
//
//                        $deadline = $record->court_date
//                            ? Carbon::parse($record->court_date)
//                            : Carbon::parse($record->registration_date)->copy()->addDays(3);
//
//                        $today = Carbon::today();
//
//                        if ($today->gt($deadline)) {
//                            $over = $deadline->diffInDays($today);
//                            return "{$over} kun kechikgan";
//                        }
//
//                        $daysLeft = $today->diffInDays($deadline, false); // 0 => bugun oxirgi kun
//                        if ($daysLeft >= 2) {
//                            return "{$daysLeft} kun qoldi"; // 2 yoki undan ko'p
//                        }
//                        if ($daysLeft === 1) {
//                            return "1 kun qoldi";
//                        }
//                        return "Bugun oxirgi kun";
//                    })
//                    ->color(function ($record) {
//                        if (!$record->registration_date && !$record->court_date) {
//                            return 'gray';
//                        }
//
//                        $deadline = $record->court_date
//                            ? Carbon::parse($record->court_date)
//                            : Carbon::parse($record->registration_date)->copy()->addDays(3);
//
//                        $today = Carbon::today();
//
//                        if ($today->gt($deadline)) {
//                            return 'danger'; // qizil
//                        }
//
//                        $daysLeft = $today->diffInDays($deadline, false);
//                        if ($daysLeft >= 2) {
//                            return 'success'; // yashil
//                        }
//                        return 'warning'; // 1 kun yoki bugun — sariq
//                    })
//                    ->tooltip(function ($record) {
//                        if (!$record->registration_date && !$record->court_date) {
//                            return null;
//                        }
//
//                        $reg = $record->registration_date
//                            ? Carbon::parse($record->registration_date)->format('d-m-Y')
//                            : '—';
//
//                        $deadline = $record->court_date
//                            ? Carbon::parse($record->court_date)->format('d-m-Y') // court_date — oxirgi muddat
//                            : Carbon::parse($record->registration_date)->addDays(3)->format('d-m-Y');
//
//                        $today = Carbon::today()->format('d-m-Y');
//
//                        return "Registratsiya: {$reg}\nOxirgi muddat (court_date): {$deadline}\nBugun: {$today}";
//                    }),
                Tables\Columns\TextColumn::make('decision_type_id')
                    ->label('Sud qarorining turi')
                    ->badge()
                    ->state(fn($record) => $record->decision_type?->name ?? 'Sud qarori chiqarmagan')
                    ->color(fn($record) => $record->decision_type_id ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('decision_date')
                    ->label('Sud qarorining sanasi')
                    ->sortable()
                    ->badge()
                    ->state(fn ($record) => $record->decision_date
                        ? Carbon::parse($record->decision_date)->format('d.m.Y')
                        : 'Sud qarori chiqarmagan'
                    )
                    ->color(fn ($record) => $record->decision_date ? 'success' : 'danger'),
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
                Tables\Columns\TextColumn::make('bxm.quantity')
                    ->sortable()
                    ->label('BXM miqdori'),
                Tables\Columns\TextColumn::make('person_full_name')
                    ->searchable()
                    ->label('Ma\'muriy qo\'llangan shaxs'),
                Tables\Columns\TextColumn::make('person_passport')
                    ->searchable()
                    ->size('')
                    ->label('Pasport malumotlari'),
                Tables\Columns\TextColumn::make('profession.name')
                    ->numeric()
                    ->size('L')
                    ->label('Lavozimi')
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
                Tables\Actions\Action::make('enterCourtDecision')
                    ->label('Sud qarorlarini kiritish')
                    ->icon('heroicon-m-scale')
                    ->visible(fn($record) => !$record->is_finished &&
                        (
                            $record->created_by === auth()->id()
                            ||
                            auth()->user()?->hasRole('moderator')
                        ) &&
                        (
                            blank($record->decision_type_id) || blank($record->decision_date)
                        )
                    )
                    ->form([
                        Forms\Components\Select::make('decision_type_id')
                            ->label('Qaror turi')
                            ->relationship('decision_type', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),

                        Forms\Components\DatePicker::make('decision_date')
                            ->label('Sud qarori sanasi')
                            ->required(),

                        Forms\Components\TextInput::make('imposed_fine')
                            ->label('Jarima miqdori')
                            ->confirmed(412000)
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
                        abort_unless(
                            $record->created_by === auth()->id() || auth()->user()?->hasRole('moderator'),
                            403
                        );

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
                    ->visible(fn($record) => (
                            $record?->imposed_fine > 0 &&
                            $record->remaining > 0
                        ) && (
                            // faqat egasi yoki moderator
                            $record->created_by === auth()->id() ||
                            auth()->user()?->hasRole('moderator')
                        )
                    )->form([
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
                        // imposed_fine-ni faqat rekorddan olamiz — formdan emas
                        abort_unless(
                            $record->created_by === auth()->id() || auth()->user()?->hasRole('moderator'),
                            403
                        );
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
                            'administrative_liability_id' => $record->id,
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function canDeleteAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('moderator');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdministrativeLiabilities::route('/'),
            'create' => Pages\CreateAdministrativeLiability::route('/create'),
            'edit' => Pages\EditAdministrativeLiability::route('/{record}/edit'),
            'view' => Pages\ViewAdministrativeLiability::route('/{record}'),
        ];
    }
}
