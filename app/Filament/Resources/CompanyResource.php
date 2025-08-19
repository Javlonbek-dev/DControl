<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;
    protected static ?string $pluralLabel = "Tashkilot";
    protected static ?string $navigationGroup = 'Tashkilot Malumotlari';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Tashkilot nomi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('stir')
                    ->required()
                    ->label('Tashkilot stiri')
                    ->maxLength(255),
                Forms\Components\Select::make('district_id')
                    ->required()
                    ->label('Tuman nomi')
                    ->relationship('district', 'name'),
                Forms\Components\Toggle::make('is_business')
                    ->required()
                    ->label('Tadbirkorlik subyectimi(Beznis ombudsman vakolatidagi tadbirkorlik subyecti)'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Tashkilot nomi'),
                Tables\Columns\TextColumn::make('stir')
                    ->searchable()
                    ->label('Tashkilot stiri'),
                Tables\Columns\TextColumn::make('district.name')
                    ->numeric()
                    ->label('Tuman nomi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_business')
                    ->searchable()
                    ->label('Tadbirkorlik subyektimi'),
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
