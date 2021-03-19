<?php

namespace CloudSystems\CloudTranscribe\Exceptions;


class NotFoundException extends BaseException
{


    /**
     * Create a new exception instance.
     *
     * @return void
     *
     */
    public function __construct()
    {
        parent::__construct('The resource you are looking for could not be found.');
    }


}
