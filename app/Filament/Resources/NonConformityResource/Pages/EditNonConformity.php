<?php

namespace App\Filament\Resources\NonConformityResource\Pages;

use App\Filament\Resources\NonConformityResource;
use App\Models\Criteria;
use App\Models\NonConformity;
use App\Models\NormativeAct;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditNonConformity extends EditRecord
{
    protected static string $resource = NonConformityResource::class;

    /** EDIT sahifasi uchun maxsus schema */
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Asosiy ma’lumotlar')
                ->schema([
                    Forms\Components\Radio::make('choice')
                        ->label('Tanlang')
                        ->dehydrated(false)
                        ->reactive()
                        ->afterStateHydrated(function ($component, $state, $record) {
                            if ($state || !$record) return;
                            $component->state(
                                $record->product_id ? 'product' :
                                    ($record->metrology_instrument_id ? 'metrology' :
                                        ($record->certificate_id ? 'certificate' :
                                            ($record->service_id ? 'service' : null)))
                            );
                        })
                        ->disabled(),

                    Forms\Components\Select::make('product_id')
                        ->relationship('product', 'name')
                        ->label('Mahsulot nomi')
                        ->visible(fn(Get $get, $record) => $get('choice') === 'product' || ($record && $record->product_id)
                        )
                        ->disabled()
                        ->dehydrated(false),

                    Forms\Components\Select::make('metrology_instrument_id')
                        ->relationship('metrology_instrument', 'name')
                        ->label('Metrologiya')
                        ->visible(fn(Get $get, $record) => $get('choice') === 'metrology' || ($record && $record->metrology_instrument_id)
                        )
                        ->disabled()
                        ->dehydrated(false),

                    Forms\Components\Select::make('certificate_id')
                        ->relationship('certificate', 'name')
                        ->label('Sertifikat')
                        ->visible(fn(Get $get, $record) => $get('choice') === 'certificate' || ($record && $record->certificate_id)
                        )
                        ->disabled()
                        ->dehydrated(false),

                    Forms\Components\Select::make('service_id')
                        ->relationship('service', 'name')
                        ->label('Xizmatlar')
                        ->visible(fn(Get $get, $record) => $get('choice') === 'service' || ($record && $record->service_id)
                        )
                        ->disabled()
                        ->dehydrated(false),

                    Forms\Components\CheckboxList::make('criteria_ids') // ← relationship YO'Q
                    ->label('Kriteriyalar')
                        ->options(fn() => Criteria::orderBy('name')->pluck('name', 'id')->toArray())
                        ->default(fn($record) => $record?->criteria()->pluck('id')->all() ?? [])
                        ->columns(2)
                        ->disabled(fn($record) => $record?->isFinalized())
                        ->helperText('Mavjud belgilardan olib tashlab bo‘lmaydi, faqat yangilarini qo‘shish mumkin.')
                        ->saveRelationshipsUsing(function ($component, $state, NonConformity $record) {
                            if ($record->isFinalized()) return;
                            $relation = $record->criteria();
                            $table = $relation->getRelated()->getTable();
                            $current = $relation->distinct()->pluck("$table.id")->all();
                            $incoming = collect($state ?? [])->map(fn($v) => (int)$v)->all();
                            $toAttach = array_diff($incoming, $current);
                            if (!empty($toAttach)) {
                                $record->criteria()->attach($toAttach);
                            }
                        }),

                    Forms\Components\Select::make('normative_act_id')
                        ->label('Normativ-huquqiy asoslar')
                        ->multiple()
                        ->options(fn() => NormativeAct::pluck('name', 'id')->toArray())
                        ->preload()
                        ->disabled(fn($record) => $record?->isFinalized())
                        ->searchable()
                        ->helperText('Mavjudlardan olib tashlab bo‘lmaydi, faqat yangilarini qo‘shing.'),

                    Forms\Components\Textarea::make('normative_documents')
                        ->label('Normativ hujjat')
                        ->disabled(fn($record) => $record?->isFinalized())
                    ,
                ])
                ->columns(2),

            Forms\Components\Section::make('Dastlabki kamchiliklar')
                ->description('Tekshiruv davomida har kuni topilgan kamchiliklar. Cheksiz qo‘shish mumkin.')
                ->schema([
                    Forms\Components\HasManyRepeater::make('findings')
                        ->relationship('findings')
                        ->schema([
                            Forms\Components\DatePicker::make('detected_at')
                                ->label('Sana')
                                ->format('D.M.YYYY')
                                ->native(false)
                                ->default(fn() => now()->toDateString())
                                ->disabled(fn(Forms\Get $get) => filled($get('id'))),

                            Forms\Components\TextInput::make('day_no')
                                ->label('Kun #')
                                ->numeric()
                                ->default(function ($get, $set, $record) {
                                    $existing = $record?->findings()->max('day_no') ?? 0;
                                    return $existing + 1;
                                })
                                ->helperText('Ixtiyoriy, avtomatik taklif qilinadi.')
                                ->disabled(fn(Forms\Get $get) => filled($get('id'))),

                            Forms\Components\Textarea::make('description')
                                ->label('Dastlabki kamchilik')
                                ->required()
                                ->columnSpanFull()
                                ->disabled(fn(Forms\Get $get) => filled($get('id'))),

                            \Filament\Forms\Components\Actions::make([
                                \Filament\Forms\Components\Actions\Action::make('copyFromLast')
                                    ->label('Kechagidan nusxa olish')
                                    ->disabled(fn($record, \Filament\Forms\Get $get) => filled($get('id')) || $record?->isFinalized())
                                    ->action(function ($livewire, $get, $set) {
                                        $record = method_exists($livewire, 'getRecord') ? $livewire->getRecord() : null;
                                        if (!$record) {
                                            \Filament\Notifications\Notification::make()->title('Avval yozuvni saqlang.')->warning()->send();
                                            return;
                                        }
                                        $last = $record->findings()->orderByDesc('detected_at')->orderByDesc('id')->first();
                                        if ($last) {
                                            $set('description', $last->description);
                                            \Filament\Notifications\Notification::make()->title('Kechagi matn nusxa qilindi.')->success()->send();
                                        } else {
                                            \Filament\Notifications\Notification::make()->title('Oldingi yozuv topilmadi.')->warning()->send();
                                        }
                                    }),
                            ])->alignLeft(),
                        ])
                        ->deleteAction(function (\Filament\Forms\Components\Actions\Action $action) {
                            $action->visible(fn(Forms\Get $get) => blank($get('id'))); // faqat YANGI (hali id yo‘q) bo‘lsa ko‘rsin
                        })
                        ->orderable(false)
                        ->cloneable(false)
                        ->defaultItems(0)
                        ->minItems(0)
                        ->addActionLabel('Dastlabki kamchilik qo‘shish')
                        ->hidden(fn($record) => $record?->isFinalized())
                        ->saveRelationshipsUsing(function ($component, $state, \App\Models\NonConformity $record) {
                            foreach ($state ?? [] as $item) {
                                // Agar id mavjud bo‘lsa — bu eski yozuv, uni SKIP qilamiz (update yo‘q)
                                if (!empty($item['id'])) {
                                    continue;
                                }

                                $record->findings()->create([
                                    'detected_at' => $item['detected_at'] ?? now()->toDateString(),
                                    'day_no' => $item['day_no'] ?? null,
                                    'description' => $item['description'] ?? '',
                                    'created_by' => auth()->id(),
                                ]);
                            }
                        })
                ])
                ->collapsed(false),

            Forms\Components\Section::make('Yakuniy xulosa')
                ->schema([
                    Forms\Components\Textarea::make('final_description')
                        ->label('Yakuniy kamchiliklar (xulosa)')
                        ->helperText('Bu bo‘lim bitta marta to‘ldiriladi va yakuniy hisoblanadi.')
                        ->disabled(fn($record) => $record?->isFinalized())
                        ->columnSpanFull(),

                    Actions::make([
                        Action::make('finalize')
                            ->label('Yakunlash')
                            ->color('success')
                            ->icon('heroicon-o-check-circle')
                            ->requiresConfirmation()
                            ->visible(fn($record) => $record && !$record->isFinalized())
                            ->action(function (NonConformity $record) {
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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['criteria_ids'] = $this->record
            ->criteria()
            ->distinct()
            ->pluck('criterias.id')
            ->all();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->isFinalized()) {
            return [];
        }
        $existingActs = collect($this->record->normative_act_id ?? [])->map(fn($v) => (int)$v);
        $incomingActs = collect($data['normative_act_id'] ?? [])->map(fn($v) => (int)$v);
        $mergedActs = $existingActs->merge($incomingActs)->unique()->values()->all();
        $data['normative_act_id'] = $mergedActs;


        if (array_key_exists('criteria_ids', $data)) {
            $relation = $this->record->criteria();
            $table = $relation->getRelated()->getTable(); // masalan 'criterias' yoki 'criteria'

            $current = $relation->distinct()->pluck("$table.id")->all();
            $incoming = collect($data['criteria_ids'] ?? [])->map(fn($v) => (int)$v)->all();
            $toAttach = array_diff($incoming, $current);
            if (!empty($toAttach)) {
                $this->record->criteria()->attach($toAttach);
            }
            unset($data['criteria_ids']);
        }

        unset($data['product_id'], $data['metrology_instrument_id'], $data['certificate_id'], $data['service_id']);

        return $data;
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        $action = parent::getSaveFormAction();

        if ($this->record?->isFinalized()) {
            $action->visible(false);

        }

        return $action;
    }

}
