<?php
namespace App\Exception;

class AuthException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('You have to login to access!',401);
    }
}