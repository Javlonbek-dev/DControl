<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrittenDirectiveResource\Pages;
use App\Filament\Resources\WrittenDirectiveResource\RelationManagers;
use App\Models\NonConformity;
use App\Models\Order;
use App\Models\WrittenDirective;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class WrittenDirectiveResource extends Resource
{
    protected static ?string $model = WrittenDirective::class;

//    public static function shouldRegisterNavigation(): bool
//    {
//        return false;
//    }
    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }

    protected static ?string $pluralLabel = "Yozma Ko'rsatma";
    protected static ?string $navigationGroup = "Qonuniy Asoslar";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('name')
                    ->required()
                    ->label('Yozma ko\'rsatma matni')
                    ->columnSpanFull(),
                Forms\Components\Select::make('order_id')
                    ->label('Buyruq raqami')
                    ->dehydrated(false)
                    ->options(Order::pluck('number', 'id'))
                    ->searchable()
                    ->preload(),
                Forms\Components\MultiSelect::make('selected_nc_ids')
                    ->label('Non-conformities')
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
                        $selected = \App\Models\NonConformity::where('written_directive_id', $record->id)
                            ->pluck('id')
                            ->all();
                        $component->state($selected);
                    }),
                Forms\Components\Toggle::make('is_ban')->default(false)
                    ->label('Zapret')
                    ->dehydrated(false)
                    ->reactive(),
                Forms\Components\Toggle::make('is_eliminate')->default(false)
                    ->label('Bartarf')
                    ->dehydrated(false)
                    ->reactive(),
                Forms\Components\DatePicker::make('ban_date')
                    ->label('Zapret sanasi')
                    ->visible(fn(callable $get) => $get('is_ban') === true),
                Forms\Components\TextInput::make('ban_number')
                    ->label('Zapret raqami')
                    ->visible(fn(callable $get) => $get('is_ban') === true),
                Forms\Components\TextInput::make('eliminate_number')
                    ->label('Bartaraf raqami')
                    ->visible(fn(callable $get) => $get('is_eliminate') === true),
                Forms\Components\DatePicker::make('eliminate_date')
                    ->label('Bartaraf sanasi')
                    ->visible(fn(callable $get) => $get('is_eliminate') === true),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Yozma ko\'rsatman matni')
                    ->wrap(),
                Tables\Columns\TextColumn::make('ban_number')
                    ->label('Zapret raqami'),
                Tables\Columns\TextColumn::make('ban_date')
                    ->date('m-d-Y')
                    ->label('Zapret sanasi'),
                Tables\Columns\TextColumn::make('eliminate_number')
                    ->label('Bartaraf raqami'),
                Tables\Columns\TextColumn::make('eliminate_date')
                    ->label('Bartaraf sanasi')
                    ->date('m-d-Y'),
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
            'index' => Pages\ListWrittenDirectives::route('/'),
            'create' => Pages\CreateWrittenDirective::route('/create'),
            'edit' => Pages\EditWrittenDirective::route('/{record}/edit'),
        ];
    }
}
