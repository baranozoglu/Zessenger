<?php
namespace App\Controllers;

use App\Exception\BlockedUserException;
use App\Exception\CouldNotFoundUserException;
use App\Exception\DeleteDatabaseException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Repository\BlockedUserRepository;
use App\Repository\FavoriteUserRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Exception;

global $messageRepository;
$messageRepository = new MessageRepository();

class MessageController extends Controller
{
    public function getMessages($request, $response)
    {
        $response->getBody()->write(json_encode($request));
        try {
            $loggedUser = $request->login;
            var_dump("loggedUser->>".json_encode($loggedUser));
            $data = $request->getQueryParams();
            $data['user_id'] = $loggedUser['user_id'];
            $messages = $this->query($data);
            var_dump("messages->>".json_encode($messages));
            $response->getBody()->write(json_encode($messages));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function addMessage($request, $response)
    {
        $response->getBody()->write(json_encode($request));
        return $response;
        try {
            $loggedUser = $this->authUser;
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

        $loggedUser = $this->authUser;

        $userRepository = new UserRepository();
        $receiver_user = $userRepository->getUserById($data['messaged_user_id']);
        if(count($receiver_user) == 0) {
            throw new CouldNotFoundUserException();
        }

        $favaroiteUserRepository = new FavoriteUserRepository();
        $favaroiteUser = $favaroiteUserRepository->getFavoriteUsers($loggedUser['id'], $data['messaged_user_id']);

        if(count($favaroiteUser) == 0) {
            throw new CouldNotFoundUserException();
        }

        $blockedUserRepository = new BlockedUserRepository();
        $is_user_blockedBy_receiver_user = $blockedUserRepository->getBlockedUsers($loggedUser['id'], $data['messaged_user_id']);
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
            return $messageRepository->getUserMessages($data['user_id'], $data['messaged_user_id']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }

}