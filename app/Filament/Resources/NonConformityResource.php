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

class NonConformityResource extends Resource
{
    protected static ?string $model = NonConformity::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static ?string $pluralLabel = "Nomuvofiqliklar";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->label('Mahsulot nomi')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\Select::make('gov_control_id')
                            ->relationship('gov_control', 'number')
                            ->required()
                    ]),
                Forms\Components\Select::make('metrology_instrument_id')
                    ->relationship('metrology_instrument', 'name')
                    ->label('Metralogiya'),
                Forms\Components\Select::make('certificate_id')
                    ->relationship('certificate', 'name')
                    ->label('Sertifikat'),
                Forms\Components\Select::make('normative_act_id')
                    ->relationship('normative_act', 'name')
                    ->label('Natmativ huquqiy asos')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Huquqiy hujjat asosi')
                    ]),
                Forms\Components\Select::make('written_directive_id')
                    ->label('Yozama ko\'rsatma')
                    ->relationship('written_directive', 'name')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label('Yozama ko\'rsatma matni'),
                    ]),
                Forms\Components\Select::make('administrative_liability_id')
                    ->required()
                    ->label('Mamuriy bayonnoma nomeri')
                    ->relationship('administrative_liability', 'number'),
                Forms\Components\Select::make('economic_sanction_id')
                    ->required()
                    ->label('Moliyaviy sanksiya')
                    ->relationship('economic_sanction', 'number'),
                Forms\Components\Select::make('sanction_payment_request_id')
                    ->required()
                    ->label('Moliyaviyga so\'lash uchun talabnoma')
                    ->relationship('sanction', 'number'),
                Forms\Components\Textarea::make('normative_documents')
                    ->required()
                    ->label('Narmative hujjat')
                    ->columnSpanFull(),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->numeric()
                    ->label('Mahsulot nomi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('metrology_instrument.name')
                    ->numeric()
                    ->label('Metralogiya')
                    ->sortable(),
                Tables\Columns\TextColumn::make('certificate.name')
                    ->numeric()
                    ->label('Sertifikat')
                    ->sortable(),
                Tables\Columns\TextColumn::make('normative.name')
                    ->numeric()
                    ->label('Natmativ huquqiy asos')
                    ->sortable(),
                Tables\Columns\TextColumn::make('written_directive.name')
                    ->numeric()
                    ->label('Yozma ko\'rsatma')
                    ->sortable(),
                Tables\Columns\TextColumn::make('administrative_liability.number')
                    ->numeric()
                    ->label('Mamuriy bayonnoma nomeri')
                    ->sortable(),
                Tables\Columns\TextColumn::make('economic_sanction_id')
                    ->numeric()
                    ->label('Moliyaviyga jarima')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sanction_payment_request.number')
                    ->numeric()
                    ->label('Moliyaviyga qo\'lash uchun talabnoma')
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
