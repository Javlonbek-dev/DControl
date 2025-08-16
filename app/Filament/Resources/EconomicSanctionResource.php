<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EconomicSanctionResource\Pages;
use App\Filament\Resources\EconomicSanctionResource\RelationManagers;
use App\Models\EconomicSanction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EconomicSanctionResource extends Resource
{
    protected static ?string $model = EconomicSanction::class;
    protected static ?string $pluralLabel = "Moliyaviy Sanksiya";
    protected static ?string $navigationGroup = "Sanksiyaga oid malumotlar";


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
                Forms\Components\TextInput::make('court_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('decision_date')
                    ->required(),
                Forms\Components\TextInput::make('decision_number')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('decision_type_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('imposed_fine')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_paid')
                    ->required(),
                Forms\Components\TextInput::make('sanction_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('created_by')
                    ->numeric(),
                Forms\Components\TextInput::make('updated_by')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('registration_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assessed_fine')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('court_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('decision_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('decision_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('decision_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('imposed_fine')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sanction_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
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
            'index' => Pages\ListEconomicSanctions::route('/'),
            'create' => Pages\CreateEconomicSanction::route('/create'),
            'edit' => Pages\EditEconomicSanction::route('/{record}/edit'),
        ];
    }
}
