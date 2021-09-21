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
        return User::all();
    }

    public function getUserById($id) {
        return User::whereRaw('id = ?', [$id])->get();
    }

    public function destroy($id) {
        return User::destroy($id)->get();
    }
}