<?php

namespace App\Filament\Resources\BxmResource\Pages;

use App\Filament\Resources\BxmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBxms extends ListRecords
{
    protected static string $resource = BxmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
