<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Exception\CouldNotFoundUserException;
use App\Exception\DeleteDatabaseException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Repository\BlockedUserRepository;
use Exception;

global $blockedUserRepository;
$blockedUserRepository = new BlockedUserRepository();

class BlockedUserController extends Controller
{
    public function getBlockedUserByUserId($request, $response)
    {
        try {
            $loggedUser = $this->authUser;
            $blacklist = $this->query($loggedUser);
            $response->getBody()->write(json_encode($blacklist));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function addBlockedUser($request, $response)
    {
        try {
            $loggedUser = $this->authUser;
            $data = $request->getParsedBody();
            $data['user_id'] = $loggedUser['id'];
            $this->validate($data);
            $blocked_user = $this->save($data);
            $response->getBody()->write(json_encode($blocked_user));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        global $blockedUserRepository;
        try {
            $blockedUserRepository->destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }

    private function validate($data) {
        $user = $this->getBlockedUserByUserId($data['blocked_user_id']);
        if($user == null) {
            throw new CouldNotFoundUserException();
        }
    }

    private function save($data) {
        global $blockedUserRepository;
        try {
            return $blockedUserRepository->save($data);
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    private function query($data) {
        global $blockedUserRepository;
        try {
            return $blockedUserRepository->getBlockedUserById($data['id']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }
}