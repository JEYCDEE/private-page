<?php

namespace App\Services;

use App\Interfaces\MyParserInterface;

class CsvParserService
implements MyParserInterface
{

    /**
     * If any errors appeared in the process.
     *
     * @var boolean
     */
    public $hasErrors = false;

    /**
     * Errors description if any.
     *
     * @var array
     */
    public $errors = [];


    /**
     * Convert CSV string into an array.
     *
     * @param  string $file
     *
     * @return array
     */
    public function parseFromFile(string $file)
    {

        try {

            $prettyArr = [];
            $dataArr = explode("\n", rtrim($file));

            foreach ($dataArr as $row => $data) {

                $data = rtrim($data, ',');

                foreach (explode(',', $data) as $key => $val) {

                    $row === 0
                        ? $prettyArr[$row][$key] = $val
                        : $prettyArr[$row][$prettyArr[0][$key]] = $val;

                }

            }

            return $prettyArr;

        } catch (\Exception $e) {

            $this->hasErrors = true;
            $this->errors[] = 'Error: ' . $e->getMessage();

        }

    }

    /**
     * Parse array into a CSV format string.
     *
     * @param  array  $prettyArr
     *
     * @return string
     */
    public function parseIntoFile(array $prettyArr, bool $store = false)
    {

        $dataArr = [];
        $csvString = '';

        foreach ($prettyArr as $row => $data) {

            $dataArr[$row] = implode(',', $data);

            if ($row == count($prettyArr) - 1)
                $csvString = implode("\n", $dataArr);

        }

        try {

            if ($store)
                Storage::disk('contacts')->put("contactsRefactored.csv", $csvString);

            return $csvString;

        } catch (\Exception $e) {

            $this->hasErrors = true;
            $this->errors[] = 'Error: ' . $e->getMessage();

        }

    }

}