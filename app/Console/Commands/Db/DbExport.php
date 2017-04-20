<?php

namespace App\Console\Commands\Db;

use DB;
use Illuminate\Console\Command;

class DbExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:export {--table=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mysql dump database. Use "," for delimiter';

	private $dir;

	/**
	 * Create a new command instance.
	 *
	 * @return \App\Console\Commands\DbExport
	 */
    public function __construct()
    {
        parent::__construct();
		$this->dir = base_path() . '/database/seeds/sql/dumps/' . date('Y-m-d');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$path = env('MYSQL_PATH');
		if ( ! $path AND $path != false)
		{
			$this->error('If you want use export to file, you must set the MYSQL_PATH variable in your ENV file. Path to "mysql\bin\".');
			return false;
		}
		if ($path == 'false')
			$path = '';

		$password = '';
		if (env('DB_PASSWORD'))
			$password = ' -p' . env('DB_PASSWORD');

		$main_command = $path.'mysqldump --complete-insert -u ' . env('DB_USERNAME') . $password . ' -t ' . env('DB_DATABASE');
		$zip = ' | gzip > ';

		if(!is_dir($this->dir)) mkdir($this->dir);

		$table_options = $this->option('table');

		if( ! $table_options )
		{
            $tables_db = $this->getAllTables();
            $key = array_search('migrations', $tables_db);
            unset($tables_db[$key]);

            $table_ssh = implode(' ', $tables_db);
            $command = $main_command . ' ' . $table_ssh . $zip . $this->dir . '/all-' . date('H-i-s') . '.sql.gz';
		}
        else
		{
			$tables = explode(',',$table_options);
			$tables_db = $this->getAllTables();

			foreach($tables as $table)
			{
				if(!in_array($table, $tables_db))
				{
					$this->info('Table "'.$table.'" not found');
					return false;
				}
			}

			$table_ssh = implode(' ', $tables);
			$command = $main_command . ' ' . $table_ssh . $zip . $this->dir . '/' . implode('-', $tables) . '-' . date('H-i-s') . '.sql.gz';
		}
		exec($command);

		$this->info('Dump complete!');
    }

	/**
	 * @return array
	 */
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
