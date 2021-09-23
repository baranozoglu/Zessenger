<?php
namespace App\Exception;

class CouldNotUploadFileException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('Could not upload file!', 400);
    }
}