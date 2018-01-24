<?php

namespace App\Interfaces;

interface NewsPostsInterface
{

    /**
     * @return array : should be unserialized array
     */
    public function getAll(): array;

    /**
     * @param string $date : should be in YYYY-MM-DD format
     *
     * @return array : should be unserialized array
     */
    public function getByDate(string $date): array;

    /**
     * @param string $dateTime : should be in YYYY-MM-DD HH:MM format
     *
     * @return array : should be unserialized array
     */
    public function getByDateTime(string $dateTime): array;

    /**
     * Add new post.
     *
     * @param string $data : value of the input or textarea field
     *
     * @return array
     */
    public function add(string $data): array;

    /**
     * Remove one post by date and time keys pair.
     *
     * @param string $date : post date, like '2018-01-01'
     * @param string $time : post time, like '08:45'
     *
     * @return array
     */
    public function deleteOne(string $date, string $time): array;

}