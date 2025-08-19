<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MetrologyInstrumentResource\Pages;
use App\Filament\Resources\MetrologyInstrumentResource\RelationManagers;
use App\Models\MetrologyInstrument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MetrologyInstrumentResource extends Resource
{
    protected static ?string $model = MetrologyInstrument::class;
    protected static ?string $navigationGroup = "Kamchiliklar turlari";
    protected static ?string $pluralLabel = "Metralogiya";

//    public static function shouldRegisterNavigation(): bool
//    {
//        return false;
//    }
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('gov_control_id')
                    ->required()
                    ->label('Tekshiruv raqami')
                    ->searchable()
                    ->relationship('gov_control.order', 'number'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Metralogiya kamchiliklari haqida')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gov_control.number')
                    ->label('Tekshiruv raqami')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Metralogiya kamchiliklari haqida'),
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
            'index' => Pages\ListMetrologyInstruments::route('/'),
            'create' => Pages\CreateMetrologyInstrument::route('/create'),
            'edit' => Pages\EditMetrologyInstrument::route('/{record}/edit'),
        ];
    }
}
