<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Exception\CouldNotFoundMessageException;
use App\Exception\DeleteDatabaseException;
use App\Exception\FavoriteMessageException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Repository\FavoriteMessageRepository;
use App\Repository\MessageRepository;
use Exception;

global $favoriteMessageRepository;
$favoriteMessageRepository = new FavoriteMessageRepository();

class FavoriteMessageController extends Controller
{
    public function getFavoriteMessagesBySenderAndReceiver($request, $response)
    {
        try {
            $loggedUser = $this->authUser;
            $favorite_messages = $this->query($loggedUser);
            $response->getBody()->write(json_encode($favorite_messages));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function addFavoriteMessage($request, $response)
    {
        try {
            $loggedUser = $this->authUser;
            $data = $request->getParsedBody();
            $data['user_id'] = $loggedUser['id'];
            $this->validate($data);
            $favorite_message = $this->save($data);
            $response->getBody()->write(json_encode($favorite_message));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        global $favoriteMessageRepository;
        try {
            $favoriteMessageRepository->destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }

    private function validate($data) {
        $messageRepository = new MessageRepository();
        $message = $messageRepository->getMessageById($data['message_id']);
        if($message == null) {
            throw new CouldNotFoundMessageException();
        }
    }

    private function save($data) {
        global $favoriteMessageRepository;
        try {
            if($data['user_id'] != $data['owner_id'] && $data['owner_id'] != $data['messaged_user_id'] ) {
                throw new FavoriteMessageException();
            }
            return $favoriteMessageRepository->save($data);
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    private function query($loggedUser) {
        global $favoriteMessageRepository;
        try {
            return $favoriteMessageRepository->getFavoriteMessages($loggedUser['id']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }
}