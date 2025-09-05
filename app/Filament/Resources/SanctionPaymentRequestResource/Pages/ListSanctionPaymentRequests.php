<?php

namespace App\Filament\Resources\SanctionPaymentRequestResource\Pages;

use App\Filament\Resources\SanctionPaymentRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSanctionPaymentRequests extends ListRecords
{
    protected static string $resource = SanctionPaymentRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Talabnoma qo'shish"),
        ];
    }
}
