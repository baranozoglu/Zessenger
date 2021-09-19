<?php
namespace App\Repository;
use App\Models\FavoriteUserCategory;

class FavoriteUserCategoryRepository {
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
    }

    public function getAll() {
        return FavoriteUserCategory::all();
    }

    public function getFavoriteUserCategoryById($id) {
        return FavoriteUserCategory::whereRaw('id = ?', [$id]);
    }

    public function getFavoriteUserCategories($logged_user_id) {
        return FavoriteUserCategory::whereRaw('user_id = ? ', [$logged_user_id])->get();
    }

    public function save($data) {
        return FavoriteUserCategory::updateOrCreate(['id' => $data['id']],
            [
                'user_id' => $data['user_id'],
                'name' => $data['name'],
            ]);
    }

    public function destroy($id) {
        return FavoriteUser::destroy($id);
    }
}