<?php

namespace App\Filament\Resources\WrittenDirectiveResource\Pages;

use App\Filament\Resources\WrittenDirectiveResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWrittenDirectives extends ListRecords
{
    protected static string $resource = WrittenDirectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Yozma ko'rsatma"),
        ];
    }
}
