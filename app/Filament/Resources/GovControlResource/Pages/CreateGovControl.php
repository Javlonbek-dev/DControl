<?php

namespace App\Filament\Resources\GovControlResource\Pages;

use App\Filament\Resources\GovControlResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGovControl extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = GovControlResource::class;
}
