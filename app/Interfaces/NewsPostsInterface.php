<?php

namespace App\Interfaces;

interface NewsPostsInterface
{

    /**
     * @return string : should be unserialized array
     */
    public function getAll();

    /**
     * @param string $date : should be in YYYY-MM-DD format
     *
     * @return string : should be unserialized array
     */
    public function getByDate(string $date);

    /**
     * @param string $dateTime : should be in YYYY-MM-DD HH:MM format
     *
     * @return string : should be unserialized array
     */
    public function getByDateTime(string $dateTime);

}