<?php
namespace App\Controllers;

use App\Models\FavoriteUserCategory;
use Exception;

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
            return $response->withStatus($ex->getCode());
        }
    }

    public function addFavoriteUserCategory($request, $response)
    {
        try {
            $data = $request->getParsedBody();
            $favorite_user_category = $this->save($data);
            $response->getBody()->write(json_encode($favorite_user_category));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    private function save($data) {
        try {
            return FavoriteUserCategory::updateOrCreate(['id' => $data['id']],
                [
                    'user_id' => $data['user_id'],
                    'name' => $data['name'],
                ]);
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while inserting data to database!',500);
        }
    }
}