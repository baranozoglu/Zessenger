<?php
namespace App\Exception;

class PermissionDeniedException extends \Exception {
    /**
     * CouldNotFoundUserException constructor.
     */
    public function __construct()
    {
        parent::__construct('Permission denied!',403);
    }
}