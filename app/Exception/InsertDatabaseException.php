<?php
namespace App\Exception;

class InsertDatabaseException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('Something went wrong while inserting data to database!',500);
    }
}