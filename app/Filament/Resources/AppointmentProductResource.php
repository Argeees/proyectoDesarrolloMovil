<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentProductResource\Pages;
use App\Models\AppointmentProduct;
use App\Models\Appointment;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;//constructor de tablas

class AppointmentProductResource extends Resource
{
    protected static ?string $model = AppointmentProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart'; // iconos de venta
    //construccion de formulariom , el cono se vera crear y editar un registro
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //campo para seleccionar la cita
                Forms\Components\Select::make('appointment_id')
                    ->relationship('appointment', 'id')
                    ->label('Cita (ID)')
                    ->required()
                    ->searchable()
                    ->preload(),
                //campo para seleccionar el producto
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'nombre_producto')
                    ->label('Producto')
                    ->required()
                    ->searchable()
                    ->preload(),
                //campo para lacantidad vendida
                Forms\Components\TextInput::make('cantidad_vendida')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->label('Cantidad Vendida'),
                //campo para el precio
                Forms\Components\TextInput::make('precio_al_momento_venta')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('Precio de Venta (Unidad)'),
            ]);
    }
    //definicion de la tabla , como se vera la tabla que lista los registros
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //columna para el id de la cita
                Tables\Columns\TextColumn::make('appointment.id')
                    ->label('ID Cita')
                    ->searchable()
                    ->sortable(),
                //columna para el nombre del producto
                Tables\Columns\TextColumn::make('product.nombre_producto')
                    ->label('Producto Vendido')
                    ->searchable()
                    ->sortable(),
                //columna para la cantidad
                Tables\Columns\TextColumn::make('cantidad_vendida')
                    ->label('Cantidad')
                    ->sortable(),
                //columna para el precio
                Tables\Columns\TextColumn::make('precio_al_momento_venta')
                    ->money('MXN')
                    ->label('Precio Venta (Unidad)')
                    ->sortable(),
                //columna para la fecha
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Venta')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([//acciones que aparecen para cada fila
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([//de realizan en varias filas a la vez
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
    //rutas prar este resource
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointmentProducts::route('/'),
            'create' => Pages\CreateAppointmentProduct::route('/create'), 
            'edit' => Pages\EditAppointmentProduct::route('/{record}/edit'),
        ];
    }
}