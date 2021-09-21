<?php
namespace App\Repository;
use App\Models\User;

class UserRepository {
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
    }

    public function getAll() {
        return User::leftJoin('logins', 'logins.user_id', '=', 'users.id')
            ->get(['users.*', 'logins.connection_id']);
    }

    public function getUserById($id) {
        return User::whereRaw('id = ?', [$id])->get();
    }

    public function destroy($id) {
        return User::destroy($id)->get();
    }
}