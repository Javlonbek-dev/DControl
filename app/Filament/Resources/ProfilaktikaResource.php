<?php

namespace App\Filament\Resources;

use App\Exports\ProfilaktikaExport;
use App\Filament\Resources\ProfilaktikaResource\Pages;
use App\Filament\Resources\ProfilaktikaResource\RelationManagers;
use App\Imports\ProfilaktikaImport;
use App\Models\Profilaktika;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Calculation\BinaryComparison;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class ProfilaktikaResource extends Resource
{
    protected static ?string $model = Profilaktika::class;
    protected static ?string $pluralLabel = "Profilaktikalar";
//    public static function shouldRegisterNavigation(): bool
//    {
//        return false;
//    }
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('region_id')
                    ->relationship('region', 'name'),
                Forms\Components\Textarea::make('korxona_nomi')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('stir')
                    ->required()
                    ->numeric(),
                Forms\Components\Textarea::make('mahsulot_nomi')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('soha_nomi')
                    ->columnSpanFull(),
                Forms\Components\DatePicker::make('prof_sanasi'),
                Forms\Components\TextInput::make('xat_raqami')
                    ->maxLength(255),
                Forms\Components\TextInput::make('user_id')
                    ->disabled()
                    ->default(Filament::auth()->user()->name)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('regions.region')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stir')
                    ->sortable(),
                TextColumn::make('korxona_nomi')
                    ->sortable(),
                Tables\Columns\TextColumn::make('prof_sanasi')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('xat_raqami')
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
            ->headerActions([
                Action::make('import')
                    ->label('Import')
                    ->form([
                        FileUpload::make('file')
                            ->required()
                            ->disk('local')
                            ->directory('temp-uploads')
                            ->storeFiles()
                            ->preserveFilenames()
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
                    ])->action(function (array $data) {
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

                        Excel::import(new ProfilaktikaImport(), $fullPath);

                        Notification::make()
                            ->title('Excel fayl muvaffaqiyatli import qilindi!')
                            ->success()
                            ->send();
                    }),
                Action::make('export')
                ->label('Export')
                ->action(function (): BinaryFileResponse{
                    return Excel::download(new ProfilaktikaExport(), 'Profilaktika.xlsx');
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
            'index' => Pages\ListProfilaktikas::route('/'),
            'create' => Pages\CreateProfilaktika::route('/create'),
            'edit' => Pages\EditProfilaktika::route('/{record}/edit'),
        ];
    }
}
