<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Filament\Resources\ProgramResource\RelationManagers;
use App\Models\Program;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $pluralLabel = "Dasturlar";
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
                TextInput::make('number')
                ->required()
                ->label('Dastur nomeri'),
                Forms\Components\DatePicker::make('program_date')
                ->required()
                ->label('Dastur sanasi'),
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
                    ->visible(fn (callable $get) => $get('is_district') === true)
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
                Tables\Columns\TextColumn::make('company_type.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
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
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }
}
