<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GovControlResource\Pages;
use App\Filament\Resources\GovControlResource\RelationManagers;
use App\Models\GovControl;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GovControlResource extends Resource
{
    protected static ?int $navigationSort =3;
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

                Forms\Components\DatePicker::make('sign_date')
                    ->required()
                    ->label('Tilxat olingan sana'),

                Repeater::make('products')
                    ->label('Mahsulotlar')
                    ->relationship('products') // hasMany(Product::class)
                    ->schema([
                        TextInput::make('name')
                            ->label('Mahsulot nomi')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('amount')
                            ->label('Qoldiq mahsulot soni')
                            ->required(),
                        TextInput::make('price')
                            ->label('1 dona mahsulot narxi sumda')
                            ->required(),
                        Forms\Components\Hidden::make('created_by')
                            ->default(fn() => auth()->id())
                            ->dehydrated(true),
                        Forms\Components\Hidden::make('updated_by')
                            ->default(fn() => auth()->id())
                            ->dehydrated(true),
                    ])
                    ->columns(1)
                    ->addActionLabel('Mahsulot qo‘shish')
                    ->defaultItems(0)      // boshlang‘ich elementlar soni
                    ->reorderable(true)    // tartibni o‘zgartirish mumkin
                    ->collapsed()          // UX uchun yig‘ilgan ko‘rinishda turadi
            ])
            ->columns(1);
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
                Tables\Columns\TextColumn::make('sign_date')
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
                // ✅ Tugatildi
                Tables\Actions\Action::make('finish')
                    ->label('Tugatildi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => ! $record->is_finished) // faqat tugallanmaganlarda
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\DatePicker::make('finished_at')
                            ->label('Tugatish sanasi')
                            ->default(now()->toDateString())
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $date = !empty($data['finished_at'])
                            ? Carbon::parse($data['finished_at'])->toDateString()
                            : now()->toDateString();

                        $record->update([
                            'is_finished'   => true,
                            'real_date_to'  => $date,   // tugatish sanasini real_date_to ga yozamiz
                            'updated_by'    => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Tekshiruv tugatildi')
                            ->success()
                            ->send();
                    }),

                // ♻️ Qayta ochish
                Tables\Actions\Action::make('reopen')
                    ->label('Qayta ochish')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')

                    // 1) Faqat tugallangan yozuvda va moderatorga ko‘rinadi:
                    ->visible(fn ($record) =>
                        $record->is_finished && auth()->user()?->hasRole('moderator')
                    )

                    // (ixtiyoriy, Filament v3 da bor) — ruxsatni action darajasida ham tekshiradi:
                    ->authorize(fn () => auth()->user()?->hasRole('moderator'))

                    ->requiresConfirmation()
                    ->action(function ($record) {
                        // 2) Backend guard — to‘g‘ridan-to‘g‘ri URL/JS’dan chaqirilsa ham to‘xtatadi:
                        abort_unless(auth()->user()?->hasRole('moderator'), 403);

                        $record->update([
                            'is_finished'  => false,
                            'real_date_to' => null,
                            'updated_by'   => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Tekshiruv qayta ochildi')
                            ->success()
                            ->send();
                    }),
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
