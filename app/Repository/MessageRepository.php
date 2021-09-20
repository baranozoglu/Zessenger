<?php
namespace App\Repository;
use App\Models\Message;

class MessageRepository {
    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
    }

    public function getAll() {
        return Message::all()->get();
    }

    public function getMessageById($id) {
        return Message::whereRaw('id = ?', [$id])->get();
    }

    public function getUserMessages($logged_user_id, $messaged_user_id) {
        return Message::leftJoin('messages as m', 'messages.parent_message_id', '=', 'm.id')
            ->leftJoin('users', 'messages.user_id', '=', 'users.id')
            ->leftJoin('files', 'messages.file_id', '=', 'files.id')
            ->whereRaw('(messages.user_id = ? and messages.messaged_user_id = ?) or (messages.user_id = ? and messages.messaged_user_id = ?) order by messages.created_at', [$logged_user_id, $messaged_user_id, $messaged_user_id, $logged_user_id])
            ->get(['messages.*', 'm.text as parent_message_text', 'users.username as sender_name', 'files.id']);
    }

    public function save($data) {
        return Message::updateOrCreate(['id' => $data['id']],
            [
                'text' => $data['text'],
                'user_id' => $data['user_id'],
                'messaged_user_id' => $data['messaged_user_id'],
                'status_for_sender' => $data['status_for_sender'],
                'status_for_receiver' => $data['status_for_receiver'],
                'isEdited' => $data['isEdited'],
                'parent_message_id' => $data['parent_message_id'],
                'file_id' => $data['file_id'],
            ]
        );
    }

    public function destroy($id) {
        return Message::destroy($id)->get();
    }
}