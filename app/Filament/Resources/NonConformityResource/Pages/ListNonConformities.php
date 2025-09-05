<?php

namespace App\Filament\Resources\NonConformityResource\Pages;

use App\Filament\Resources\NonConformityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNonConformities extends ListRecords
{
    protected static string $resource = NonConformityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Nomovufiqlik qo'shish"),
        ];
    }
}
