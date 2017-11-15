<?php

namespace App\Interfaces;

interface HasErrorsInterface
{

    /**
     * Let user know if any error occured.
     *
     * @return mixed
     */
    public function hasErrors();

}