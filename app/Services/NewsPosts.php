<?php

namespace App\Services;

use App\Interfaces\NewsPostsInterface;
use Illuminate\Support\Facades\Redis;

class NewsPosts
implements NewsPostsInterface
{

    /**
     * Get all posts for all times.
     *
     * @return string : should be unserialized array
     */
    public function getAll()
    {

        Redis::set('news:all', serialize(
            [
                '2017-11-13' => [
                    "09:54" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.",
                    "14:22" => "It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                ],
                '2017-11-14' => [
                    '15:42' => "Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).",
                ],
                '2017-11-15' => [
                    '10:05' => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.",
                    '17:23' => "Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of \"de Finibus Bonorum et Malorum\" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, \"Lorem ipsum dolor sit amet..\", comes from a line in section 1.10.32.",
                    '19:57' => "The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from \"de Finibus Bonorum et Malorum\" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham",
                ],
            ]
        ));

        return array_reverse(unserialize(Redis::get('news:all')));

    }

    /**
     * Get only posts under given date.
     *
     * @param string $date : should be in YYYY-MM-DD format
     *
     * @return mixed
     */
    public function getByDate(string $date)
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
    public function getByDateTime(string $dateTime)
    {
        // TODO: Implement getByDateTime() method.
    }
}