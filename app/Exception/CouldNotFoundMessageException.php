<?php
namespace App\Exception;

class CouldNotFoundMessageException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('Could not found message!',404);
    }
}