<?php

namespace App\Interfaces;

interface CreateThumbnailsInterface
{

    /**
     * Initializing process method.
     *
     * @param  string $path : path to a file or folder
     * @param  string $saveTo : path where file(s) would be saved
     *
     * @return boolean
     */
    public static function make(string $path, string $saveTo): bool;

}