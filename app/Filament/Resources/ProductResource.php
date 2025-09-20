<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProductResource extends Resource
{
    protected static ?int $navigationSort = 1;
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = "Tekshiruv malumotlari";
    protected static ?string $pluralLabel = "Mahsulot";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('gov_control_id')
                    ->required()
                    ->relationship('gov_control.order', 'number')
                    ->label('Buyruq raqami'),
                        TextInput::make('name')
                            ->label('Mahsulot nomi')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('amount')
                            ->label('Qoldiq mahsulot soni')
                            ->required(),
                        TextInput::make('price')
                            ->label('1 birilik  mahsulot narxi sumda')
                            ->required(),
                        TextInput::make('type')
                            ->label("O'lchov birligi")
                            ->required(),
                        Forms\Components\Hidden::make('created_by')
                            ->default(fn() => auth()->id())
                            ->dehydrated(true),
                        Forms\Components\Hidden::make('updated_by')
                            ->default(fn() => auth()->id())
                            ->dehydrated(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gov_control.order.number')
                    ->label('Buyruq raqami')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->wrap()
                    ->label('Mahsulot tavsifi haqida malumot'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
