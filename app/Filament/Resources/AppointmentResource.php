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
//resource para las citas, pa ver crear y editar citas en el panel
class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';//icono
    public static function getEloquentQuery(): Builder//se ejecuta antes de que la tabla muestre y nos permite modificar la consulta
{
    $query = parent::getEloquentQuery();
    $user = auth()->user();//obtengo el usuario autenticado

    if ($user->role->nombre_rol === 'Veterinario') {
        return $query->where('vet_id', $user->id);//si el rol es vet ,solo se traen las citas de ese veterinario
    }
    // si el rol es dueno de mascota , se usa whereHas pa filtrar las citas que pertenecem auna mascota cuyo dueño seal el actual
    if ($user->role->nombre_rol === 'Dueño de Mascota') {

        return $query->whereHas('pet', fn ($q) => $q->where('owner_id', $user->id));
    }

    // admin ve todo
    return $query;
}
    //define el formulario para crear y editar citas
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //seleccion de mascota
                Forms\Components\Select::make('pet_id')
                    ->relationship('pet', 'nombre_mascota')
                    ->label('Mascota')
                    ->preload()
                    ->searchable()
                    ->required(),
                //seleccion de veterinario
                Forms\Components\Select::make('vet_id')
                    ->relationship('veterinarian', 'name')
                    ->label('Veterinario')
                    ->preload()
                    ->searchable()
                    ->required(),
                //fecha y hora
                Forms\Components\DateTimePicker::make('appointment_datetime')
                    ->label('Fecha y hora de la cita')
                    ->required(),
                Forms\Components\Textarea::make('motivo_consulta')
                    ->required()
                    ->columnSpanFull(),
                //selector con opciones
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
    //define la tabla que lista las citas
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
    {//rutas prar este resource
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
