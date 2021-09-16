<?php
namespace App\Exception;

class GetDatabaseException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('Something went wrong while getting data from database!',500);
    }
}