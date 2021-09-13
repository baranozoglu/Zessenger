<?php


namespace App\Controllers;

use App\Models\FavoriteUserCategory;

class FavoriteUserCategoryController extends Controller
{
    public function getFavoriteUserCategoriesByUserId($request, $response)
    {
        try {
            $data = $request->getQueryParams();
            $blacklist = FavoriteUserCategory::whereRaw('user_id = ? ', [$data['user_id']])->get();
            $response->getBody()->write(json_encode($blacklist));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus(500);
        }
    }

    public function addFavoriteUserCategory($request, $response)
    {
        try {
            $data = $request->getParsedBody();

            $favorite_user_category = FavoriteUserCategory::create([
                'user_id' => $data['user_id'],
                'name' => $data['name'],
            ]);

            $response->getBody()->write(json_encode($favorite_user_category));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus(500);
        }
    }
}