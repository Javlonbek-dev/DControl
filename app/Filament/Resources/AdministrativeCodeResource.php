<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdministrativeCodeResource\Pages;
use App\Filament\Resources\AdministrativeCodeResource\RelationManagers;
use App\Models\AdministrativeCode;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdministrativeCodeResource extends Resource
{
    protected static ?string $model = AdministrativeCode::class;

    protected static ?string $pluralLabel = "Ma'muriy javobgarlik to'g'risidagi kodeks";
    protected static ?string $navigationGroup = "Qonuniy Asoslar";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('article')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('normative_act_id')
                    ->relationship('normative_act', 'name')
                    ->required(),
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
                Tables\Columns\TextColumn::make('article')
                    ->searchable(),
                Tables\Columns\TextColumn::make('normative_act.name')
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
            'index' => Pages\ListAdministrativeCodes::route('/'),
            'create' => Pages\CreateAdministrativeCode::route('/create'),
            'edit' => Pages\EditAdministrativeCode::route('/{record}/edit'),
        ];
    }
}
