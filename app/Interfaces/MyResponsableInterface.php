<?php

namespace App\Interfaces;

interface MyResponsableInterface
{

    /**
     * When unsuccess.
     *
     * @param  array  $errors : errors, appeared in ducing the process
     *
     * @return \Illuminate\Http\Response
     */
    public function errorsHappened(array $errors);

    /**
     * When success.
     *
     * @param  \stdClass $data : all necessary processed data we need
     *
     * @return \Illuminate\Http\Response
     */
    public function itWorks(\stdClass $data);

}