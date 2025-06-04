<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pet_id')
                    ->relationship('pet', 'nombre_mascota')
                    ->label('Mascota')
                    ->preload()
                    ->searchable()
                    ->required(),
                    
                Forms\Components\Select::make('vet_id')
                    ->relationship('veterinarian', 'name')
                    ->label('Veterinario')
                    ->preload()
                    ->searchable()
                    ->required(),
                    
                Forms\Components\DateTimePicker::make('appointment_datetime')
                    ->label('Fecha y hora de la cita')
                    ->required(),
                Forms\Components\Textarea::make('motivo_consulta')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('estado')
                    ->options([
                        'Programada' => 'Programada',
                        'Completada' => 'Completada',
                        'Cancelada' => 'Cancelada',
                    ])
                    ->label('Estado')
                    ->required(),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id') 
                    ->label('ID Cita')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pet.nombre_mascota')
                    ->label('Mascota')
                    ->searchable()
                    
                    ->sortable(),
                Tables\Columns\TextColumn::make('veterinarian.name')
                    ->label('Veterinario')
                    ->searchable()
                    
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_datetime')
                    ->label('Fecha y hora de la cita')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
