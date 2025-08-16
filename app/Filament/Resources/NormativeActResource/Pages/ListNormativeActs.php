<?php

namespace App\Filament\Resources\NormativeActResource\Pages;

use App\Filament\Resources\NormativeActResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNormativeActs extends ListRecords
{
    protected static string $resource = NormativeActResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
