<?php

namespace App\Filament\Resources\NonConformityResource\Pages;

use App\Filament\Resources\NonConformityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNonConformity extends EditRecord
{
    protected static string $resource = NonConformityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
