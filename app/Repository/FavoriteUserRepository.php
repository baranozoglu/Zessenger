<?php
namespace App\Repository;
use App\Models\FavoriteUser;

class FavoriteUserRepository {
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
    }

    public function getAll() {
        return FavoriteUser::all();
    }

    public function getFavoriteUserById($id) {
        return FavoriteUser::whereRaw('id = ?', [$id])->get();
    }

    public function getFavoriteUsers($user_id, $messaged_user_id) {
        return FavoriteUser::join('favorite_user_categories', 'favorite_user_categories.id', '=', 'favorite_users.favorite_user_category_id')
            ->whereRaw('favorite_users.favorite_user_id = ? and favorite_users.user_id = ?', [$messaged_user_id, $user_id])
            ->get(['favorite_users.*', 'favorite_user_categories.name']);
    }

    public function save($data) {
        return FavoriteUser::updateOrCreate(['id' => $data['id']],
            [
                'user_id' => $data['user_id'],
                'favorite_user_category_id' => $data['favorite_user_category_id'],
                'favorite_user_id' => $data['favorite_user_id'],
                'nickname' => $data['nickname'],
                'last_message_time' => $data['last_message_time'],
            ]);
    }

    public function updateLastMessageTime($id_list) {
        return FavoriteUser::whereRaw('id in (?)', [$id_list])
            ->update(['last_message_time' => date('Y-m-d H:i:s')]);
    }

    public function findFavoriteUserIdList($user_id) {
        return FavoriteUser::whereRaw('favorite_user_id = ? or user_id = ?', [$user_id, $user_id])
            ->get('id');
    }
    public function getFavoriteUser($user_id) {
        return FavoriteUser::whereRaw('favorite_user_id = ? or user_id = ?', [$user_id, $user_id])
            ->get('id');
    }

    public function destroy($id) {
        return FavoriteUser::destroy($id)->get();
    }
}