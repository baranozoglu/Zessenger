<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Models\FavoriteUserCategory;
use Exception;

class FavoriteUserCategoryController extends Controller
{
    public function getFavoriteUserCategoriesByUserId($request, $response)
    {
        try {
            $loggedUser = Auth::user();
            $blacklist = $this->query($loggedUser);
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
            $loggedUser = Auth::user();
            $data = $request->getParsedBody();
            $data['user_id'] = $loggedUser['id'];
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
            throw new DeleteDatabaseException();
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
            throw new InsertDatabaseException();
        }
    }

    private function query($loggedUser) {
        try {
            return FavoriteUserCategory::whereRaw('user_id = ? ', [$loggedUser['id']])->get();
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }
}