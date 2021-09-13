<?php
namespace App\Controllers;

use App\Models\Login;
use Exception;

class LoginController extends Controller
{
    public function getLoginsByUserId($request, $response)
    {
        try {
            $data = $request->getQueryParams();
            $favorite_users = Login::whereRaw('user_id = ?', [$data['user_id']])->get();
            $response->getBody()->write(json_encode($favorite_users));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function addLogin($request, $response)
    {
        try {
            $data = $request->getParsedBody();

            $favorite_user = Login::create([
                'user_id' => $data['user_id'],
            ]);

            $response->getBody()->write(json_encode($favorite_user));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }
}