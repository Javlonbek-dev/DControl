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
                Forms\Components\Select::make('product_id')
                    ->required()
                    ->relationship('product', 'name'),
                Forms\Components\Select::make('metrology_instrument_id')
                    ->required()
                    ->relationship('metrology_instrument', 'name'),
                Forms\Components\Select::make('certificate_id')
                    ->required()
                    ->relationship('certificate', 'name'),
                Forms\Components\TextInput::make('normative_act_id')
                    ->required(),
//                    ->relationship('normative_act', 'name'),
                Forms\Components\TextInput::make('written_directive_id')
                    ->required(),
//                    ->relationship('written_directive', 'name'),
                Forms\Components\TextInput::make('administrative_liability_id')
                    ->required(),
//                    ->relationship('administrative_liabilitie', 'number'),
                Forms\Components\TextInput::make('economic_sanction_id')
                    ->required(),
//                    ->relationship('economic_sanction', 'number'),
                Forms\Components\TextInput::make('sanction_payment_request_id')
                    ->required(),
//                    ->relationship('sanction_payment_request', 'number'),
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
            'index' => Pages\ListNonConformities::route('/'),
            'create' => Pages\CreateNonConformity::route('/create'),
            'edit' => Pages\EditNonConformity::route('/{record}/edit'),
        ];
    }
}
