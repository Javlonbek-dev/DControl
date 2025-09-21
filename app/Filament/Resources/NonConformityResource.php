<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NonConformityResource\Pages;
use App\Filament\Resources\NonConformityResource\RelationManagers;
use App\Models\Criteria;
use App\Models\NonConformity;
use App\Models\NormativeAct;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class NonConformityResource extends Resource
{
    protected static ?int $navigationSort = 4;
    protected static ?string $model = NonConformity::class;

//    public static function shouldRegisterNavigation(): bool
//    {
//        return false;
//    }
    protected static ?string $pluralLabel = "Nomuvofiqliklar";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
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
                Forms\Components\Radio::make('choice')
                    ->label('Tanlang')
                    ->dehydrated(false)
                    ->options([
                        'product' => 'Mahsulot',
                        'metrology' => 'Metrologiya',
                        'certificate' => 'Sertifikat',
                        'service' => 'Xizmat',
                    ])
                    ->inline()
                    ->reactive(),

                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label('Mahsulot nomi')
                    ->visible(fn (callable $get) => $get('choice') === 'product'),

                Forms\Components\Select::make('metrology_instrument_id')
                    ->relationship('metrology_instrument', 'name')
                    ->label('Metrologiya')
                    ->visible(fn (callable $get) => $get('choice') === 'metrology'),

                Forms\Components\Select::make('certificate_id')
                    ->relationship('certificate', 'name')
                    ->label('Sertifikat')
                    ->visible(fn (callable $get) => $get('choice') === 'certificate'),
                Forms\Components\Select::make('service_id')
                    ->relationship('service', 'name')
                    ->label('Xizmatlar')
                    ->visible(fn (callable $get) => $get('choice') === 'service'),
                CheckboxList::make('criteria')
                    ->label('Kriteriyalar')
                    ->relationship('criteria', 'name')
                    ->options(function (Get $get) {
                        $type = $get('choice');
                        return Criteria::query()
                            ->forType($type)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->visible(fn (Get  $get) => filled($get('choice')))
                    ->columns(2)
                    ->reactive(),
                Forms\Components\Select::make('normative_act_id')
                    ->label('Normativ-huquqiy asoslar')
                    ->multiple()
                    ->options(NormativeAct::pluck('name', 'id')->toArray())
                    ->preload()
                    ->searchable()
                    ->default([]),
                Forms\Components\Textarea::make('normative_documents')
                    ->required()
                    ->label('Normativ hujjat')
                    ->columnSpanFull(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Tashkilot')
                    ->state(function ($record) {
                        return $record->product?->gov_control?->order?->company?->name
                            ?? $record->certificate?->gov_control?->order?->company?->name
                            ?? $record->metrology_instrument?->gov_control?->order?->company?->name
                            ?? $record->service?->gov_control?->order?->company?->name
                            ?? '—';
                    })
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('product.name')
                    ->numeric()
                    ->label('Mahsulot nomi')
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('metrology_instrument.name')
                    ->numeric()
                    ->label('Metralogiya')
                    ->sortable(),
                Tables\Columns\TextColumn::make('certificate.name')
                    ->numeric()
                    ->label('Sertifikat')
                    ->sortable(),
                TextColumn::make('service.name')
                    ->searchable()
                    ->wrap()
                    ->label('Xizmatlar'),
                TextColumn::make('normative_acts_names')
                    ->label('Normativ huquqiy asoslar')
                    ->wrap(),
                TextColumn::make('criterias_grouped')
                    ->label('Mezonlar (guruhlab)')
                    ->state(function ($record) {
                        $byType = $record->criteria->groupBy('type'); // ['product'=>..., 'metrology'=>..., ...]

                        $parts = [];
                        foreach (['product', 'metrology', 'certificate', 'service'] as $type) {
                            if (!empty($byType[$type])) {

                                $names = $byType[$type]->pluck('name')->join(', ');
                                if($type == 'product'){
                                    $type = "Mahsulot";
                                }
                                if($type == 'metrology'){
                                    $type = "O'lchov vositalari";
                                }
                                if($type == 'certificate'){
                                    $type = "Sertifikat";
                                }
                                if($type == 'service'){
                                    $type = "Xizmatlar";
                                }
                                $parts[] = ucfirst($type) . ': ' . $names;
                            }
                        }

                        return $parts ? implode(" | ", $parts) : '—';
                    })
                    ->wrap(),
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
            'index' => Pages\ListNonConformities::route('/'),
            'create' => Pages\CreateNonConformity::route('/create'),
            'edit' => Pages\EditNonConformity::route('/{record}/edit'),
        ];
    }
}
