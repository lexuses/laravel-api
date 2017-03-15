<?php

namespace App\Console\Commands;

use App\Services\Core\Generator\Generator;
use Illuminate\Console\Command;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {name : Module name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate module folders';
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
        $name = $this->argument('name');

        $module = $this->generator->makeModule($name);

        $this->info('Module "'. $module . '" generate successfully');

        return true;
    }
}
