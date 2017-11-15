<?php

namespace App\Interfaces;

interface GetMediaInterface
{

    /**
     * Get one media by selected id.
     *
     * @param  string $id : file name
     * @return array
     */
    public function getOne(string $id);

    /**
     * Get all media files.
     *
     * @return array
     */
    public function getAll();

    /**
     * Set the media type before continue any actions with this method.
     *
     * @param string $type
     */
    public function setMediaType(string $type);

}