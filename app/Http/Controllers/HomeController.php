<?php

namespace App\Http\Controllers;

use App\Services\NewsPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class HomeController extends Controller
{
    /**
     * @var Redis
     */
    private $redis;

    /**
     * @var NewsPosts
     */
    private $newsPosts;

    /**
     * HomeController constructor.
     *
     * @param Redis $redis
     * @param NewsPosts $newsPosts
     */
    public function __construct(Redis $redis, NewsPosts $newsPosts)
    {

        $this->redis     = $redis;
        $this->newsPosts = $newsPosts;

    }

	/**
	 * This is home page, that welcomes you.
	 *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function index()
    {

    	return view('pages.home', [

    	    'newsPosts' => $this->newsPosts->getAll(),
            'isRoot'    => $this->redis::get('isRoot'),

        ]);

    }

    /**
     * This is navigation bar part for media. '$link' is a URL link, came from
     * photo or video 'src' attribute and sent via ajax request here.
     *
     * @param Request $request
     *
     * @var string $link : link for <a> elemnt for downlaoding picture
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function partMediaNavbar(Request $request)
    {

        $link = $request->get('link');

        return view('parts.media-navbar', [

            'link' => $link,

        ]);

    }


    /**
     * This is simple login form box.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function partLoginBox()
    {

        return view('parts.login-box', []);

    }

    public function partControlPanel()
    {

        return view('parts.control-panel', []);

    }
}
