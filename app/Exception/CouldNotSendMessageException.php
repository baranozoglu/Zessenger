<?php
namespace App\Exception;

class CouldNotSendMessageException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('Could not send message to user who is not in your favorite user list!',400);
    }
}