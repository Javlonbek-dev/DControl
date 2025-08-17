<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('ombudsman_code_number')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('control_days')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('data_from')
                    ->required(),
                Forms\Components\DatePicker::make('data_to')
                    ->required(),
                Forms\Components\DatePicker::make('period_from')
                    ->required(),
                Forms\Components\DatePicker::make('period_to')
                    ->required(),
                Forms\Components\TextInput::make('program_id')
                    ->required()
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
                Tables\Columns\TextColumn::make('ombudsman_code_number')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('control_days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_from')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_to')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('period_from')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('period_to')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program_id')
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
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Kim tomonidan yaratilgan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->label('Kim tomonidan o\'zgartirilgan')
                    ->searchable(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
