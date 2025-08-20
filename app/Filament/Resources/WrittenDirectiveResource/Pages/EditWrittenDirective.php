<?php

namespace App\Filament\Resources\WrittenDirectiveResource\Pages;

use App\Filament\Resources\WrittenDirectiveResource;
use App\Models\NonConformity;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditWrittenDirective extends EditRecord
{
    protected static string $resource = WrittenDirectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    protected function beforeSave(): void
    {
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
            NonConformity::where('written_directive_id', $record->id)
                ->when($ids !== [], fn($q) => $q->whereNotIn('id', $ids))
                ->update(['written_directive_id' => null]);

            if ($ids !== []) {
                NonConformity::whereIn('id', $ids)
                    ->update(['written_directive_id' => $record->id]);
            }
        });

        \Log::info('SYNC: now linked NC IDs', NonConformity::where('written_directive_id', $record->id)->pluck('id')->all());
    }
}
