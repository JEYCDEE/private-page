<?php

namespace App\Services;

use App\Interfaces\LoginInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LoginService
implements LoginInterface
{

    /**
     * Login root user.
     *
     * @param Request $request : assotiative array containing login and password
     *
     * @return boolean
     */
    public function loginAction(Request $request): bool
    {

        $userLogin    = $request->get('login');
        $userPassword = md5(md5($request->get('password')));
        $rootLogin    = env('ROOT_LOGIN');
        $rootPassword = env('ROOT_PASSWORD');

        if ($userLogin == $rootLogin && $userPassword == $rootPassword) {
            return (bool) Redis::set('isRoot', true);
        }

        return false;

    }

    /**
     * Logout root user from the system.
     *
     * @return boolean
     */
    public function logoutAction(): bool
    {

        return (bool) Redis::set('isRoot', false);

    }
}