<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Models\FavoriteMessage;
use App\Models\Message;
use Exception;

class FavoriteMessageController extends Controller
{
    public function getFavoriteMessagesBySenderAndReceiver($request, $response)
    {
        try {
            $loggedUser = Auth::user();
            $favorite_messages = $this->query($loggedUser);
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
            $loggedUser = Auth::user();
            $data = $request->getParsedBody();
            $data['user_id'] = $loggedUser['id'];
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
            throw new DeleteDatabaseException();
        }
    }

    private function validate($data) {
        $message = Message::whereRaw('id = ? ', [$data['message_id']])->get();
        if(count($message) == 0) {
            throw new CouldNotFoundMessageException();
        }
    }

    private function save($data) {
        try {
            if($data['user_id'] != $data['sender_id'] && $data['user_id'] != $data['receiver_id'] ) {
                throw new FavoriteMessageException();
            }
            return FavoriteMessage::updateOrCreate(['id' => $data['id']],
                [
                    'message_id' => $data['message_id'],
                    'user_id' => $data['user_id'],
                    'sender_id' => $data['sender_id'],
                    'receiver_id' => $data['receiver_id'],
                ]
            );
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    private function query($loggedUser) {
        try {
            return FavoriteMessage::leftJoin('users', 'favorite_messages.sender_id', '=', 'users.id')
                ->whereRaw('user_id = ? order by created_at', [$loggedUser['id']])
                ->get(['favorite_messages.*','users.username as sender_name']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }
}