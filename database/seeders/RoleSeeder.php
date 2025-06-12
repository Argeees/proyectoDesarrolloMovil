<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
                // 1. Desactivamos temporalmente la revisión de llaves foráneas
        //    (Le decimos a la base de datos: "hazte de la vista gorda un momento")
        Schema::disableForeignKeyConstraints();

        // 2. Vaciamos la tabla (ahora sí nos va a dejar porque no está revisando)
        Role::truncate();

        // 3. Volvemos a activar la revisión de llaves foráneas (¡SÚPER IMPORTANTE!)
        //    (Le decimos: "Listo, ya acabé, ahora sí, vuelve a ponerte estricto")
        Schema::enableForeignKeyConstraints();
 

        Role::create(['nombre_rol' => 'Admin']);
        Role::create(['nombre_rol' => 'Veterinario']);
        Role::create(['nombre_rol' => 'Dueño de Mascota']);
    }
}
