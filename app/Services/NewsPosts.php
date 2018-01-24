<?php

namespace App\Services;

use App\Interfaces\NewsPostsInterface;
use Illuminate\Support\Facades\Redis;

class NewsPosts
implements NewsPostsInterface
{

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
     * Get all posts for all times.
     *
     * @return array : should be unserialized array
     */
    public function getAll(): array
    {

        return array_reverse(unserialize(Redis::get('news:all')));

    }

    /**
     * Get only posts under given date.
     *
     * @param string $date : should be in YYYY-MM-DD format
     *
     * @return array
     */
    public function getByDate(string $date): array
    {
        // TODO: Implement getByDate() method.
    }

    /**
     * Get only posts under certain time, that is under given date.
     *
     * @param string $dateTime : should be in YYYY-MM-DD HH:MM format
     *
     * @return string : should be unserialized array
     */
    public function getByDateTime(string $dateTime): array
    {
        // TODO: Implement getByDateTime() method.
    }

    /**
     * Add new post.
     *
     * @param string $data : value of the input or textarea field
     *
     * @return array
     */
    public function add(string $data): array
    {

        try {

            $postDate = date('Y-m-d');
            $postTime = date('H:i');
            $oldPosts = unserialize(Redis::get('news:all'));
            $newPosts = $oldPosts;

            $newPosts[$postDate][$postTime] = $data;

            Redis::set('news:all', serialize($newPosts));

            return [
                'success' => true,
            ];

        } catch(\Exception $e) {

            $this->hasErrors = true;
            $this->errors[] = 'Error: ' . $e->getMessage();

        }

        return [
            'success' => false,
            'errors'  => implode('\n', $this->errors)
        ];

    }

    /**
     * Remove one post by date and time keys pair.
     *
     * @param string $date : post date, like '2018-01-01'
     * @param string $time : post time, like '08:45'
     *
     * @return array
     */
    public function deleteOne(string $date, string $time): array
    {

        $posts = array_reverse(unserialize(Redis::get('news:all')));

        return $posts;

    }
}