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
        return Login::whereRaw('user_id = ? order by id desc limit 1', [$id])->get();
    }

    public function save($data) {
        return Login::updateOrCreate([
            'user_id' => $data['user_id']],
            ['connection_id' => $data['connection_id'],
        ]);
    }

    public function setNullConnectionsBeforeSave($data) {
        return Login::where('connection_id',$data['connection_id'])
            ->update(['connection_id' => null]);
    }

    public function destroy($id) {
        return Login::destroy($id)->get();
    }
}