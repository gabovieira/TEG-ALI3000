<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\EmpresaConsultorSeeder;

class AsignarEmpresasConsultores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ali3000:asignar-empresas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asigna empresas a los consultores para pruebas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Asignando empresas a consultores...');
        
        $seeder = new EmpresaConsultorSeeder();
        $seeder->setContainer($this->laravel);
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->info('¡Empresas asignadas correctamente!');
    }
}