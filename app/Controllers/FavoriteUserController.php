<?php
namespace App\Controllers;

use App\Models\FavoriteUser;
use App\Models\FavoriteUserCategory;
use Exception;

class FavoriteUserController extends Controller
{
    public function getFavoriteUsersByUserId($request, $response, $args)
    {
        try {
            $favorite_users = $this->query($args);
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
            $data = $request->getParsedBody();
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
            throw new Exception('Something went wrong while deleting data from database!',500);
        }
    }

    private function validate($data) {
        $user = User::whereRaw('id = ? ', [$data['favorite_user_id']])->get();
        if(count($user) == 0) {
            throw new Exception('Could not find user which you want to add as favorite!',404);
        }

        $favorite_user_category = FavoriteUserCategory::whereRaw('id = ? ', [$data['favorite_user_category_id']])->get();
        if(count($favorite_user_category) == 0) {
            throw new Exception('Could not find favorite user category which you want to add favorite user!',404);
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
                ]);
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while inserting data to database!',500);
        }
    }

    private function query($args) {
        try {
            return FavoriteUser::join('favorite_user_categories', 'favorite_user_categories.id', '=', 'favorite_users.user_id')
                ->whereRaw('favorite_users.user_id = ?', [$args['user_id']])
                ->get(['favorite_users.*', 'favorite_user_categories.name']);
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while getting data from database!',500);
        }
    }
}