<?php


namespace App\Controllers;


use App\Models\Message;

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