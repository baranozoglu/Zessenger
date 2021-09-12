<?php


namespace App\Controllers;

use App\Models\FavoriteUserCategory;

class FavoriteUserCategoryController extends Controller
{
    public function getFavoriteUserCategoriesByUserId($request, $response)
    {
        $data = $request->getQueryParams();
        $blacklist = FavoriteUserCategory::whereRaw('user_id = ? ', [$data['user_id']])->get();
        $response->getBody()->write(json_encode($blacklist));
        return $response;
    }

    public function addFavoriteUserCategory($request, $response)
    {
        $data = $request->getParsedBody();

        $favorite_user_category = FavoriteUserCategory::create([
            'user_id' => $data['user_id'],
            'name' => $data['name'],
        ]);

        $response->getBody()->write(json_encode($favorite_user_category));
        return $response;
    }
}