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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class NonConformityResource extends Resource
{
    protected static ?string $model = NonConformity::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('product_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('metrology_instrument_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('certificate_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('normative_act_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('written_directive_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('administrative_liability_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('economic_sanction_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('sanction_payment_request_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('created_by')
                    ->numeric(),
                Forms\Components\TextInput::make('updated_by')
                    ->numeric(),
                Forms\Components\Textarea::make('normative_documents')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('metrology_instrument_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('certificate_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('normative_act_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('written_directive_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('administrative_liability_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('economic_sanction_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sanction_payment_request_id')
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
            'index' => Pages\ListNonConformities::route('/'),
            'create' => Pages\CreateNonConformity::route('/create'),
            'edit' => Pages\EditNonConformity::route('/{record}/edit'),
        ];
    }
}
