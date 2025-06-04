<?php

namespace App\Filament\Resources\MedicalNoteResource\Pages;

use App\Filament\Resources\MedicalNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedicalNote extends EditRecord
{
    protected static string $resource = MedicalNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
