<?php

namespace App\Http\Responses\Photos;

use App\Interfaces\MyResponsableInterface;
use Illuminate\Contracts\Support\Responsable;

use App\Services\FileGathererService;
use App\Services\MyHelperService;

class PhotosIndexResponse
implements Responsable, MyResponsableInterface
{

    /**
     * File gathering service.
     *
     * @var FileGathererService
     */
    private $fileGathererS;

    /**
     * Media type for this category.
     *
     * @var string
     */
    private $mediaType = 'photos';

    /**
     * Constructor.
     *
     * @param FileGathererService $fileGathererS
     */
    public function __construct(FileGathererService $fileGathererS)
    {

        $this->fileGathererS = $fileGathererS;

        /* Set media type. */
        $this->fileGathererS->setMediaType($this->mediaType);

    }

    /**
     * Implemented method, that returns the result like controller does.
     *
     * @param \Illuminate\Http\Resources\Json\response $response
     * @return \Illuminate\Http\Response
     */
    public function toResponse($response)
    {

        $data = $this->fileGathererS->getAll();

        if ($this->fileGathererS->hasErrors)
            return $this->errorsHappened($this->fileGathererS->errors);

        return $this->itWorks(MyHelperService::arrToObj($data));

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

        return view('pages.photos.error', [
            'errors' => $errors,
        ]);

    }

    /**
     * Return this on success.
     *
     * @param  stdClass $photos
     *
     * @return \Illuminate\Http\Response
     */
    public function itWorks(\stdClass $data)
    {

        return view('pages.photos.index', [
            'mediaType' => $this->mediaType,
            'photos' => $data,
        ]);

    }
}