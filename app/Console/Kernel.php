<?php

namespace App\Console;

use App\Console\Commands\ApiDocsGenerate;
use App\Console\Commands\GenerateController;
use App\Console\Commands\GenerateException;
use App\Console\Commands\GenerateModel;
use App\Console\Commands\GenerateRequest;
use App\Console\Commands\GenerateTask;
use App\Console\Commands\GenerateTransformer;
use App\Console\Commands\MakeModule;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ApiDocsGenerate::class,
        MakeModule::class,
        GenerateController::class,
        GenerateException::class,
        GenerateRequest::class,
        GenerateTask::class,
        GenerateModel::class,
        GenerateTransformer::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
