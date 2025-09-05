<?php

namespace App\Filament\Resources\EconomicSanctionResource\Pages;

use App\Filament\Resources\EconomicSanctionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEconomicSanctions extends ListRecords
{
    protected static string $resource = EconomicSanctionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Moliyaviy jarima qo'shish"),
        ];
    }
}
