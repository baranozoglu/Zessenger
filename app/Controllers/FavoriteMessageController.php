<?php
namespace App\Controllers;

use App\Models\FavoriteMessage;
use App\Models\Message;
use Exception;

class FavoriteMessageController extends Controller
{
    public function getFavoriteMessagesBySenderAndReceiver($request, $response, $args)
    {
        try {
            $favorite_messages = FavoriteMessage::leftJoin('users', 'favorite_messages.sender_id', '=', 'users.id')
                ->whereRaw('user_id = ? order by created_at', [$args['user_id']])
                ->get(['favorite_messages.*','users.username']);
            $response->getBody()->write(json_encode($favorite_messages));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function addFavoriteMessage($request, $response)
    {
        try {
            $data = $request->getParsedBody();
            $this->validate($data);
            $favorite_message = $this->save($data);
            $response->getBody()->write(json_encode($favorite_message));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        try {
            FavoriteMessage::destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while deleting data from database!',500);
        }
    }

    private function validate($data) {
        $message = Message::whereRaw('id = ? ', [$data['message_id']])->get();
        if(count($message) == 0) {
            throw new Exception('Could not find message which you want to add as favorite!',404);
        }
    }

    private function save($data) {
        try {
            return FavoriteMessage::updateOrCreate(['id' => $data['id']],
                [
                    'message_id' => $data['message_id'],
                    'user_id' => $data['user_id'],
                    'sender_id' => $data['sender_id'],
                    'receiver_id' => $data['receiver_id'],
                ]
            );
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while inserting data to database!',500);
        }
    }
}