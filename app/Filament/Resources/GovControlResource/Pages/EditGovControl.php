<?php

namespace App\Filament\Resources\GovControlResource\Pages;

use App\Filament\Resources\GovControlResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGovControl extends EditRecord
{
    protected static string $resource = GovControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
