<?php
namespace App\Exception;

class FavoriteMessageException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('You can not add message which is not relative with you to your favorite messages!',400);
    }
}