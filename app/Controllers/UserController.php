<?php


namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function getUsers($request, $response)
    {
        $users = User::all();
        $response->getBody()->write(json_encode($users));
        return $response;
    }
}