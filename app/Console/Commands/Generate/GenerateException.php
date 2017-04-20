<?php

namespace App\Console\Commands\Generate;

use App\Services\Core\Generator\Generator;
use Illuminate\Console\Command;

class GenerateException extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'g:e {module : Module name} {name : Exception name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate exception in module';

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

        $this->generator->moduleExist($module);
        $name = $this->generator->generateByType('Exceptions', $name);

        $this->info('"'. $name . '" generate successfully');

        return true;
    }
}
