<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NonConformityResource\Pages;
use App\Filament\Resources\NonConformityResource\RelationManagers;
use App\Models\NonConformity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NonConformityResource extends Resource
{
    protected static ?string $model = NonConformity::class;

//    public static function shouldRegisterNavigation(): bool
//    {
//        return false;
//    }
    protected static ?string $pluralLabel = "Nomuvofiqliklar";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ])
                    ->inline()
                    ->reactive(),

                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->searchable()
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

                Forms\Components\Select::make('normative_act_id')
                    ->relationship('normative_act', 'name')
                    ->label('Natmativ huquqiy asos')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Huquqiy hujjat asosi')
                    ]),
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
                Tables\Columns\TextColumn::make('normative_act.name')
                    ->numeric()
                    ->wrap()
                    ->label('Natmativ huquqiy asos')
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
            'index' => Pages\ListNonConformities::route('/'),
            'create' => Pages\CreateNonConformity::route('/create'),
            'edit' => Pages\EditNonConformity::route('/{record}/edit'),
        ];
    }
}
