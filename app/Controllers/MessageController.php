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
            $messages = Message::whereRaw('sender_id = ? and receiver_id = ? ', [$data['sender_id'], $data['receiver_id']])->get();
            $response->getBody()->write(json_encode($messages));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus(500);
        }
    }

    public function addMessage($request, $response)
    {
        try {
            $data = $request->getParsedBody();

            $receiver_user = User::whereRaw('id = ? ', [$data['receiver_id']])->get();
            if(count($receiver_user) == 0) {
                throw new Exception('Could not find user which you want to send message!');
            }

            $is_user_blockedBy_receiver_user = BlockedUser::whereRaw('user_id = ? and blocked_user_id = ? ', [$data['receiver_id'], $data['sender_id']])->get();
            if(count($is_user_blockedBy_receiver_user) != 0) {
                throw new Exception('You have blocked by user which you want to send message!');
            }

            $user = Message::create([
                'text' => $data['text'],
                'sender_id' => $data['sender_id'],
                'receiver_id' => $data['receiver_id'],
                'status_for_sender' => $data['status_for_sender'],
                'status_for_receiver' => $data['status_for_receiver'],
                'isEdited' => $data['isEdited'],
            ]);

            $response->getBody()->write(json_encode($user));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus(500);
        }

    }
}