<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface LoginInterface
{

    /**
     * Login root user.
     *
     * @param Request $request : assotiative array containing login and password
     *
     * @return boolean
     */
    public function loginAction(Request $request): bool;

    /**
     * Logout root user from the system.
     *
     * @param string $userId : user identificator, could be anything
     *
     * @return boolean
     */
    public function logoutAction(): bool;

}