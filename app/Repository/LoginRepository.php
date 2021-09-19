<?php
namespace App\Repository;
use App\Models\Login;

class LoginRepository {
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
    }

    public function getAll() {
        return Login::all();
    }

    public function getLoginsByUserId($id) {
        return Login::whereRaw('user_id = ?', [$id])->get();
    }

    public function save($data) {
        return Login::create([
            'user_id' => $data,
        ]);
    }

    public function destroy($id) {
        return Login::destroy($id);
    }
}