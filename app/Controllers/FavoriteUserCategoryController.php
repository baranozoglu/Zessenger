<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Exception\DeleteDatabaseException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Repository\FavoriteUserCategoryRepository;
use Exception;

global $favoriteUserCategoryRepository;
$favoriteUserCategoryRepository = new FavoriteUserCategoryRepository();

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
            $response->getBody()->write(json_encode($ex->getMessage()));
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
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        global $favoriteUserCategoryRepository;
        try {
            $favoriteUserCategoryRepository->destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }

    private function save($data) {
        global $favoriteUserCategoryRepository;
        try {
            return $favoriteUserCategoryRepository->save($data);
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    private function query($loggedUser) {
        global $favoriteUserCategoryRepository;
        try {
            return $favoriteUserCategoryRepository->getFavoriteUserCategories($loggedUser['id']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }
}