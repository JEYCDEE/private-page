<?php

namespace App\Interfaces;

interface GetMediaInterface
{

    /**
     * Get one media by selected id.
     *
     * @param string $id : file name
     *
     * @return array
     */
    public function getOne(string $id): array;

    /**
     * Get all media files.
     *
     * @return array
     */
    public function getAll(): array;

    /**
     * Set the media type before continue any actions with this method.
     *
     * @param string $type
     *
     * @return void
     */
    public function setMediaType(string $type);

}