<?php
namespace App\Exception;

class DeleteDatabaseException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('Something went wrong while deleting data from database!',500);
    }
}