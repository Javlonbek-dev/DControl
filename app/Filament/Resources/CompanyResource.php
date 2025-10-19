<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use App\Models\District;
use App\Models\Region;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class CompanyResource extends Resource
{
    protected static ?int $navigationSort = 1;
    protected static ?string $model = Company::class;
    protected static ?string $pluralLabel = "Tashkilot";
//    protected static ?string $navigationGroup = 'Tashkilot Malumotlari';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Tashkilot nomi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('stir')
                    ->required()
                    ->label('Tashkilot stiri')
                    ->maxLength(255),
                Forms\Components\Select::make('region_id')
                    ->label('Viloyat')
                    ->options(fn () => Region::orderBy('name')->pluck('name', 'id')->toArray())
                    ->searchable()
                    ->preload()
                    ->dehydrated(false) // << MUHIM: DBga yubormaydi
                    ->reactive() // districtni qayta yuklash uchun
                    ->afterStateUpdated(fn (Set $set) => $set('district_id', null)) // viloyat o'zgarsa tumanni tozalash
                    ->required(),

                Forms\Components\Select::make('district_id')
                    ->label('Tuman nomi')
                    ->options(fn (Get $get) =>
                    blank($get('region_id'))
                        ? []
                        : District::where('region_id', $get('region_id'))
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray()
                    )
                    ->searchable()
                    ->preload()
                    ->disabled(fn (Get $get) => blank($get('region_id')))
                    ->required()
                    ->rule('exists:districts,id'),
                Forms\Components\Toggle::make('is_business')
                    ->required()
                    ->label('Tashkilot turi: Tadbirkorlik subektimi'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Tashkilot nomi'),
                Tables\Columns\TextColumn::make('stir')
                    ->label('Tashkilot STIRi')
                    ->searchable()
                    ->formatStateUsing(function ($state) {
                        if ($state && strlen($state) === 9) {
                            return substr($state, 0, 3) . ' ' .
                                substr($state, 3, 3) . ' ' .
                                substr($state, 6, 3);
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('district.name')
                    ->label('Tashkilot joylashgan hududi')
                    ->state(function ($record) {
                        return optional($record->district->region)->name
                            . ', ' .
                            optional($record->district)->name. ' tumani';
                    }),
                Tables\Columns\TextColumn::make('is_business')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => $state ? 'DN' : 'DT')
                    ->label('Tadbirkorlik subyekt shakli'),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Kim tomonidan yaratilgan '),
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

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
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
            'index' => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'edit' => Pages\EditCompany::route('/{record}/edit'),
        ];
    }
}
