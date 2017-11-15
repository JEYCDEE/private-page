<?php

namespace App\Console\Commands;

use App\Interfaces\CreateThumbnailsInterface;
use CreateVideosThumbnailsService;
use Illuminate\Console\Command;
use Storage;

class CreateVideoThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnails:videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create thumbnails for videos';

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

        $answer = $this->ask("Are you under 'sudo' (y/n)?");

        if ($answer === 'y')
            $this->createThumbnails(new CreateVideosThumbnailsService());
        else
            echo "Sorry, but you should run it with root privileges.";

    }

    /**
     * Initiating actual operation.
     *
     * @param  CreateThumbnailsInterface $thumbnailsI
     * @return mixed
     */
    private function createThumbnails(CreateThumbnailsInterface $thumbnailsI)
    {

        $path   = Storage::disk('videos')->getAdapter()->getPathPrefix();
        $saveTo = Storage::disk('videosThumbnails')->getAdapter()->getPathPrefix();

        echo "Done.\n";

        $thumbnailsI::make($path, $saveTo);

    }
}
