<?php

namespace App\Filament\Resources;

use App\Exports\OgohlantirishExport;
use App\Filament\Resources\OgohlantirishResource\Pages;
use App\Filament\Resources\OgohlantirishResource\RelationManagers;
use App\Imports\OgohlantirishImport;
use App\Models\Ogohlantirish;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class OgohlantirishResource extends Resource
{
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    protected static ?string $model = Ogohlantirish::class;

    protected static ?string $pluralLabel= "Ogohlantirishlar";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('stir')
                    ->required()->label("Tashkilot stiri ")
                    ->maxLength(255),
                Forms\Components\TextInput::make('korxona_nomi')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('mahsulot_nomi'),
                Forms\Components\TextInput::make('soha_nomi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('faoliyat_turi')
                    ->maxLength(255),
                Forms\Components\TextInput::make('metralogiya')
                    ->maxLength(255),
                Forms\Components\TextInput::make('standart')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sertifikat')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('ogohlantirish_xati_sanasi')
                    ->required(),
                Forms\Components\TextInput::make('ogohlantirish_xati_raqami')
                    ->required(),
                Forms\Components\DatePicker::make('javob_sanasi'),
                Forms\Components\TextInput::make('javob_raqami')
                    ->maxLength(255),
                Forms\Components\TextInput::make('user_id')
                    ->label('Ijrochi nomi')
                    ->default(Filament::auth()->user()->name)
                    ->disabled()
                    ->required(),
                Forms\Components\Select::make('region_id')
                    ->relationship('region', 'name'),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('stir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('korxona_nomi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mahsulot_nomi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('soha_nomi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('faoliyat_turi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('metralogiya')
                    ->searchable(),
                Tables\Columns\TextColumn::make('standart')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sertifikat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ogohlantirish_xati_sanasi')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('ogohlantirish_xati_raqami')
                    ->searchable(),
                Tables\Columns\TextColumn::make('javob_sanasi')
                    ->date('d/m/Y')
                    ->searchable(),
                Tables\Columns\TextColumn::make('javob_raqami')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable()
                    ->label('Ijrochi Ismi'),
                Tables\Columns\TextColumn::make('region.region_id')
                    ->label('Hudud nomi')
                    ->sortable(),
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
            ->headerActions([
                Action::make('import')
                    ->label('Excel import')
                    ->form([
                        FileUpload::make('file')
                            ->label('Excel faylni tanlang')
                            ->required()
                            ->disk('local')
                            ->directory('temp-uploads')
                            ->storeFiles()
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']),
                    ])
                    ->action(function (array $data) {
                        \Log::info('Uploaded file path:', $data);

                        $filePath = $data['file'];

                        if (!Storage::disk('local')->exists($filePath)) {
                            Notification::make()
                                ->title("Fayl saqlanmadi yoki topilmadi: $filePath")
                                ->danger()
                                ->send();
                            return;
                        }

                        $fullPath = Storage::disk('local')->path($filePath);

                        Excel::import(new OgohlantirishImport(), $fullPath);

                        Notification::make()
                            ->title('Excel fayl muvaffaqiyatli import qilindi!')
                            ->success()
                            ->send();
                    }),
                Action::make('export_excel')
                    ->label('Excel Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (): BinaryFileResponse {
                        return Excel::download(new OgohlantirishExport(), 'sohalar.xlsx');
                    })
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
            'index' => Pages\ListOgohlantirishes::route('/'),
            'create' => Pages\CreateOgohlantirish::route('/create'),
            'edit' => Pages\EditOgohlantirish::route('/{record}/edit'),
        ];
    }
}
