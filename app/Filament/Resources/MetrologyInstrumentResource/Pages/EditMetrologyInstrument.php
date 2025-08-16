<?php

namespace App\Filament\Resources\MetrologyInstrumentResource\Pages;

use App\Filament\Resources\MetrologyInstrumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMetrologyInstrument extends EditRecord
{
    protected static string $resource = MetrologyInstrumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
