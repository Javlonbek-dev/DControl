<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CourtResource\Pages;
use App\Filament\Resources\CourtResource\RelationManagers;
use App\Models\Court;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourtResource extends Resource
{
    protected static ?string $model = Court::class;

    protected static ?string $pluralLabel= "Sudlar";
    protected static ?string $navigationGroup = 'Tashkilot Malumotlari';
//    public static function shouldRegisterNavigation(): bool
//    {
//        return false;
//    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('district_id')
                    ->required()
                    ->relationship('districts', 'name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('districts.name')
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
            'index' => Pages\ListCourts::route('/'),
            'create' => Pages\CreateCourt::route('/create'),
            'edit' => Pages\EditCourt::route('/{record}/edit'),
        ];
    }
}
