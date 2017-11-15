<?php

namespace App\Services;

use App\Interfaces\GetMediaInterface;

use Storage;
use MyHelperService;

class FileGathererService
implements GetMediaInterface
{

    /**
     * Could be photos, videos.
     *
     * @var string
     */
    public $mediaType;

    /**
     * If any errors appeared in the process.
     *
     * @var boolean
     */
    public $hasErrors = false;

    /**
     * Errors description if any.
     *
     * @var array
     */
    public $errors = [];

    /**
     * Link to our photos storage object.
     *
     * @var Storage
     */
    private $storage;


    /**
     * Set the media type before continue any actions with this method.
     *
     * @param string $type
     * @return void
     */
    public function setMediaType(string $type)
    {

        $this->mediaType = $type;

        return;

    }

    /**
     * Get one media by selected id.
     *
     * @param  string $id : file name
     * @return array
     */
    public function getOne(string $id)
    {

        return [0 => "Here you go, one file"];

    }

    /**
     * Get all non-system (starting with dot) and with correct name files.
     * Correct names means, that all files should match 'simpleString' method
     * rules - they should be consist of numbers and letters only.
     *
     * @return array
     */
    public function getAll()
    {

        try {

            $storage = Storage::disk($this->mediaType);
            $thumbs  = Storage::disk($this->mediaType . 'Thumbnails');
            $files   = $storage->allFiles();
            $photos  = [];
            $counter = 0;

            foreach ($files as $file) {

                /* Id is a file name without extension. */
                $id    = substr($file, 0, strrpos($file, '.'));
                /* File extention. */
                $ext   = substr($file, strrpos($file, '.') + 1);
                /* Real path to a file. */
                $rPath = $storage->getAdapter()->getPathPrefix();

                if (substr($file, 0, 1) == '.') continue;
                if (!MyHelperService::simpleString($id)) continue;

                /* Expand photos array, if all data is correct */
                $photos[$counter]['id']   = $id;
                $photos[$counter]['name'] = '';
                $photos[$counter]['path'] = $storage->url($file);
                $photos[$counter]['thmb'] = $thumbs->url($id . '.jpg');
                $photos[$counter]['date'] = filectime($rPath);
                $photos[$counter]['size'] = round($storage->size($file) / 1024);
                $photos[$counter]['type'] = $storage->mimeType($file);

                $counter++;

            }

            return $photos;

        } catch (\Exception $e) {

            $this->hasErrors = true;
            $this->errors[] = 'Error: ' . $e->getMessage();

        }

    }

}