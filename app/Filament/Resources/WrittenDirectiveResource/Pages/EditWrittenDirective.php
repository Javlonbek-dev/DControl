<?php

namespace App\Filament\Resources\WrittenDirectiveResource\Pages;

use App\Filament\Resources\WrittenDirectiveResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWrittenDirective extends EditRecord
{
    protected static string $resource = WrittenDirectiveResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
