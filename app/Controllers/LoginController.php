<?php


namespace App\Controllers;

use App\Models\Login;

class LoginController extends Controller
{
    public function getLoginsByUserId($request, $response)
    {
        $data = $request->getQueryParams();
        $favorite_users = Login::whereRaw('user_id = ?', [$data['user_id']])->get();
        $response->getBody()->write(json_encode($favorite_users));
        return $response;
    }

    public function addLogin($request, $response)
    {
        $data = $request->getParsedBody();

        $favorite_user = Login::create([
            'user_id' => $data['user_id'],
        ]);

        $response->getBody()->write(json_encode($favorite_user));
        return $response;
    }
}