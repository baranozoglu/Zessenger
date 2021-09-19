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
use App\Repository\BlockedUserRepository;
use App\Repository\UserRepository;
use App\WebSocket\Chat;
use Exception;

$messageRepository = new MessageRepository();

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
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response;
        }
    }

    public function addMessage($request, $response)
    {
        try {
            $loggedUser = Auth::user();
            $data = $request->getParsedBody();
            $data['user_id'] = $loggedUser['id'];
            $this->validate($data);
            $message = $this->save($data);

            $id_list = FavoriteUserController::findFavoriteUserIdList($data);
            FavoriteUserController::updateLastMessageTime($id_list);

            $response->getBody()->write(json_encode($message));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        global $messageRepository;
        try {
            $messageRepository->destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }

    public function validate($data) {
        $userRepository = new UserRepository();
        $receiver_user = $userRepository->getUserById([$data['receiver_id']]);
        if(count($receiver_user) == 0) {
            throw new CouldNotFoundUserException();
        }

        $loggedUser = Auth::user();
        $blockedUserRepository = new BlockedUserRepository();
        $is_user_blockedBy_receiver_user = $blockedUserRepository->getUser($loggedUser, $data['receiver_id']);
        if(count($is_user_blockedBy_receiver_user) != 0) {
            throw new BlockedUserException();
        }
    }

    public function save($data) {
        global $messageRepository;
        try {
            return $messageRepository->save($data);
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    private function query($data) {
        global $messageRepository;
        try {
            return $messageRepository->getUserMessages($data['user_id'], $data['receiver_id']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }

/*    private function post($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }*/
}