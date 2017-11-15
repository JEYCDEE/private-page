<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

use MyHelperService;

class PhotoSymlinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'symlinks:photos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates symbolic links for storage/../photos';

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
     * Execute the console command. Look for /storage/app/public/photos
     * directory and make symbolic links for all of this files placing them
     * into /public/photos directory.
     *
     * @return mixed
     */
    public function handle()
    {

        $resource = Storage::disk('photos');
        $saveTo   = Storage::disk('photosPublic')->getAdapter()->getPathPrefix();

        echo "Done.\n";

        return MyHelperService::mediaSymlinks($resource, $saveTo);

    }
}
