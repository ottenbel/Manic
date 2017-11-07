<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateFolders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'folders:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the storage folders if they do not exists.';

    /**
     * Create a new command instance.
     *
     * @return void
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
        $folders = array('app/public/images/full', 'app/public/images/thumbs', 'app/public/images/tmp', 'app/public/images/export/chapters', 'app/public/images/export/volumes', 'app/public/images/export/collections');
        $this->output->progressStart(count($folders));
        foreach($folders as $folder)
        {
            if (!file_exists($folder)) {
                mkdir($folder, '755', true);
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        $this->info("Folders created.");
    }
}
