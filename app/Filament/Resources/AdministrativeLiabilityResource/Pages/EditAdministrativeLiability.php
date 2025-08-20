<?php

namespace App\Filament\Resources\AdministrativeLiabilityResource\Pages;

use App\Filament\Resources\AdministrativeLiabilityResource;
use App\Models\NonConformity;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EditAdministrativeLiability extends EditRecord
{
    protected static string $resource = AdministrativeLiabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function beforeSave(): void
    {
        // DIQQAT: getRawState() => dehydrat qilinmagan maydonlarni ham qaytaradi
        $raw = $this->form->getRawState();
        \Log::info('RAW FORM STATE:', $raw);

        $ids = $raw['selected_nc_ids'] ?? [];
        \Log::info('SYNC: incoming IDs', $ids);

        $this->syncNonConformities($ids);
    }

    private function syncNonConformities(array $ids): void
    {
        $record = $this->record;

        $ids = collect($ids)->filter()->map(fn($v) => (int)$v)->unique()->values()->all();

        DB::transaction(function () use ($record, $ids) {
            NonConformity::where('administrative_liability_id', $record->id)
                ->when($ids !== [], fn($q) => $q->whereNotIn('id', $ids))
                ->update(['administrative_liability_id' => null]);

            if ($ids !== []) {
                NonConformity::whereIn('id', $ids)
                    ->update(['administrative_liability_id' => $record->id]);
            }
        });

        \Log::info('SYNC: now linked NC IDs', NonConformity::where('administrative_liability_id', $record->id)->pluck('id')->all());
    }
}
