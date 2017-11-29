<?php

namespace App\Services;

use App\Interfaces\CreateThumbnailsInterface;

use Image;
use MyHelperService;

class CreateThumbnailsService
implements CreateThumbnailsInterface
{
    /**
     * Defined source - directory.
     *
     * @var string
     */
    private static $directory = false;

    /**
     * Defined source - file.
     *
     * @var string
     */
    private static $file = false;

    /**
     * Prefix name for thumbnail files.
     *
     * @var string
     */
    private static $thumbPrefix = '';

    /**
     * Thumbnail width.
     *
     * @var integer
     */
    private static $thumbWidth = 400;

    /**
     * Create thumbnails either for whole directory, or for single file.
     *
     * @param  string $path : path to a file or folder
     * @param  string $saveTo : path where file(s) would be saved
     * @return bool
     */
    public static function make(string $path, string $saveTo): bool
    {

        self::defineSource($path);

        if (self::$directory)
            return self::fromDirectory($path, $saveTo);

        if (self::$file)
            return self::fromFile($path, $saveTo);

    }

    /**
     * Define source. It could be a directory, or a single file.
     *
     * @return void
     */
    private static function defineSource(string $path)
    {

        if (is_file($path))
            self::$file = true;
        else
            self::$directory = true;

        return;

    }

    /**
     * Iterate thorough each file on selected directory and create thumbnails.
     *
     * @param  string $path : path to a file or folder
     * @param  string $saveTo : path where file(s) would be saved
     * @return void
     */
    private static function fromDirectory(string $path, string $saveTo)
    {

        $files = scandir($path);

        try {

            foreach ($files as $key => $file) {

                $id  = substr($file, 0, strrpos($file, '.'));
                $ext = substr($file, strrpos($file, '.') + 1);

                if (substr($file, 0, 1) == '.') continue;
                if (!MyHelperService::simpleString($id)) continue;

                $thumbnail = Image::make($path . $file);
                $thumbnail->resize(self::$thumbWidth, null, function($constraint) {

                    $constraint->aspectRatio();
                    $constraint->upsize();

                });
                $thumbnail->save($saveTo . self::$thumbPrefix . $file);

            }

        } catch(\Exception $e) {

            dd($e->getMessage());

        }

    }

    /**
     * Create thumbnail for single file.
     *
     * @param  string $path : path to a file or folder
     * @param  string $saveTo : path where file(s) would be saved
     * @return void
     */
    private static function fromFile(string $path, string $saveTo)
    {

        dd('from file');

    }

}