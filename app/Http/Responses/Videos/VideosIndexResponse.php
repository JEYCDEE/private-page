<?php

namespace App\Http\Responses\Videos;

use App\Interfaces\MyResponsableInterface;
use Illuminate\Contracts\Support\Responsable;

use App\Services\FileGathererService;
use App\Services\MyHelperService;

class VideosIndexResponse
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
    private $mediaType = 'videos';

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function toResponse($request)
    {

        $data = $this->fileGathererS->getAll();

        if ($this->fileGathererS->hasErrors)
            return $this->errorsHappened($this->fileGathererS->errors);

        return $this->itWorks(MyHelperService::arrToObj($data));


    }

    /**
     * When unsuccess.
     *
     * @param  array $errors : errors, appeared in ducing the process
     * @return \Illuminate\Http\Response
     */
    public function errorsHappened(array $errors)
    {
        return view('pages.videos.error', [
            'errors' => $errors,
        ]);

    }

    /**
     * When success.
     *
     * @param  \stdClass $data : all necessary processed data we need
     * @return \Illuminate\Http\Response
     */
    public function itWorks(\stdClass $data)
    {

        return view('pages.videos.index', [
            'mediaType' => $this->mediaType,
            'videos' => $data,
        ]);

    }
}