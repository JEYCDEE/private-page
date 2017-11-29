<?php

namespace App\Interfaces;

interface DeleteMediaInterface
{

    /**
     * Delete single media.
     *
     * @param int $id : file name
     *
     * @return array
     */
    public function deleteOne(int $id): array;

    /**
     * Delete multiple elements from media folder.
     *
     * @param array $ids : simple array with id's (file names)
     *
     * @return array
     */
    public function deteleMultiple(array $ids): array;

}
