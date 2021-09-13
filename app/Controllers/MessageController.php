<?php


namespace App\Controllers;


use App\Models\BlockedUser;
use App\Models\Message;
use App\Models\User;

class MessageController extends Controller
{
    public function getMessagesBySenderAndReceiver($request, $response)
    {
        $data = $request->getQueryParams();
        $messages = Message::whereRaw('sender_id = ? and receiver_id = ? ', [$data['sender_id'], $data['receiver_id']])->get();
        $response->getBody()->write(json_encode($messages));
        return $response;
    }

    public function addMessage($request, $response)
    {
        $data = $request->getParsedBody();

        $receiver_user = User::whereRaw('sender_id = ? ', [$data['sender_id']])->get();
        if($receiver_user == null ) {
            $this->flash->addMessage('error', 'Could not find user which you want to send message!');
        }

        $is_user_blockedBy_receiver_user = BlockedUser::whereRaw('user_id = ? and blocked_user_id = ? ', [$data['receiver_id'], $data['sender_id']])->get();
        if($is_user_blockedBy_receiver_user != null) {
            $this->flash->addMessage('error', 'You have blocked by user which you want to send message!');
        }

        $user = Message::create([
            'text' => $data['text'],
            'sender_id' => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
            'status_for_sender' => $data['status_for_sender'],
            'status_for_receiver' => $data['status_for_receiver'],
        ]);

        $response->getBody()->write(json_encode($user));
        return $response;
    }
}