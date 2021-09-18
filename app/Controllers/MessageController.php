<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Controllers\Auth\AuthController;
use App\Exception\BlockedUserException;
use App\Exception\CouldNotFoundUserException;
use App\Exception\DeleteDatabaseException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Models\BlockedUser;
use App\Models\Message;
use App\Models\User;
use App\WebSocket\Chat;
use Exception;

class MessageController extends Controller
{
    public function getMessagesBySenderAndReceiver($request, $response)
    {
        try {
            $loggedUser = Auth::user();
            $data = $request->getQueryParams();
            $data['user_id'] = $loggedUser['id'];
            $messages = $this->query($data);
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
            $message = $this->save($data);

            $id_list = FavoriteUserController::findFavoriteUserIdList($data);
            FavoriteUserController::updateLastMessageTime($id_list);

            $response->getBody()->write(json_encode($message));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        try {
            Message::destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }

    public function validate($data) {
        $receiver_user = User::whereRaw('id = ? ', [$data['receiver_id']])->get();
        if(count($receiver_user) == 0) {
            throw new CouldNotFoundUserException();
        }

        $is_user_blockedBy_receiver_user = BlockedUser::whereRaw('user_id = ? and blocked_user_id = ? ', [$data['receiver_id'], $data['sender_id']])->get();
        if(count($is_user_blockedBy_receiver_user) != 0) {
            throw new BlockedUserException();
        }
    }

    public function save($data) {
        try {
            return Message::updateOrCreate(['id' => $data['id']],
                [
                    'text' => $data['text'],
                    'sender_id' => $data['sender_id'],
                    'receiver_id' => $data['receiver_id'],
                    'status_for_sender' => $data['status_for_sender'],
                    'status_for_receiver' => $data['status_for_receiver'],
                    'isEdited' => $data['isEdited'],
                    'parent_message_id' => $data['parent_message_id'],
                    'file_id' => $data['file_id'],
                ]
            );
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    private function query($data) {
        try {
            return Message::leftJoin('messages as m', 'messages.parent_message_id', '=', 'm.id')
                ->leftJoin('users', 'messages.sender_id', '=', 'users.id')
                ->leftJoin('files', 'messages.file_id', '=', 'files.id')
                ->whereRaw('(messages.sender_id = ? and messages.receiver_id = ?) or (messages.sender_id = ? and messages.receiver_id = ?) order by messages.created_at', [$data['user_id'], $data['messaged_user_id'], $data['messaged_user_id'], $data['user_id']])
                ->get(['messages.*', 'm.text as parent_message_text', 'users.username as sender_name', 'files.id']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }

    private function post($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}