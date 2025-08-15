<?php

namespace App\Filament\Resources\TalabnomaResource\Pages;

use App\Filament\Resources\TalabnomaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTalabnomas extends ListRecords
{
    protected static string $resource = TalabnomaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
