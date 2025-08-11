<?php

namespace App\Filament\Resources\OgohlantirishResource\Pages;

use App\Filament\Resources\OgohlantirishResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOgohlantirishes extends ListRecords
{
    protected static string $resource = OgohlantirishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
