<?php

namespace App\Console\Commands\Db;

use DB;
use Illuminate\Console\Command;

class DbTruncate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all tables';

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
        $tables = $this->getAllTables();

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($tables as $table)
        {
            if($table == 'migrations')
                continue;

            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('All tables was truncated');
    }

    private function getAllTables()
    {
        $tables_from_db = DB::select('SHOW TABLES');
        $tables = [];
        foreach ($tables_from_db as $table)
        {
            $key = key(get_object_vars($table));
            $tables[] = $table->{$key};
        }

        return $tables;
    }
}
