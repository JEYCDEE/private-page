<?php

namespace App\Http\Responses\Contacts;

use App\Interfaces\MyParserInterface;
use App\Interfaces\MyResponsableInterface;
use Illuminate\Contracts\Support\Responsable;

use App\Services\FileParserService;

class ContactsIndexResponse
implements Responsable, MyResponsableInterface
{

    /**
     * File parsing service.
     *
     * @var FileParserService
     */
    private $fileParserS;

    /**
     * Parser, that implements specific interface.
     *
     * @var MyParserInterface
     */
    private $myParserI;

    /**
     * Constructor.
     *
     * @param FileParserService $fileParserS
     * @param MyParserInterface $myParserI
     */
    public function __construct(FileParserService $fileParserS, MyParserInterface $myParserI)
    {

        $this->fileParserS = $fileParserS;
        $this->myParserI = $myParserI;

    }

    /**
     * Implemented method, that returns the result like controller does.
     *
     * @param \Illuminate\Http\Resources\Json\response $response
     * @return \Illuminate\Http\Response
     */
    public function toResponse($response)
    {

        $this->fileParserS->prepareData('contacts', 'contacts.csv');
        if ($this->fileParserS->hasError)
            return $this->errorsHappened($this->fileParserS->errors);

        $this->fileParserS->parseData($this->myParserI);
        if ($this->fileParserS->hasError)
            return $this->errorsHappened($this->fileParserS->errors);

        return $this->itWorks($this->fileParserS->getData());

    }

    /**
     * Return this on errors.
     *
     * @param  array  $errors
     *
     * @return \Illuminate\Http\Response
     */
    public function errorsHappened(array $errors)
    {

        return view('pages.contacts.error', [
            'errors' => $errors,
        ]);

    }

    /**
     * Return this on success.
     *
     * @param  stdClass $contacts
     *
     * @return \Illuminate\Http\Response
     */
    public function itWorks(\stdClass $data)
    {

        return view('pages.contacts.index', [
            'contacts' => $data,
        ]);

    }

}