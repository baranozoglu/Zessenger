<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Exception\CouldNotFoundUserException;
use App\Exception\CouldNotSendMessageException;
use App\Exception\DeleteDatabaseException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Exception\UpdateDatabaseException;
use App\Repository\FavoriteUserCategoryRepository;
use App\Repository\FavoriteUserRepository;
use App\Repository\UserRepository;
use Exception;

global $favoriteUserRepository;
$favoriteUserRepository = new FavoriteUserRepository();

class FavoriteUserController extends Controller
{
    public function getFavoriteUsersByUserId($request, $response)
    {
        try {
            $loggedUser = Auth::user();
            $favorite_users = $this->query($loggedUser);
            $response->getBody()->write(json_encode($favorite_users));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function addFavoriteUser($request, $response)
    {
        try {
            $loggedUser = Auth::user();
            $data = $request->getParsedBody();
            $data['user_id'] = $loggedUser['id'];
            $this->validate($data);
            $favorite_user = $this->save($data);
            $response->getBody()->write(json_encode($favorite_user));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        global $favoriteUserRepository;
        try {
            $favoriteUserRepository->destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }

    private function validate($data) {
        $userRepository = new UserRepository();
        $user = $userRepository->getUserById($data['favorite_user_id']);
        if($user == null) {
            throw new CouldNotFoundUserException();
        }
        $favoriteUserCategoryRepository = new FavoriteUserCategoryRepository();
        $favorite_user_category = $favoriteUserCategoryRepository->getFavoriteUserCategoryById($data['favorite_user_category_id']);
        if($favorite_user_category == null) {
            throw new CouldNotFoundUserException();
        }
    }

    private function save($data) {
        global $favoriteUserRepository;
        try {
            return $favoriteUserRepository->save($data);
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    public function query($loggedUser) {
        global $favoriteUserRepository;
        try {
            return $favoriteUserRepository->getFavoriteUsers($loggedUser['id']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }

    public function updateLastMessageTime($id_list) {
        global $favoriteUserRepository;
        try {
            var_dump("id->>>".json_encode($id_list));
            return $favoriteUserRepository->updateLastMessageTime(json_encode($id_list));
        } catch (Exception $ex) {
            throw new UpdateDatabaseException();
        }
    }

    public function findFavoriteUserIdList($data) {
        global $favoriteUserRepository;
        try {
            $obj_list = $favoriteUserRepository->findFavoriteUserIdList($data['user_id']);
            return self::converter($obj_list);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }

    private function converter($obj_list) {
        try {
            if (count($obj_list) == 0) {
                throw new Exception();
            }
            $id_list = array();
            foreach ($obj_list as $obj) {
                array_push($id_list, $obj['id']);
            }
            return $id_list;
        } catch (Exception $ex) {
            throw new CouldNotSendMessageException();
        }
    }
}