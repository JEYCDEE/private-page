<?php

namespace App\Services;

use App\Interfaces\FileParserInterface;
use App\Interfaces\HasErrorsInterface;
use App\Interfaces\MyParserInterface;

use Storage;
use MyHelperService;

class FileParserService
implements FileParserInterface, HasErrorsInterface
{

    /**
     * If any errors appeared in the process.
     *
     * @var boolean
     */
    public $hasError = false;

    /**
     * Errors description if any.
     *
     * @var array
     */
    public $errors = [];

    /**
     * Name of the disk from filesystems.php.
     *
     * @var string
     */
    public $diskName = '';

    /**
     * Filename you want to work with.
     *
     * @var string
     */
    public $fileName = '';

    /**
     * The result(parsed) data you want to return.
     *
     * @var mixed
     */
    protected $parsedData;

    /**
     * File itself.
     *
     * @var string
     */
    protected $file;

    /**
     * Make some preparation before using this service.
     *
     * @param  string $diskName : name of the disk from filesystems.php
     * @param  string $fileName : name of the file we are going to work with
     *
     * @return void
     */
    public function prepareData(string $diskName, string $fileName)
    {

        $this->diskName = $diskName;
        $this->fileName = $fileName;

        try {

            $this->file = Storage::disk($this->diskName)->get($this->fileName);

        } catch (\Exception $e) {

            $this->hasError = true;

            if (empty($this->file))
                $this->errors[] = 'Error: ' . $this->fileName . ' does not exist';
            else
                $this->errors[] = 'Error: ' . $e->getMessage();

        }

        return;

    }

    /**
     * Process data using defined method.
     *
     * @return void
     */
    public function parseData(MyParserInterface $myParserI)
    {

        try {

            $this->parsedData = $myParserI->parseFromFile($this->file);

        } catch (\Exception $e) {

            $this->hasError = true;
            $this->errors[] = 'Error: ' . $e->getMessage();

        }

        return;

    }

    /**
     * Get the result data.
     *
     * @return object
     */
    public function getData()
    {

        return MyHelperService::arrToObj($this->parsedData);

    }

    /**
     * Check if any errors appeared during the proccess and return them if so.
     *
     * @return mixed
     */
    public function hasErrors()
    {

        return $this->hasError
            ? (object) $this->errors
            : false;

    }

}

