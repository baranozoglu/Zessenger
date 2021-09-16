<?php
namespace App\Exception;

class UpdateDatabaseException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('Something went wrong while updating last message time on database!',500);
    }
}