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
use Filament\Tables\Table;

class AppointmentProductResource extends Resource
{
    protected static ?string $model = AppointmentProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart'; // Ícono más relacionado a ventas/productos

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('appointment_id')
                    ->relationship('appointment', 'id')
                    ->label('Cita (ID)')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'nombre_producto')
                    ->label('Producto')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('cantidad_vendida')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->label('Cantidad Vendida'),
                Forms\Components\TextInput::make('precio_al_momento_venta')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('Precio de Venta (Unidad)'),
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
                Tables\Columns\TextColumn::make('product.nombre_producto')
                    ->label('Producto Vendido')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cantidad_vendida')
                    ->label('Cantidad')
                    ->sortable(),
                Tables\Columns\TextColumn::make('precio_al_momento_venta')
                    ->money('MXN')
                    ->label('Precio Venta (Unidad)')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Venta')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListAppointmentProducts::route('/'),
            'create' => Pages\CreateAppointmentProduct::route('/create'), 
            'edit' => Pages\EditAppointmentProduct::route('/{record}/edit'),
        ];
    }
}