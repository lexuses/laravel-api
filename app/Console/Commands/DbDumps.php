<?php

namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;

class DbDumps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dumps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show all dumps.';

	/**
	 * Create a new command instance.
	 *
	 * @return \App\Console\Commands\DbDumps
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
		$dir = 'database/seeds/sql/dumps';

		$sql_dumps = File::allFiles($dir);
		$dumps = [];
		foreach($sql_dumps as $k=>$dump)
		{
			$dump = str_replace($dir, '', $dump);
			if($dump != '.gitkeep')
				$dumps[] = $dump;
		}

		if(empty($dumps))
		{
			$this->info('Empty.');
			return true;
		}

		foreach($dumps as $k=>$dump)
		{
			$this->line($k.') '.$dump);
		}
    }
}
