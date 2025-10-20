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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;
    protected static ?string $pluralLabel = "To'lovlar";

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with([
            'administrative_liability.order.company',
            'administrative_liability.orders.company',
            'economic_sanction.order.company',
            'economic_sanction.orders.company',
//            'economic_sanction.company',
            'sanction.company',
        ]);
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
//    public static function shouldRegisterNavigation(): bool
//    {
//        if(auth()->user()?->hasRole('moderator')){
//            return true;
//        }
//        return false;
//    }
    protected static ?string $navigationGroup = "Sanksiyaga oid malumotlar";
//    public static function shouldRegisterNavigation(): bool
//    {
//        return false;
//    }
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('sanction_id')
                    ->required()
                    ->label('Moliyaviy talabnoma raqami')
                    ->relationship('sanction', 'number'),
                Forms\Components\Select::make('economic_sanction_id')
                    ->required()
                    ->label('Moliyaviy jarima raqami')
                    ->relationship('economic_sanction', 'number'),
                Forms\Components\Select::make('administrative_liability_id')
                    ->label('Mamuriy bayonnoma raqami')
                    ->relationship('administrative_liability', 'number'),
                Forms\Components\DatePicker::make('paid_date')
                    ->required()
                    ->label('To\'lov qilingan sanasi'),
                Forms\Components\TextInput::make('paid_ball')
                    ->required()
                    ->label('To\'lov so\'mmasi')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_fallback')
                    ->label('Tashkilot nomi')
                    ->getStateUsing(function ($record) {
                        // 1) Administrative liability yo‘li:
                        $name =
                            // belongsTo: order -> company
//                            $record->administrative_liability?->order?->company?->name
                            // hasMany: orders -> first() -> company
                             $record->administrative_liability?->orders?->first()?->company?->name

                            // 2) Economic sanction yo‘li:
//                            ?? $record->economic_sanction?->order?->company?->name
                            ?? $record->economic_sanction?->orders?->first()?->company?->name
                            // ba’zan economic_sanction bevosita company() bo‘lishi mumkin
//                            ?? $record->economic_sanction?->company?->name

                            // 3) Sanction request yo‘li:
                            ?? $record->sanction?->orders?->first()->company?->name;

                        return $name;
                    })
                    ->searchable(query: function (Builder $query, string $search) {
                        // Postgres bo‘lsa ILIKE qulay; MySQL/MariaDB bo‘lsa LIKE ishlating
                        $cmp = fn($q) => $q->where('name', 'ilike', "%{$search}%");

                        $query
                            // admin liability
                            ->whereHas('administrative_liability.order.company', $cmp)
                            ->orWhereHas('administrative_liability.orders', fn ($q) => $q->whereHas('company', $cmp))

                            // economic sanction
                            ->orWhereHas('economic_sanction.order.company', $cmp)
                            ->orWhereHas('economic_sanction.orders', fn ($q) => $q->whereHas('company', $cmp))
                            ->orWhereHas('economic_sanction.company', $cmp)

                            // sanction payment request
                            ->orWhereHas('sanction.company', $cmp);
                    }),

//                 Tables\Columns\TextColumn::make('sanction_id')
//                    ->numeric()
//                    ->sortable(),
//                Tables\Columns\TextColumn::make('economic_sanction_id')
//                    ->numeric()
//                    ->sortable(),
                Tables\Columns\TextColumn::make('paid_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_amount')
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
