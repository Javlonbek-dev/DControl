<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Filament\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $pluralLabel = "To'lovlar";

    protected static ?string $navigationGroup = "Sanksiyaga oid malumotlar";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sanction_id')
                    ->required()
                    ->relationship('sanction', 'number'),
                Forms\Components\Select::make('economic_sanction_id')
                    ->required()
                    ->relationship('economic_sanction', 'number'),
                Forms\Components\Select::make('administrative_liability_id')
                    ->relationship('administrative_liability', 'number'),
                Forms\Components\DatePicker::make('paid_date')
                    ->required(),
                Forms\Components\TextInput::make('paid_ball')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sanction_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('economic_sanction_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('administrative_liability_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_ball')
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
