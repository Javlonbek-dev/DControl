<?php

namespace App\Filament\Resources\TalabnomaResource\Pages;

use App\Filament\Resources\TalabnomaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTalabnoma extends EditRecord
{
    protected static string $resource = TalabnomaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! auth()->user()->hasRole('moderator')) {
            unset($data['jarima_sum'],$data['jarima_foizi'],$data['tekshiruv_holati'],
                $data['tulangan_sum'],$data['tulangan_foizi'],$data['end_date'],
                $data['huquqbuzarlik_mazmuni'],$data['qounun_moddasi']);
        }
        return $data;
    }
}
