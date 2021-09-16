<?php
namespace App\Exception;

class BlockedUserException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('You have blocked by user which you want to send message!',400);
    }
}