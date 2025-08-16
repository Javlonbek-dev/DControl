<?php

namespace App\Filament\Resources\BxmResource\Pages;

use App\Filament\Resources\BxmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBxm extends EditRecord
{
    protected static string $resource = BxmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
