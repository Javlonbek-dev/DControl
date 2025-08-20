<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WrittenDirectiveResource\Pages;
use App\Filament\Resources\WrittenDirectiveResource\RelationManagers;
use App\Models\WrittenDirective;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WrittenDirectiveResource extends Resource
{
    protected static ?string $model = WrittenDirective::class;

//    public static function shouldRegisterNavigation(): bool
//    {
//        return false;
//    }

    protected static ?string $pluralLabel = "Yozma Ko'rsatma";
    protected static ?string $navigationGroup = "Qonuniy Asoslar";

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_ban')->default(false)
                    ->label('Zapret')
                    ->reactive(),
                Forms\Components\Toggle::make('is_eliminate')->default(false)
                    ->label('Bartarf')
                    ->reactive(),
                Forms\Components\DatePicker::make('ban_date')
                    ->visible(fn(callable $get) => $get('is_ban') === true),
                Forms\Components\TextInput::make('ban_raqam')
                    ->visible(fn(callable $get) => $get('is_zapret') === true),
                Forms\Components\TextInput::make('eliminate_number')
                    ->visible(fn(callable $get) => $get('is_eliminate') === true),
                Forms\Components\DatePicker::make('eliminate_date')
                    ->visible(fn(callable $get) => $get('is_eliminate') === true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
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
            'index' => Pages\ListWrittenDirectives::route('/'),
            'create' => Pages\CreateWrittenDirective::route('/create'),
            'edit' => Pages\EditWrittenDirective::route('/{record}/edit'),
        ];
    }
}
