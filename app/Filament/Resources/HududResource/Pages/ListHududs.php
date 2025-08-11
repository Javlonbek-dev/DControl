<?php

namespace App\Filament\Resources\HududResource\Pages;

use App\Filament\Resources\HududResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHududs extends ListRecords
{
    protected static string $resource = HududResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
