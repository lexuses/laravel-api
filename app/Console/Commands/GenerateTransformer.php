<?php

namespace App\Console\Commands;

use App\Services\Core\Generator\Generator;
use App\Services\Core\Generator\GeneratorDataFromDB;
use Illuminate\Console\Command;

class GenerateTransformer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'g:tr 
                            {module : Module name} 
                            {name : Transformer name} 
                            {--table= : Table of model}
                            {--model= : Model name}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate transformer in module';

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
            'properties' => '',
            'properties_docs' => '',
        ];

        $table = $this->option('table');
        if($table)
        {
            if( ! GeneratorDataFromDB::tableExist($table))
                dd('Table does not exists!');

            $data = $this->generator->transformerData($table);
        }

        $modelNamespace = $this->option('model');
        if($modelNamespace)
        {
            if( ! class_exists($modelNamespace))
                dd('Model class does not exists!');

            $model = new $modelNamespace;
            $data = $this->generator->transformerData($model->getTable());
            $data['model_name'] = class_basename($model);
        }

        $this->generator->moduleExist($module);
        $name = $this->generator->generateByType('Transformers', $name, $data);

        $this->info('"'. $name . '" generate successfully');

        return true;
    }
}
