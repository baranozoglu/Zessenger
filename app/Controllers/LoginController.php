<?php
namespace App\Controllers;

use App\Exception\DeleteDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Repository\LoginRepository;
use Exception;

global $loginRepository;
$loginRepository = new LoginRepository();

class LoginController extends Controller
{
    public function getLoginsByUserId($request, $response, $args)
    {
        try {
            $loginRepository = new LoginRepository();
            $favorite_users = $loginRepository->getLoginsByUserId($args['user_id']);
            $response->getBody()->write(json_encode($favorite_users));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function addLogin($request, $response)
    {
        try {
            $data = $request->getParsedBody();
            $favorite_user = $this->save($data['user_id']);
            $response->getBody()->write(json_encode($favorite_user));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function save($data) {
        global $loginRepository;
        try {
            return $loginRepository->save($data);
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    public function delete($request, $response, $args) {
        global $loginRepository;
        try {
            $loginRepository->destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }
}