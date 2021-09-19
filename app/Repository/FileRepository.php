<?php
namespace App\Repository;
use App\Models\File;

class FileRepository {
    /**
     * FileRepository constructor.
     */
    public function __construct()
    {
    }

    public function getAll() {
        return File::all();
    }

    public function getLoginById($id) {
        return File::whereRaw('id = ?', [$id]);
    }

    public function getLoginsByUserId($id, $logged_user_id, $messaged_user_id) {
        return File::whereRaw('id = ? and ((sender_id = ? and receiver_id = ? ) or (sender_id = ? and receiver_id = ? ))', [$id, $logged_user_id, $messaged_user_id, $messaged_user_id, $logged_user_id])
            ->get();
    }

    public function save($data, $filename) {
        return File::updateOrCreate(['id' => $data['id']],
            [
                'filename' => $filename,
                'user_id' => $data['user_id'],
                'messaged_user_id' => $data['messaged_user_id'],
            ]
        );
    }

    public function destroy($id) {
        return File::destroy($id);
    }
}