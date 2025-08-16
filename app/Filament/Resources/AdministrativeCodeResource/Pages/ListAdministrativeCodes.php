<?php

namespace App\Filament\Resources\AdministrativeCodeResource\Pages;

use App\Filament\Resources\AdministrativeCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdministrativeCodes extends ListRecords
{
    protected static string $resource = AdministrativeCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
