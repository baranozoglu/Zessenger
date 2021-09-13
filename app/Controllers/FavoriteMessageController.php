<?php


namespace App\Controllers;


use App\Models\FavoriteMessage;

class FavoriteMessageController extends Controller
{
    public function getFavoriteMessagesBySenderAndReceiver($request, $response)
    {
        try {
            $data = $request->getQueryParams();
            $favorite_messages = FavoriteMessage::whereRaw('sender_id = ? and receiver_id = ? ', [$data['sender_id'], $data['receiver_id']])->get();
            $response->getBody()->write(json_encode($favorite_messages));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus(500);
        }
    }

    public function addFavoriteMessage($request, $response)
    {
        try {
            $data = $request->getParsedBody();

            $favorite_message = FavoriteMessage::create([
                'message_id' => $data['message_id'],
                'sender_id' => $data['sender_id'],
                'receiver_id' => $data['receiver_id'],
            ]);

            $response->getBody()->write(json_encode($favorite_message));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus(500);
        }
    }
}