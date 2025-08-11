<?php

namespace App\Filament\Resources\ProfilaktikaResource\Pages;

use App\Filament\Resources\ProfilaktikaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfilaktika extends EditRecord
{
    protected static string $resource = ProfilaktikaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
