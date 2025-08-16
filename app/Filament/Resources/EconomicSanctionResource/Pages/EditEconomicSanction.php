<?php

namespace App\Filament\Resources\EconomicSanctionResource\Pages;

use App\Filament\Resources\EconomicSanctionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEconomicSanction extends EditRecord
{
    protected static string $resource = EconomicSanctionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
