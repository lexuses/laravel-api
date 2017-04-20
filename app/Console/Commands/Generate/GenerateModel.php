<?php

namespace App\Console\Commands\Generate;

use App\Services\Core\Generator\Generator;
use App\Services\Core\Generator\GeneratorDataFromDB;
use Illuminate\Console\Command;

class GenerateModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'g:m {module : Module name} {name : Model name} {--table= : Table of model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate model in module';

    private $generator;

    /**
     * Create a new command instance.
     *
     * @param Generator $generator
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = $this->argument('module');
        $name = $this->argument('name');

        $data = [
            'table' => '',
            'fillable' => '',
            'dates' => '',
            'properties' => '',
        ];

        $table = $this->option('table');
        if($table)
        {
            if( ! GeneratorDataFromDB::tableExist($table))
                dd('Table does not exists!');

            $data = $this->generator->modelData($table);
        }

        $this->generator->moduleExist($module);
        $name = $this->generator->generateByType('Models', $name, $data);

        $this->info('"'. $name . '" generate successfully');

        $modelNamespace = 'App\Modules\\' . $module . '\Models\\' . $name;

        if ( ! class_exists($modelNamespace))
            dd('Model does not exists!');

        $this->call('g:tr', [
            'module' => $module,
            'name' => $name,
            '--model' => $modelNamespace
        ]);

        return true;
    }
}
