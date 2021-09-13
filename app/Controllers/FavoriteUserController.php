<?php


namespace App\Controllers;

use App\Models\FavoriteUser;

class FavoriteUserController extends Controller
{
    public function getFavoriteUsersByUserId($request, $response)
    {
        try {
            $data = $request->getQueryParams();
            $favorite_users = FavoriteUser::whereRaw('user_id = ?', [$data['user_id']])->get();
            $response->getBody()->write(json_encode($favorite_users));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus(500);
        }
    }

    public function getFavoriteUsersByCategoryId($request, $response)
    {
        try {
            $data = $request->getQueryParams();
            $favorite_users = FavoriteUser::whereRaw('favorite_user_category_id = ?', [$data['favorite_user_category_id']])->get();
            $response->getBody()->write(json_encode($favorite_users));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus(500);
        }
    }

    public function addFavoriteUser($request, $response)
    {
        try {
            $data = $request->getParsedBody();

            $favorite_user = FavoriteUser::create([
                'user_id' => $data['user_id'],
                'favorite_user_category_id' => $data['favorite_user_category_id'],
                'favorite_user_id' => $data['favorite_user_id'],
                'nickname' => $data['nickname'],
            ]);

            $response->getBody()->write(json_encode($favorite_user));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus(500);
        }
    }
}