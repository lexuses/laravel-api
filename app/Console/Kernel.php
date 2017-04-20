<?php

namespace App\Console;

use App\Console\Commands\ApiDocsGenerate;
use App\Console\Commands\CronTest;
use App\Console\Commands\Db\DbClear;
use App\Console\Commands\Db\DbDumps;
use App\Console\Commands\Db\DbExport;
use App\Console\Commands\Db\DbImport;
use App\Console\Commands\Db\DbTables;
use App\Console\Commands\Db\DbTruncate;
use App\Console\Commands\Generate\GenerateController;
use App\Console\Commands\Generate\GenerateException;
use App\Console\Commands\Generate\GenerateModel;
use App\Console\Commands\Generate\GenerateRequest;
use App\Console\Commands\Generate\GenerateTask;
use App\Console\Commands\Generate\GenerateTransformer;
use App\Console\Commands\Generate\MakeModule;
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
        CronTest::class,

        MakeModule::class,
        GenerateController::class,
        GenerateException::class,
        GenerateRequest::class,
        GenerateTask::class,
        GenerateModel::class,
        GenerateTransformer::class,

        DbClear::class,
        DbDumps::class,
        DbExport::class,
        DbImport::class,
        DbTables::class,
        DbTruncate::class,
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
