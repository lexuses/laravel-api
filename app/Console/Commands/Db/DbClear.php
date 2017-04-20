<?php

namespace App\Console\Commands\Db;

use DB;
use Illuminate\Console\Command;

class DbClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all tables';

    /**
     * Create a new command instance.
     *
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
            DB::statement('DROP TABLE IF EXISTS '.$table);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('All tables was deleted');
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
