<?php
namespace App\Controllers;

use App\Models\FavoriteUserCategory;
use Exception;

class FavoriteUserCategoryController extends Controller
{
    public function getFavoriteUserCategoriesByUserId($request, $response, $args)
    {
        try {
            $blacklist = $this->query($args);
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

    public function delete($request, $response, $args) {
        try {
            FavoriteUserCategory::destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while deleting data from database!',500);
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

    private function query($args) {
        try {
            return FavoriteUserCategory::whereRaw('user_id = ? ', [$args['user_id']])->get();
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while getting data from database!',500);
        }
    }
}