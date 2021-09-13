<?php
namespace App\Controllers;

use App\Models\User;
use Exception;

class UserController extends Controller
{
    public function getUsers($request, $response)
    {
        try {
            $users = User::all();
            $response->getBody()->write(json_encode($users));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }
}