<?php

namespace App\Http\Controllers;

use App\Services\NewsPosts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Cookie;

class HomeController extends Controller
{

    /**
     * List of defined language constants. This languages should exist in
     * 'resources/lang' set.
     *
     * @var array
     */
    private $appLanguages = ['en', 'ru'];

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
     * HomeController constructor.
     *
     * @param Redis $redis
     * @param NewsPosts $newsPosts
     */
    public function __construct(Redis $redis, NewsPosts $newsPosts)
    {

        $this->setLanguage();

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

            'newsPosts'  => $this->newsPosts->getAll(),
            'isRoot'     => $this->redis::get('isRoot'),

        ]);

    }

    /**
     * Switch web app language by setting new cookie.
     *
     * @param string $lang : language name to switch to
     *
     * @return \Illuminate\Support\Facades\Redirect.
     */
    public function switchLanguage(string $lang)
    {

        Cookie::queue(Cookie::forever('lang', $lang));

        App::setLocale($lang);

        return Redirect::to('/home');

    }

    /**
     * Set current web application language, or return to default one.
     *
     * @return void
     */
    private function setLanguage()
    {

        $language = Cookie::get('lang', 'en');

        if (!in_array($language, $this->appLanguages))
            $language = Crypt::decrypt($language);

        if (!in_array($language, $this->appLanguages))
            $language = 'en';

        App::setLocale($language);

        return;

    }

    /**
     * Get current language all constants.
     *
     * @return object
     */
    public function getLanguage()
    {

        return json_encode(Lang::get('common'));

    }

    /**
     * This is navigation bar part for media. '$link' is a URL link, came from
     * photo or video 'src' attribute and sent via ajax request here.
     *
     * @param Request $request
     *
     * @var string $link : link for <a> element for downloading picture
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

    /**
     * This is control panel for root user.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function partControlPanel()
    {

        return view('parts.control-panel', []);

    }

    /**
     * This is a form for adding new posts to the home screen.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function partNewsPostForm()
    {

        return view('parts.news-post-form', []);

    }
}
