<?php

namespace App\Filament\Resources\NormativeActResource\Pages;

use App\Filament\Resources\NormativeActResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNormativeAct extends CreateRecord
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected static string $resource = NormativeActResource::class;
}
