<?php

namespace App\Interfaces;

interface MyParserInterface
{

    /**
     * Parse data from file into an array.
     *
     * @param string $file : content of the file
     *
     * @return array
     */
    public function parseFromFile(string $file): array;

    /**
     * Parse data from an array into a string so we can write it to a file.
     *
     * @param array        $prettyArr : array with data
     * @param bool|boolean $store     : if true, write it to a file
     *
     * @return string
     */
    public function parseIntoFile(array $prettyArr, bool $store = false): string;

}