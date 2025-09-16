<?php

namespace App\Filament\Resources\MetrologyInstrumentResource\Pages;

use App\Filament\Resources\MetrologyInstrumentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMetrologyInstrument extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = MetrologyInstrumentResource::class;
}
