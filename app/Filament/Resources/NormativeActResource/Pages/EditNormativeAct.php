<?php

namespace App\Filament\Resources\NormativeActResource\Pages;

use App\Filament\Resources\NormativeActResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNormativeAct extends EditRecord
{
    protected static string $resource = NormativeActResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
