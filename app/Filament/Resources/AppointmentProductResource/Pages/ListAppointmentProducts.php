<?php

namespace App\Filament\Resources\AppointmentProductResource\Pages;

use App\Filament\Resources\AppointmentProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAppointmentProducts extends ListRecords
{
    protected static string $resource = AppointmentProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
