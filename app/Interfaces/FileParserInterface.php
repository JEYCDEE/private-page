<?php

namespace App\Interfaces;

use \App\Interfaces\MyParserInterface;

interface FileParserInterface
{

    /**
     * Prepare data to parse it.
     *
     * @param  string $path : absolute path to the file
     *
     * @return mixed
     */
    public function prepareData(string $diskName, string $fileName);

    /**
     * Parse given file.
     *
     * @param  MyParserInterface $parser
     *
     * @return mixed
     */
    public function parseData(MyParserInterface $parser);

    /**
     * Get the result data.
     *
     * @return mixed
     */
    public function getData();

}