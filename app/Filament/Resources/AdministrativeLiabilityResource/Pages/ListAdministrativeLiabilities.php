<?php

namespace App\Filament\Resources\AdministrativeLiabilityResource\Pages;

use App\Filament\Resources\AdministrativeLiabilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdministrativeLiabilities extends ListRecords
{
    protected static string $resource = AdministrativeLiabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Ma'muriy javobgarlik yaratish"),
        ];
    }
}
