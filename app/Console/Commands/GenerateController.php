<?php

namespace App\Console\Commands;

use App\Services\Core\Generator\Generator;
use Illuminate\Console\Command;

class GenerateController extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'g:c {module : Module name} {name : Controller name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate controller in module';

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
        $fullName = $this->generator->generateByType('Controllers', $name);

        $this->info('"'. $fullName . '" generate successfully');

        $this->call('g:r', [
            'module' => $module,
            'name' => $name,
        ]);

        $this->call('g:t', [
            'module' => $module,
            'name' => $name,
        ]);

        return true;
    }
}
