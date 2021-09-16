<?php
namespace App\Exception;

class CouldNotFoundUserException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('Could not found user!',404);
    }
}