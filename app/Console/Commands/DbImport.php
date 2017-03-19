<?php

namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;

class DbImport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:import {--all : Show all dumps} {--last : Set last dump}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import selected dump to database';
    private $dir;

    /**
     * Create a new command instance.
     *
     * @return \App\Console\Commands\DbImport
     */
    public function __construct()
    {
        parent::__construct();
        $this->dir = 'database/seeds/sql/dumps/';
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
			$this->error('If you want use import from file, you must set the MYSQL_PATH variable in your ENV file. Path to "mysql\bin\".');
			return false;
		}
		if($path == 'false')
			$path = '';

        $directories = $this->askDirectories();
        if( ! $directories)
            return false;

        if($this->option('last'))
            $folder = array_keys($directories)[0]+1;
        else
            $folder = $this->anticipate('Select folder: ', array_keys($directories));

        $files = $this->askFiles($directories, $folder);

        if($this->option('last'))
        {
            $dump = array_keys($files);
            $dump = end($dump)+1;
        }
        else
            $dump = $this->anticipate('Select dump: ', array_keys($files));

        $pass = env('DB_PASSWORD') ? '-p'.env('DB_PASSWORD') : '';
        $command = 'gunzip < '.$files[$dump-1].' | ' . $path . 'mysql -u'.env('DB_USERNAME').' '.$pass.' -f '.env('DB_DATABASE').'';

		exec($command);

        $this->info('Done!');
    }

    private function askDirectories()
    {
        $directories = File::directories($this->dir);
        if(empty($directories))
        {
            $this->line('No directories.');
            return true;
        }

        rsort($directories);
        if( ! $this->option('all'))
            $directories = array_slice($directories, 0, 5);

        foreach($directories as $k=>$dir)
        {
            $dir = explode('/', $dir);

            if( ! $this->option('last'))
                $this->line( ($k+1).') '.end($dir));
        }

        return $directories;
    }

    private function askFiles($directories, $folder)
    {
        $files = File::files($directories[$folder-1]);
        if(empty($files))
        {
            $this->line('Directory is empty. Nothing to import.');
            return true;
        }

        foreach($files as $j=>$file)
        {
            $file = explode('/', $file);

            if( ! $this->option('last'))
                $this->line( ($j+1).') '.end($file));
        }

        return $files;
    }
}
