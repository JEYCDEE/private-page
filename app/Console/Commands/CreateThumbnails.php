<?php

namespace App\Console\Commands;

use App\Interfaces\CreateThumbnailsInterface;

use Storage;
use CreateThumbnailsService;
use Illuminate\Console\Command;

class CreateThumbnails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbnails:photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create thumbnails for photos';

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

        $this->createThumbnails(new CreateThumbnailsService());

    }

    /**
     * Initiating actual operation.
     *
     * @param  CreateThumbnailsInterface $thumbnailsI
     * @return mixed
     */
    private function createThumbnails(CreateThumbnailsInterface $thumbnailsI)
    {

        $path   = Storage::disk('photos')->getAdapter()->getPathPrefix();
        $saveTo = Storage::disk('photosThumbnails')->getAdapter()->getPathPrefix();

        echo "Done.\n";

        $thumbnailsI::make($path, $saveTo);

    }
}
