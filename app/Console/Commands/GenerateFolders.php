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
        $folders = array('storage/app/public/images/full', 'storage/app/public/images/thumbs', 'storage/app/public/images/tmp', 'storage/app/public/images/export/chapters', 'storage/app/public/images/export/volumes', 'storage/app/public/images/export/collections');
        $this->output->progressStart(count($folders));
        foreach($folders as $folder)
        {
            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }
            $this->output->progressAdvance();
        }
        $this->output->progressFinish();
        $this->info("Folders created.");
    }
}
