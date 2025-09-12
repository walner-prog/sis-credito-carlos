<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Datos básicos del sistema
        $this->call(RolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(CarteraSeeder::class);
        $this->call(ClienteSeeder::class);
        $this->call(ConfiguracionSeeder::class);
       // $this->call(CreditoSeeder::class);
      //  $this->call(CustomerSeeder::class);
       // $this->call(CreditSeeder::class);
      
        
        
        
       
      
       
        
        
       
      


      
    }
}
