<?php

namespace App\Filament\Resources\GovControlResource\Pages;

use App\Filament\Resources\GovControlResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGovControls extends ListRecords
{
    protected static string $resource = GovControlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
