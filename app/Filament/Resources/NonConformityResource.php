<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NonConformityResource\Pages;
use App\Filament\Resources\NonConformityResource\RelationManagers;
use App\Models\Criteria;
use App\Models\NonConformity;
use App\Models\NormativeAct;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class NonConformityResource extends Resource
{
    protected static ?int $navigationSort = 4;
    protected static ?string $model = NonConformity::class;

//    public static function shouldRegisterNavigation(): bool
//    {
//        return false;
//    }
    protected static ?string $pluralLabel = "Nomuvofiqliklar";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole('moderator');
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Asosiy ma’lumotlar')
                ->schema([
                    Forms\Components\Radio::make('choice')
                        ->label('Tanlang')
                        ->dehydrated(false)
                        ->options([
                            'product' => 'Mahsulot',
//                            'metrology' => 'Metrologiya',
//                            'certificate' => 'Sertifikat',
                            'service' => 'Xizmat',
                        ])
                        ->inline()
                        ->reactive(),

                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'name')
                        ->label('Mahsulot nomi')
                        ->visible(fn(callable $get) => $get('choice') === 'product'),

                    Forms\Components\Select::make('service_id')
                        ->relationship('service', 'name')
                        ->label('Xizmatlar')
                        ->visible(fn(callable $get) => $get('choice') === 'service'),

                    Forms\Components\CheckboxList::make('criteria')
                        ->label('Kriteriyalar')
                        ->relationship('criteria', 'name')
                        ->options(function (Get $get) {
                            $type = $get('choice');
                            return Criteria::query()
                                ->when($type, fn($q) => method_exists(Criteria::class, 'scopeForType') ? $q->forType($type) : $q)
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->toArray();
                        })
                        ->visible(fn(Get $get) => filled($get('choice')))
                        ->columns(2)
                        ->reactive(),

                    Forms\Components\Select::make('normative_act_id')
                        ->label('Normativ-huquqiy asoslar')
                        ->multiple()
                        ->options(fn() => NormativeAct::pluck('name', 'id')->toArray())
                        ->preload()
                        ->searchable()
                        ->default([]),

//                    Forms\Components\Textarea::make('normative_documents')
//                        ->label('Normativ hujjat')
//                        ->columnSpanFull(),
                ])
                ->columns(1),

            Section::make('Dastlabki kamchiliklar')
                ->description('Tekshiruv davomida har kuni topilgan kamchiliklar. Cheksiz qo‘shish mumkin.')
                ->schema([
                    Forms\Components\HasManyRepeater::make('findings')
                        ->relationship('findings')
                        ->label('Kamchiliklar')
                        ->schema([
                            Forms\Components\DatePicker::make('detected_at')
                                ->label('Sana')
                                ->native(false)
                                ->default(fn() => now()->toDateString()),

                            Forms\Components\TextInput::make('day_no')
                                ->label('Kun #')
                                ->numeric()
                                ->default(function ($get, $set, $record) {
                                    $existing = $record?->findings()->max('day_no') ?? 0;
                                    return $existing + 1;
                                })
                                ->helperText('Ixtiyoriy, avtomatik taklif qilinadi.'),

                            Forms\Components\Textarea::make('description')
                                ->label('Dastlabki kamchilik')
                                ->required()
                                ->columnSpanFull(),

                            Actions::make([
                                Action::make('copyFromLast')
                                    ->label('Kechagidan nusxa olish')
                                    ->action(function ($livewire, $get, $set) {
                                        $record = method_exists($livewire, 'getRecord') ? $livewire->getRecord() : null;
                                        if (!$record) {
                                            Notification::make()->title('Hali yozuv yaratilmagan. Avval saqlang.')->warning()->send();
                                            return;
                                        }

                                        $last = $record->findings()
                                            ->orderByDesc('detected_at')
                                            ->orderByDesc('id')
                                            ->first();

                                        if ($last) {
                                            $set('description', $last->description);
                                            Notification::make()->title('Kechagi matn nusxa qilindi.')->success()->send();
                                        } else {
                                            Notification::make()->title('Oldingi yozuv topilmadi.')->warning()->send();
                                        }
                                    }),
                            ])->alignLeft(),
                        ])
                        ->orderable(false)
                        ->defaultItems(0)
                        ->minItems(0)
                        ->addActionLabel('Aniqlangan kamchilik qo‘shish')
                        ->hidden(fn($record) => $record?->isFinalized()),
                ])
                ->collapsed(false),

            Section::make('Yakuniy xulosa')
                ->hidden(fn($record) => $record === null)
                ->schema([
                    Forms\Components\Textarea::make('final_description')
                        ->label('Yakuniy kamchiliklar (xulosa)')
                        ->disabled(fn($record) => $record?->isFinalized())
                        ->columnSpanFull(),

                    Actions::make([
                        Action::make('finalize')
                            ->label('Yakunlash')
                            ->color('success')
                            ->icon('heroicon-o-check-circle')
                            ->requiresConfirmation()
                            ->visible(fn($record) => $record && !$record->isFinalized())
                            ->action(function (\App\Models\NonConformity $record) {
                                if ($record->findings()->count() === 0) {
                                    Notification::make()->title('Avval kamida bitta dastlabki kamchilik kiriting.')->warning()->send();
                                    return;
                                }
                                if (blank($record->final_description)) {
                                    Notification::make()->title('Yakuniy xulosani kiriting.')->warning()->send();
                                    return;
                                }
                                $record->finalized_at = now();
                                $record->finalized_by = auth()->id();
                                $record->save();

                                Notification::make()->title('Ish holati yakunlandi.')->success()->send();
                            }),
                    ])->alignRight(),
                ])
                ->collapsed(false),
        ])->columns(1);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name')
                    ->label('Tashkilot')
                    ->state(function ($record) {
                        return $record->product?->gov_control?->order?->company?->name
                            ?? $record->certificate?->gov_control?->order?->company?->name
                            ?? $record->metrology_instrument?->gov_control?->order?->company?->name
                            ?? $record->service?->gov_control?->order?->company?->name
                            ?? '—';
                    })
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('product.name')
                    ->numeric()
                    ->label('Mahsulot nomi')
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('metrology_instrument.name')
                    ->numeric()
                    ->label('Metralogiya')
                    ->sortable(),
                Tables\Columns\TextColumn::make('certificate.name')
                    ->numeric()
                    ->label('Sertifikat')
                    ->sortable(),
                TextColumn::make('service.name')
                    ->searchable()
                    ->wrap()
                    ->label('Xizmatlar'),
                TextColumn::make('normative_acts_names')
                    ->label('Normativ huquqiy asoslar')
                    ->wrap(),
                TextColumn::make('criterias_grouped')
                    ->label('Mezonlar (guruhlab)')
                    ->state(function ($record) {
                        $byType = $record->criteria->groupBy('type');

                        $parts = [];
                        foreach (['product', 'metrology', 'certificate', 'service'] as $type) {
                            if (!empty($byType[$type])) {

                                $names = $byType[$type]->pluck('name')->join(', ');
                                if($type == 'product'){
                                    $type = "Mahsulot";
                                }
                                if($type == 'metrology'){
                                    $type = "O'lchov vositalari";
                                }
                                if($type == 'certificate'){
                                    $type = "Sertifikat";
                                }
                                if($type == 'service'){
                                    $type = "Xizmatlar";
                                }
                                $parts[] = ucfirst($type) . ': ' . $names;
                            }
                        }

                        return $parts ? implode(" | ", $parts) : '—';
                    })
                    ->wrap(),
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
