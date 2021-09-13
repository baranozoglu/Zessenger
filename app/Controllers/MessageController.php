<?php
namespace App\Controllers;

use App\Models\BlockedUser;
use App\Models\Message;
use App\Models\User;
use Exception;

class MessageController extends Controller
{
    public function getMessagesBySenderAndReceiver($request, $response)
    {
        try {
            $data = $request->getQueryParams();

            $messages = Message::leftJoin('messages as m', 'messages.parent_message_id', '=', 'm.id')
                ->leftJoin('users', 'messages.sender_id', '=', 'users.id')
                ->whereRaw('(messages.sender_id = ? and messages.receiver_id = ?) or (messages.sender_id = ? and messages.receiver_id = ?) order by messages.created_at', [$data['sender_id'], $data['receiver_id'], $data['receiver_id'], $data['sender_id']])
                ->get(['messages.*', 'm.text as parent_message_text', 'users.id as receiver_name']);
                //->get(['messages.*', 'm.text as parent_message_text']);

            $response->getBody()->write(json_encode($messages));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response;
        }
    }

    public function addMessage($request, $response)
    {
        try {
            $data = $request->getParsedBody();
            $this->validate($data);
            $user = $this->save($data);
            $response->getBody()->write(json_encode($user));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    private function validate($data) {
        $receiver_user = User::whereRaw('id = ? ', [$data['receiver_id']])->get();
        if(count($receiver_user) == 0) {
            throw new Exception('Could not find user which you want to send message!',404);
        }

        $is_user_blockedBy_receiver_user = BlockedUser::whereRaw('user_id = ? and blocked_user_id = ? ', [$data['receiver_id'], $data['sender_id']])->get();
        if(count($is_user_blockedBy_receiver_user) != 0) {
            throw new Exception('You have blocked by user which you want to send message!',400);
        }
    }

    private function save($data) {
        return Message::updateOrCreate(['id' => $data['id'],],
            [
            'text' => $data['text'],
            'sender_id' => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
            'status_for_sender' => $data['status_for_sender'],
            'status_for_receiver' => $data['status_for_receiver'],
            'isEdited' => $data['isEdited'],
            'parent_message_id' => $data['parent_message_id'],
            ]
        );
    }

}