<?php

namespace App\Services;

use Illuminate\Filesystem\FilesystemAdapter;

class MyHelperService
{

    /**
     * Convert any array into an object.
     *
     * @param  array  $dataArr : any array
     * @return object
     */
    public static function arrToObj(array $dataArr, $deep = false)
    {

        $object = new \stdClass();

        if ($deep) {
            # Solution for multi dimension.
        } else {
            foreach ($dataArr as $key => $arr)
                if ($key > 0)
                    $object->$key = (object) $arr;
        }

        return $object;

    }

    /**
     * Check if any chars live inside a string, other than numbers and letters.
     *
     * @param  string $string : simple string value
     * @return bool
     */
    public static function simpleString(string $string)
    {

        return !preg_match('/[^A-Za-z0-9]/', $string);

    }

    /**
     * Create symbolic links to media folders from /storage/ into /public/ so we
     * can use them in our website.
     *
     * @param  Illuminate\Filesystem\FilesystemAdapter $resource
     * @param  string $saveTo : media type, like 'videos', 'photos'
     * @return mixed
     */
    public static function mediaSymlinks(FilesystemAdapter $resource, $saveTo)
    {
        $files  = $resource->allFiles();
        $errors = [];

        foreach ($files as $file) {

            $id = substr($file, 0, strrpos($file, '.'));

            if (substr($file, 0, 1) == '.') continue;
            if (!self::simpleString($id)) continue;

            $pathFrom = $resource->path($file);
            $pathTo   = $saveTo . "$file";

            exec("ln -s '$pathFrom' '$pathTo' 2>&1", $errors, $fail);

        }

        return $fail ? $errors : true;
    }

}