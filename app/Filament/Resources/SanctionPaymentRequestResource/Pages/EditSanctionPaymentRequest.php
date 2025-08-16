<?php

namespace App\Filament\Resources\SanctionPaymentRequestResource\Pages;

use App\Filament\Resources\SanctionPaymentRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSanctionPaymentRequest extends EditRecord
{
    protected static string $resource = SanctionPaymentRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
