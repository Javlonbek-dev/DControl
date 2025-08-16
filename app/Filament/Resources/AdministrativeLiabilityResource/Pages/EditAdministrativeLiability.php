<?php

namespace App\Filament\Resources\AdministrativeLiabilityResource\Pages;

use App\Filament\Resources\AdministrativeLiabilityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdministrativeLiability extends EditRecord
{
    protected static string $resource = AdministrativeLiabilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
