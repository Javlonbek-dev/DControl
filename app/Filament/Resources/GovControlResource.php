<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GovControlResource\Pages;
use App\Filament\Resources\GovControlResource\RelationManagers;
use App\Models\GovControl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GovControlResource extends Resource
{
    protected static ?string $model = GovControl::class;
    protected static ?string $pluralLabel= "Tekshiruv";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('real_date_from')
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('gov_control_date')
                    ->required(),
                Forms\Components\DatePicker::make('real_date_to'),
                Forms\Components\Toggle::make('is_finished')->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_date_from')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gov_control_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_date_to')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy')
                    ->label('Kim tomonidan yaratilgan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updatedBy')
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
            'index' => Pages\ListGovControls::route('/'),
            'create' => Pages\CreateGovControl::route('/create'),
            'edit' => Pages\EditGovControl::route('/{record}/edit'),
        ];
    }
}
