<?php

namespace App\Filament\Resources\EconomicSanctionResource\Pages;

use App\Filament\Resources\EconomicSanctionResource;
use App\Models\NonConformity;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditEconomicSanction extends EditRecord
{
    protected static string $resource = EconomicSanctionResource::class;

    protected function getHeaderActions(): array
    {
        if (Filament::auth()->user()->hasRole('moderator')) {


            return [
                Actions\DeleteAction::make(),
            ];
        }
        else return [];
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
            NonConformity::where('economic_sanction_id', $record->id)
                ->when($ids !== [], fn($q) => $q->whereNotIn('id', $ids))
                ->update(['economic_sanction_id' => null]);

            if ($ids !== []) {
                NonConformity::whereIn('id', $ids)
                    ->update(['economic_sanction_id' => $record->id]);
            }
        });

        \Log::info('SYNC: now linked NC IDs', NonConformity::where('economic_sanction_id', $record->id)->pluck('id')->all());
    }
}
