<?php
namespace App\Repository;
use App\Models\FavoriteMessage;

class FavoriteMessageRepository {
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
    }

    public function getAll() {
        return FavoriteMessage::all()->get();
    }

    public function getFavoriteMessageById($id) {
        return FavoriteMessage::whereRaw('id = ?', [$id])->get();
    }

    public function getFavoriteMessages($logged_user_id) {
        return FavoriteMessage::leftJoin('users', 'favorite_messages.user_id', '=', 'users.id')
            ->whereRaw('user_id = ? order by created_at', [$logged_user_id])
            ->get(['favorite_messages.*','users.username as sender_name'])->get();
    }

    public function save($data) {
        return FavoriteMessage::updateOrCreate(['id' => $data['id']],
            [
                'message_id' => $data['message_id'],
                'user_id' => $data['user_id'],
                'owner_id' => $data['owner_id'],
                'messaged_user_id' => $data['messaged_user_id'],
            ]
        );
    }

    public function destroy($id) {
        return FavoriteMessage::destroy($id)->get();
    }
}