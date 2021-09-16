<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Exception\CouldNotFoundUserException;
use App\Exception\CouldNotSendMessageException;
use App\Exception\DeleteDatabaseException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Exception\UpdateDatabaseException;
use App\Models\FavoriteUser;
use App\Models\User;
use App\Models\FavoriteUserCategory;
use Exception;

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
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
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
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        try {
            FavoriteUser::destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }

    private function validate($data) {
        $user = User::whereRaw('id = ? ', [$data['favorite_user_id']])->get();
        if(count($user) == 0) {
            throw new CouldNotFoundUserException();
        }

        $favorite_user_category = FavoriteUserCategory::whereRaw('id = ? ', [$data['favorite_user_category_id']])->get();
        if(count($favorite_user_category) == 0) {
            throw new CouldNotFoundUserException();
        }
    }

    private function save($data) {
        try {
            return FavoriteUser::updateOrCreate(['id' => $data['id']],
                [
                    'user_id' => $data['user_id'],
                    'favorite_user_category_id' => $data['favorite_user_category_id'],
                    'favorite_user_id' => $data['favorite_user_id'],
                    'nickname' => $data['nickname'],
                    'last_message_time' => $data['last_message_time'],
                ]);
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    public function query($loggedUser) {
        try {
            return FavoriteUser::join('favorite_user_categories', 'favorite_user_categories.id', '=', 'favorite_users.user_id')
                ->whereRaw('favorite_users.user_id = ?', [$loggedUser['id']])
                ->get(['favorite_users.*', 'favorite_user_categories.name']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }

    public function updateLastMessageTime($id_list) {
        try {
            FavoriteUser::whereRaw('id in (?)', [$id_list])
                ->update(['last_message_time' => date('Y-m-d H:i:s')]);
        } catch (Exception $ex) {
            throw new UpdateDatabaseException();
        }
    }

    public function findFavoriteUserIdList($data) {
        try {
            $obj_list = FavoriteUser::whereRaw('favorite_user_id = ? or user_id = ?', [$data['sender_id'], $data['sender_id']])
                ->get('id');
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