<?php

namespace App\Http\Controllers;

use App\Services\NewsPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class NewsController extends Controller
{

    /**
     * All news posts for home / index / news page.
     *
     * @var NewsPosts
     */
    private $newsPosts;

    /**
     * Redis database itself.
     *
     * @var Redis
     */
    private $redis;

    /**
     * NewsController constructor.
     *
     * @param Redis $redis
     * @param NewsPosts $newsPosts
     *
     * @return void
     */
    public function __construct(Redis $redis, NewsPosts $newsPosts)
    {

        $this->redis     = $redis;
        $this->newsPosts = $newsPosts;

    }

    /**
     * Add new post.
     *
     * @param Request $request
     *
     * @return object
     */
    public function add(Request $request)
    {

        $data = $request->get('data');

        return json_encode($this->newsPosts->add($data));

    }

    /**
     * Remove one post by date and time keys pair.
     *
     * @param Request $request
     *
     * @return object
     */
    public function deleteOne(Request $request)
    {

        dd('WOKRS!', $request);

    }



}
