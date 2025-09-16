<?php

namespace App\Filament\Resources\NonConformityResource\Pages;

use App\Filament\Resources\NonConformityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNonConformity extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = NonConformityResource::class;
}
