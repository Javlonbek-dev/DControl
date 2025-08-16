<?php

namespace App\Filament\Resources\AdministrativeCodeResource\Pages;

use App\Filament\Resources\AdministrativeCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdministrativeCode extends EditRecord
{
    protected static string $resource = AdministrativeCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
