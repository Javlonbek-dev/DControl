<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SanctionPaymentRequestResource\Pages;
use App\Filament\Resources\SanctionPaymentRequestResource\RelationManagers;
use App\Models\NonConformity;
use App\Models\Order;
use App\Models\SanctionPaymentRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SanctionPaymentRequestResource extends Resource
{
    protected static ?int $navigationSort = 6;
    protected static ?string $model = SanctionPaymentRequest::class;
    protected static ?string $pluralLabel = "Sanksiya To'lov Talabnomasi";
//    protected static ?string $navigationGroup = "Sanksiyaga oid malumotlar";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->label('Talabnoma raqami'),
                Forms\Components\DatePicker::make('registration_date')
                    ->required()
                    ->label('Talabnoma registratsiya qilingan sanasi'),
                Forms\Components\TextInput::make('assessed_fine')
                    ->required()
                    ->label('Jarima so\'mmasi')
                    ->numeric(),
                Forms\Components\Select::make('order_id')
                    ->label('Buyruq raqami')
                    ->dehydrated(false)
                    ->options(Order::pluck('number', 'id'))
                    ->searchable()
                    ->preload(),
//                Forms\Components\MultiSelect::make('selected_nc_ids')
//                    ->label('Nomuvofiqliklar')
//                    ->options(function (Get $get) {
//                        $orderId = $get('order_id');
//                        if (!$orderId) return [];
//
//                        $byProduct = NonConformity::query()
//                            ->whereHas('product.gov_control', fn($q) => $q->where('order_id', $orderId))
//                            ->with('product:id,name')
//                            ->get()
//                            ->mapWithKeys(fn($nc) => [
//                                $nc->id => ($nc->product?->name ? $nc->product->name . ' ' : '') . "(NC #{$nc->id})",
//                            ])
//                            ->toArray();
//
//                        $byMetrology = NonConformity::query()
//                            ->whereHas('metrology_instrument.gov_control', fn($q) => $q->where('order_id', $orderId))
//                            ->with('metrology_instrument:id,name')
//                            ->get()
//                            ->mapWithKeys(fn($nc) => [
//                                $nc->id => ($nc->metrology_instrument?->name ? $nc->metrology_instrument->name . ' ' : '') . "(NC #{$nc->id})",
//                            ])
//                            ->toArray();
//
//                        $byCertificate = NonConformity::query()
//                            ->whereHas('certificate.gov_control', fn($q) => $q->where('order_id', $orderId))
//                            ->with('certificate:id,name')
//                            ->get()
//                            ->mapWithKeys(fn($nc) => [
//                                $nc->id => ($nc->certificate?->name ? $nc->certificate->name . ' ' : '') . "(NC #{$nc->id})",
//                            ])
//                            ->toArray();
//                        $byServices = NonConformity::query()
//                            ->whereHas('service.gov_control', fn($q) => $q->where('order_id', $orderId))
//                            ->with('service:id,name')
//                            ->get()
//                            ->mapWithKeys(fn($nc) => [
//                                $nc->id => ($nc->service?->name ? $nc->service->name . ' ' : '') . "(NC #{$nc->id})",
//                            ])
//                            ->toArray();
//
//                        return array_filter([
//                            'Mahsulot' => $byProduct,
//                            'O‘lchov vositasi' => $byMetrology,
//                            'Sertifikat' => $byCertificate,
//                            'Xizmatlar' => $byServices
//                        ], fn($arr) => !empty($arr));
//                    })
//                    ->searchable()
//                    ->preload()
//                    ->reactive()
//                    ->dehydrated(false)
//                    ->afterStateHydrated(function ($component, $state, $record) {
//                        if (!$record) return;
//                        $selected = \App\Models\NonConformity::where('sanction_payment_request_id', $record->id)
//                            ->pluck('id')
//                            ->all();
//                        $component->state($selected);
//                    }),
//                Forms\Components\Toggle::make('is_paid')
//                    ->required()
//                    ->label('Jarima to\'langanmi'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->numeric()
                    ->label('Talabnoma raqami')
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->date('d-m-Y')
                    ->label('Talabnoma registratsiya qilingan sanasi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('assessed_fine')
                    ->numeric()
                    ->sortable()
                    ->label('Jarima so\'mmasi'),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean()
                    ->label('To\'langanmi'),
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
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSanctionPaymentRequests::route('/'),
            'create' => Pages\CreateSanctionPaymentRequest::route('/create'),
            'edit' => Pages\EditSanctionPaymentRequest::route('/{record}/edit'),
        ];
    }
}
