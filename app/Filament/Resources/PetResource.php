<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetResource\Pages;
use App\Filament\Resources\PetResource\RelationManagers;
use App\Models\Pet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
//crea el crud para mascota
class PetResource extends Resource
{
    protected static ?string $model = Pet::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    //se ejecuta antes de que la tabla se muestre , permite filtrar que registros se ven dependiendo del rol
    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();
    $user = auth()->user();//obtengo el usuario autenticado

    if ($user->role->nombre_rol === 'Dueño de Mascota') {
        return $query->where('owner_id', $user->id);
    }

    return $query;
}
    //form para crear y editar
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('owner_id')
                    ->relationship('owner', 'name') 
                    ->label('Dueño') 
                    ->searchable()   
                    ->preload()      
                    ->required(),
                Forms\Components\TextInput::make('nombre_mascota')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('especie')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('raza')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('fecha_nacimiento'),
                Forms\Components\TextInput::make('foto_url')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Dueño')
                    ->searchable()
                    
                    ->sortable(),
                Tables\Columns\TextColumn::make('nombre_mascota')
                    ->searchable(),
                Tables\Columns\TextColumn::make('especie')
                    ->searchable(),
                Tables\Columns\TextColumn::make('raza')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_nacimiento')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('foto_url')
                    ->searchable(),
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
    //paginas y rutas para este resource
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPets::route('/'),
            'create' => Pages\CreatePet::route('/create'),
            'edit' => Pages\EditPet::route('/{record}/edit'),
        ];
    }
}
