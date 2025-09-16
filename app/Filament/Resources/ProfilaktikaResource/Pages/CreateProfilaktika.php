<?php

namespace App\Filament\Resources\ProfilaktikaResource\Pages;

use App\Filament\Resources\ProfilaktikaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProfilaktika extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = ProfilaktikaResource::class;
}
