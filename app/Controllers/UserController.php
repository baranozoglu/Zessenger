<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Exception\CouldNotFoundUserException;
use App\Exception\DeleteDatabaseException;
use App\Exception\PermissionDeniedException;
use App\Models\User;
use Exception;
use App\Repository\UserRepository;

class UserController extends Controller
{
    public function getUsers($request, $response)
    {
        try {
            $userRepository = new UserRepository();
            $users = $userRepository->getAll();
            $response->getBody()->write(json_encode($users));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        try {
            $userRepository = new UserRepository();
            $permission = $this->permission($args['id'], $userRepository);

            if($permission) {
                $userRepository->destroy($args['id']);
            } else {
                throw new CouldNotFoundUserException();
            }
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }

    public function permission($id, $userRepository) {
        try {
            $loggedUser = Auth::user();
            if($userRepository->getUserById($id) == null) {
                throw new Exception();
            }
            return $userRepository->getUserById($id) == $loggedUser;
        } catch (Exception $ex) {
            throw new PermissionDeniedException();
        }
    }
}