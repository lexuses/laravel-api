<?php

namespace App\Console\Commands\Db;

use DB;
use Illuminate\Console\Command;

class DbTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all tables';

	/**
	 * Create a new command instance.
	 *
	 * @return \App\Console\Commands\DbTables
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
        $tables = $this->get_all_tables();

		$this->info('Total '.count($tables).' tables in database.');

		$sorted = [];
		foreach($tables as $table)
		{
			$first_letter = substr($table, 0, 1);
			$sorted[$first_letter][] = $table;
		}

		foreach($sorted as $letter=>$tables)
		{
			$alfa_delimetr = str_repeat('-',10);
			$this->info($alfa_delimetr.strtoupper($letter).$alfa_delimetr);
			$this->line(implode(', ',$tables));
		}
    }

	/**
	 * @return array
	 */
	private function get_all_tables()
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
