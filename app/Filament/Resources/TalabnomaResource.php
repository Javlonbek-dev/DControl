<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalabnomaResource\Pages;
use App\Filament\Resources\TalabnomaResource\RelationManagers;
use App\Models\Talabnoma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TalabnomaResource extends Resource
{
    protected static ?string $model = Talabnoma::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('korxona_nomi')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('inn')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('faoliyat_turi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('hudud_is')
                    ->relationship('hududs', 'hudud_nomi'),
                Forms\Components\TextInput::make('tuman')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('start_tekshiruv'),
                Forms\Components\DateTimePicker::make('end_tekshiruv'),
                Forms\Components\DateTimePicker::make('yubroilgan_vaqti'),
                Forms\Components\TextInput::make('talabnoma_raq')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jarima_sum')
                    ->maxLength(255),
                Forms\Components\TextInput::make('jarima_foizi')
                    ->numeric(),
                Forms\Components\TextInput::make('tekshiruv_holati')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('tulangan_sum')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tulangan_foizi')
                    ->numeric(),
                Forms\Components\DateTimePicker::make('end_date'),
                Forms\Components\Textarea::make('huquqbuzarlik_mazmuni')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('qounun_moddasi')
                    ->columnSpanFull(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('inn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('faoliyat_turi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('hudud_is')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tuman')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_tekshiruv')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_tekshiruv')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('yubroilgan_vaqti')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('talabnoma_raq')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jarima_sum')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jarima_foizi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tekshiruv_holati')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tulangan_sum')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tulangan_foizi')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->dateTime()
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
            'index' => Pages\ListTalabnomas::route('/'),
            'create' => Pages\CreateTalabnoma::route('/create'),
            'edit' => Pages\EditTalabnoma::route('/{record}/edit'),
        ];
    }
}
