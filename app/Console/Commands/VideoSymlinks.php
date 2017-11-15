<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Storage;

use MyHelperService;

class VideoSymlinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'symlinks:videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates symbolic links for storage/../videos';

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
     * Execute the console command. Look for /storage/app/public/videos
     * directory and make symbolic links for all of this files placing them
     * into /public/videos directory.
     *
     * @return mixed
     */
    public function handle()
    {

        $resource = Storage::disk('videos');
        $saveTo   = Storage::disk('videosPublic')->getAdapter()->getPathPrefix();

        echo "Done.\n";

        return MyHelperService::mediaSymlinks($resource, $saveTo);

    }
}
