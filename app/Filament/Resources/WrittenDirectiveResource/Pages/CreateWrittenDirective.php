<?php

namespace App\Filament\Resources\WrittenDirectiveResource\Pages;

use App\Filament\Resources\WrittenDirectiveResource;
use App\Models\NonConformity;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateWrittenDirective extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = WrittenDirectiveResource::class;

    public array $selectedNcIds = [];

    // CREATEdan oldin — form state’dan ID’larni olib stash qilamiz
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $raw = $this->form->getRawState(); // dehydrat bo‘lmagan maydonlarni ham oladi
        $this->selectedNcIds = Arr::get($raw, 'selected_nc_ids', []);
        \Log::info('CREATE: captured selectedNcIds', $this->selectedNcIds);

        return $data; // model uchun qolgan maydonlar
    }

    // CREATEdan keyin — endi record ID bor, FK’larni yangilaymiz
    protected function afterCreate(): void
    {
        $ids = collect($this->selectedNcIds)->filter()->map(fn($v) => (int)$v)->unique()->values()->all();
        \Log::info('CREATE: syncing with IDs', $ids);
        $this->syncNonConformities($ids);
    }

    private function syncNonConformities(array $ids): void
    {
        $record = $this->record;

        DB::transaction(function () use ($record, $ids) {
            // Avval bu liability’ga bog‘langan, ammo endi tanlanmaganlarni bo‘shatamiz
            NonConformity::where('written_directive_id', $record->id)
                ->when($ids !== [], fn ($q) => $q->whereNotIn('id', $ids))
                ->update(['written_directive_id' => null]);

            // Tanlanganlarni joriy liability’ga bog‘laymiz
            if ($ids !== []) {
                NonConformity::whereIn('id', $ids)
                    ->update(['written_directive_id' => $record->id]);
            }
        });

        \Log::info('CREATE: now linked NC IDs',
            NonConformity::where('written_directive_id', $record->id)->pluck('id')->all()
        );
    }
}
