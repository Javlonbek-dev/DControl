<?php

namespace App\Filament\Resources\HududResource\Pages;

use App\Filament\Resources\HududResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHudud extends EditRecord
{
    protected static string $resource = HududResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
