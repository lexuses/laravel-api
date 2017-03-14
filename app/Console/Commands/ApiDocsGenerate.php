<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ApiDocsGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api:docs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate api documentation. Powered by Swagger. 
    Check: http://zircote.com/swagger-php/ Official: http://swagger.io';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        exec('php ./vendor/zircote/swagger-php/bin/swagger ./app ./routes -o ./public/api-docs/swagger.json');

        $this->info('Api docs updated');
    }
}
