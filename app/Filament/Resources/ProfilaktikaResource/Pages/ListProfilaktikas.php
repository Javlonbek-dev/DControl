<?php

namespace App\Filament\Resources\ProfilaktikaResource\Pages;

use App\Filament\Resources\ProfilaktikaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProfilaktikas extends ListRecords
{
    protected static string $resource = ProfilaktikaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
