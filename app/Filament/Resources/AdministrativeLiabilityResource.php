<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdministrativeLiabilityResource\Pages;
use App\Filament\Resources\AdministrativeLiabilityResource\RelationManagers;
use App\Models\AdministrativeLiability;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdministrativeLiabilityResource extends Resource
{
    protected static ?string $model = AdministrativeLiability::class;

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
                Forms\Components\Select::make('decision_type_id')
                    ->required()
                    ->relationship('decision_type', 'name'),
                Forms\Components\DatePicker::make('decision_date')
                    ->required(),
                Forms\Components\TextInput::make('imposed_fine')
                    ->required()
                    ->numeric(),
                Forms\Components\Toggle::make('is_paid')
                    ->required(),
                Forms\Components\Select::make('bxm_id')
                    ->required()
                    ->relationship('bxm', 'quantity'),
                Forms\Components\TextInput::make('person_full_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('person_passport')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('profession_id')
                    ->required()
                    ->relationship('profession', 'name'),
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
                Tables\Columns\TextColumn::make('decision_type.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('decision_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('imposed_fine')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('bxm.quality')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('person_full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('person_passport')
                    ->searchable(),
                Tables\Columns\TextColumn::make('profession.name')
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
