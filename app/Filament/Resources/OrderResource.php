<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $pluralLabel = "Buyruqlar";


    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->label('Buyruq nomeri')
                    ->numeric(),
                Forms\Components\TextInput::make('ombudsman_code_number')
                    ->required()
                    ->label('Ombudsmandan olingan raqam')
                    ->numeric(),
                Forms\Components\TextInput::make('control_days')
                    ->required()
                    ->label('Tekshiruv o\'tkaziladigan kunlar')
                    ->numeric(),
                Forms\Components\DatePicker::make('data_from')
                    ->required()
                    ->label('Tekshiruv boshlanadigan sana'),
                Forms\Components\DatePicker::make('data_to')
                    ->required()
                    ->label('Tekshiruv tugash sanasi'),
                Forms\Components\DatePicker::make('period_from')
                    ->required()
                    ->label('Tekshiruv davrini boshlanishi'),
                Forms\Components\DatePicker::make('period_to')
                    ->required()
                    ->label('Tekshiruv davrini tugash sanasi'),
                Forms\Components\Select::make('company_type_id')
                    ->required()
                    ->relationship('company_type', 'name')
                    ->label('Faoliyat turi')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                    ]),
                Forms\Components\Toggle::make('is_district')
                    ->default(false)
                    ->label('Filailmi')
                    ->reactive(),
                Forms\Components\Select::make('district_id')
                    ->relationship('district', 'name')
                    ->searchable()
                    ->label('Tuman')
                    ->preload()
                    ->visible(fn(callable $get) => $get('is_district') === true)
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Tuman')
                            ->required(),
                        Forms\Components\Select::make('region_id')
                            ->relationship('region', 'name')
                    ]),
                Forms\Components\Select::make('company_id')
                    ->required()
                    ->searchable()
                    ->label('Tashkilot nomi')
                    ->relationship('company', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Tashkilot nomi')
                            ->required(),
                        TextInput::make('stir')
                            ->numeric()
                            ->required(),
                        Forms\Components\Select::make('district_id')
                            ->relationship('district', 'name')
                            ->searchable(),
                        Forms\Components\Toggle::make('is_business')
                            ->default(false)
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->numeric()
                    ->label('Buyruq raqami')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ombudsman_code_number')
                    ->numeric()
                    ->label('Ombudsmandan olingan raqam')
                    ->sortable(),
                Tables\Columns\TextColumn::make('control_days')
                    ->numeric()
                    ->label('Tekshiruv o\'tkaziladigan kunlar')
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('data_from')
                    ->date()
                    ->label('Tekshiruv boshlanadigan sana')
                    ->sortable(),
                Tables\Columns\TextColumn::make('data_to')
                    ->date()
                    ->label('Tekshiruv tugagan sana')
                    ->sortable(),
                Tables\Columns\TextColumn::make('period_from')
                    ->date()
                    ->label('Tekshiruv davrini boshlanishi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('period_to')
                    ->date()
                    ->label('Tekshiruv davrini tugash sanasi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('company_type.name')
                    ->numeric()
                    ->label('Faoliyat turi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->numeric()
                    ->label('Tuman')
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->numeric()
                    ->label('Tashkilot nomi')
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
