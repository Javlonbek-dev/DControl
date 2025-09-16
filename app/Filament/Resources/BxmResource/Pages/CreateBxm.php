<?php

namespace App\Filament\Resources\BxmResource\Pages;

use App\Filament\Resources\BxmResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBxm extends CreateRecord
{
    protected static string $resource = BxmResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
