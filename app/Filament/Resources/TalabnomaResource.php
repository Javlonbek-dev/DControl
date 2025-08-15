<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalabnomaResource\Pages;
use App\Filament\Resources\TalabnomaResource\RelationManagers;
use App\Models\Talabnoma;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\{Section, TextInput, Select, DateTimePicker, RichEditor};


class TalabnomaResource extends Resource
{
    protected static ?string $model = Talabnoma::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';

    public static function form(Form $form): Form
    {
        $isModerator = auth()->user()?->hasRole('moderator');

        $userFields = [
            TextInput::make('korxona_nomi')->required()->maxLength(65535),
            TextInput::make('inn')->required()->maxLength(255),
            TextInput::make('faoliyat_turi')->required()->maxLength(255),

            // Modelda: public function hudud(){ return $this->belongsTo(Hudud::class); }
            Select::make('hudud_id')->relationship('hudud', 'hudud_nomi')->required(),

            TextInput::make('tuman')->maxLength(255),

            // timestamp ustunlar uchun DateTimePicker qulayroq
            DateTimePicker::make('start_tekshiruv'),
            DateTimePicker::make('end_tekshiruv'),
            DateTimePicker::make('yuborilgan_vaqti'),

            TextInput::make('talabnoma_raq')->label('Talabnoma raqami')->maxLength(255),
        ];

        $moderatorFields = [
            TextInput::make('jarima_sum')->numeric()->prefix('so‘m')
                ->disabled(fn () => ! $isModerator)
                ->dehydrated(fn () => $isModerator),

            TextInput::make('jarima_foizi')->numeric()
                ->disabled(fn () => ! $isModerator)
                ->dehydrated(fn () => $isModerator),

            Select::make('tekshiruv_holati')
                ->options([
                    'draft' => 'Draft',
                    'yuborildi' => 'Yuborildi',
                    'jarimalandi' => 'Jarimalandi',
                    'yopildi' => 'Yopildi',
                ])
                ->required()
                ->disabled(fn () => ! $isModerator)
                ->dehydrated(fn () => $isModerator),

            TextInput::make('tulangan_sum')->numeric()->prefix('so‘m')
                ->disabled(fn () => ! $isModerator)
                ->dehydrated(fn () => $isModerator),

            TextInput::make('tulangan_foizi')->numeric()
                ->disabled(fn () => ! $isModerator)
                ->dehydrated(fn () => $isModerator),

            DateTimePicker::make('end_date')
                ->disabled(fn () => ! $isModerator)
                ->dehydrated(fn () => $isModerator),

            RichEditor::make('huquqbuzarlik_mazmuni')
                ->disabled(fn () => ! $isModerator)
                ->dehydrated(fn () => $isModerator),

            RichEditor::make('qonun_moddasi')
                ->disabled(fn () => ! $isModerator)
                ->dehydrated(fn () => $isModerator),
        ];

        return $form->schema([
            Section::make('Talabnoma ma’lumotlari (foydalanuvchi)')
                ->schema($userFields)
                ->columns(2),

            Section::make('Moderator bo‘limi')
                ->schema($moderatorFields)
                ->columns(2)
                ->hidden(fn () => ! $isModerator),
        ])->columns(2);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('talabnoma_raq')->searchable(),
                Tables\Columns\TextColumn::make('korxona_nomi')->limit(30)->searchable(),
                Tables\Columns\TextColumn::make('inn')->searchable(),
                Tables\Columns\TextColumn::make('tekshiruv_holati')->badge(),
                Tables\Columns\TextColumn::make('user.name')->label('Yaratgan'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => auth()->user()?->hasRole('moderator')
                        || $record->user_id === auth()->id()),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListTalabnomas::route('/'),
            'create' => Pages\CreateTalabnoma::route('/create'),
            'edit' => Pages\EditTalabnoma::route('/{record}/edit'),
        ];
    }
}
