<?php

namespace App\Filament\Resources\MedicalNoteResource\Pages;

use App\Filament\Resources\MedicalNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicalNote extends CreateRecord
{
    protected static string $resource = MedicalNoteResource::class;
}
