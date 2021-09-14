<?php
namespace App\Controllers;

use App\Models\Login;
use App\Models\Message;
use Exception;

class LoginController extends Controller
{
    public function getLoginsByUserId($request, $response, $args)
    {
        try {
            $favorite_users = Login::whereRaw('user_id = ?', [$args['user_id']])->get();
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

    public function delete($request, $response, $args) {
        try {
            Login::destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while deleting data from database!',500);
        }
    }
}