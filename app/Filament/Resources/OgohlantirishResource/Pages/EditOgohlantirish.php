<?php

namespace App\Filament\Resources\OgohlantirishResource\Pages;

use App\Filament\Resources\OgohlantirishResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOgohlantirish extends EditRecord
{
    protected static string $resource = OgohlantirishResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
