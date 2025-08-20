<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GovControlResource\Pages;
use App\Filament\Resources\GovControlResource\RelationManagers;
use App\Models\GovControl;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GovControlResource extends Resource
{
    protected static ?string $model = GovControl::class;
    protected static ?string $pluralLabel= "Tekshiruv";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('order_id')
                    ->required()
                    ->label('Buyruq raqami')
                    ->relationship('order', 'number'),
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->label('Tekshiruv raqami')
                    ->numeric(),
                Forms\Components\DatePicker::make('real_date_from')
                    ->required()
                    ->label('Tilxat olingan sana')
                    ->live(debounce: 0)
                    ->afterStateUpdated(function (Set $set, $state) {
                        if ($state) {
                            $set('real_date_to', Carbon::parse($state)->addDays(10)->format('Y-m-d'));
                        } else {
                            $set('real_date_to', null);
                        }
                    }),

                Forms\Components\DatePicker::make('real_date_to')
                    ->readOnly()
                    ->label('Tekshiruvni tugatish sanasi')
                    ->afterStateHydrated(function (Get $get, Set $set, $state) {
                        if (!$state && $get('real_date_from')) {
                            $set('real_date_to', Carbon::parse($get('real_date_from'))->addDays(10)->format('Y-m-d'));
                        }
                    }),
                Forms\Components\Toggle::make('is_finished')->default(false)
                    ->visible(fn () => auth()->user()?->hasRole('moderator'))
                    ->label('Tekshiruv tugatildimi')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.company.name')
                    ->searchable()
                    ->label('Tashkilot nomi'),
                Tables\Columns\TextColumn::make('order.number')
                    ->numeric()
                    ->label('Buyruq raqami')
                    ->sortable(),
                Tables\Columns\TextColumn::make('number')
                    ->label('Tekshiruv raqami')
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_date_from')
                    ->date('d-m-Y')
                    ->label('Tilxat olingan sana')
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_date_to')
                    ->date('d-m-Y')
                    ->label('Tekshiruvni tugatish sanasi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('is_finished')
                    ->label('Tekshiruv tugatilganmi')
                    ->badge()
                    ->icon(fn($record) => $record->is_finished
                        ? 'heroicon-o-check-circle'
                        : 'heroicon-o-x-circle'
                    )
                    ->color(fn($record) => $record->is_finished ? 'success' : 'danger')
                    ->formatStateUsing(fn($state) => $state ? 'Tugatilgan' : 'Tugatilmagan'),
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
                Tables\Actions\ViewAction::make(),
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
            'view' => Pages\ViewGovControlResource::route('/{record}'),

        ];
    }
}
