<?php
namespace App\Repository;
use App\Models\BlockedUser;

class BlockedUserRepository {
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
    }

    public function getAll() {
        return BlockedUser::all();
    }

    public function getBlockedUserById($id) {
        return BlockedUser::whereRaw('id = ?', [$id]);
    }

    public function getBlockedUsers($logged_user_id, $messaged_user_id) {
        return BlockedUser::whereRaw('user_id = ? and blocked_user_id = ? ', [$logged_user_id, $messaged_user_id])->get();
    }

    public function save($data) {
        return BlockedUser::updateOrCreate(['id' => $data['id']],
            [
                'user_id' => $data['user_id'],
                'blocked_user_id' => $data['blocked_user_id'],
            ]
        );
    }

    public function destroy($id) {
        return BlockedUser::destroy($id);
    }
}