<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicalNoteResource\Pages;
use App\Models\MedicalNote;
use App\Models\Appointment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class MedicalNoteResource extends Resource
{
    protected static ?string $model = MedicalNote::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //muestra el id de la cita y evita que una cita tenga multiples notas medicas
                Forms\Components\Select::make('appointment_id')
                    ->relationship('appointment', 'id') 
                    ->label('Cita Asociada')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->searchable()
                    ->preload(),
                Forms\Components\Textarea::make('diagnostico')
                    ->required()
                    ->rows(5),
                Forms\Components\Textarea::make('tratamiento_sugerido')
                    ->required()
                    ->rows(5),
                Forms\Components\Textarea::make('observaciones')
                    ->rows(3)
                    ->nullable(),
                //para la subida de archivos , con la restricción de tamaño y que se puede abrir en otra ventana
                Forms\Components\FileUpload::make('archivo_url')
                    ->label('Archivo Adjunto')
                    ->disk('public') 
                    ->directory('medical_notes_files')
                    ->visibility('public') 
                    ->nullable() 
                    ->maxSize(2048) 
                    ->openable() 
                   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment.id')
                    ->label('ID Cita')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment.pet.nombre_mascota')
                     ->label('Mascota de la Cita')
                     ->searchable()
                     ->sortable(),
                Tables\Columns\TextColumn::make('diagnostico')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tratamiento_sugerido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('archivo_url')
                    ->label('Archivo Adjunto')
                    ->url(fn (?string $state): ?string => $state ? Storage::disk('public')->url($state) : null, shouldOpenInNewTab: true)
                    ->formatStateUsing(fn (?string $state): string => $state ? basename($state) : '-'), // Muestra el nombre del archivo, o un guion si no hay
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMedicalNotes::route('/'),
            'create' => Pages\CreateMedicalNote::route('/create'),
            'edit' => Pages\EditMedicalNote::route('/{record}/edit'),

        ];
    }
}
