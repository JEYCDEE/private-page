<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LoginService;

class LoginController
extends Controller
{

    /**
     * Service responsable for login and logout actions.
     *
     * @var LoginService
     */
    private $loginService;

    public function __construct(LoginService $loginService)
    {

        $this->loginService = $loginService;

    }

    /**
     * Check if user is already loggied in and do it if not.
     *
     * @param Request $request : every bit of incomming information
     *
     * @return boolean
     */
    public function loginAction(Request $request)
    {

        return json_encode($this->loginService->loginAction($request));

    }

    /**
     * Remove current user from root privileges, so he van only view app.
     *
     * @return boolean
     */
    public function logoutAction()
    {

        return json_encode($this->loginService->logoutAction());

    }

}
