<?php
namespace App\Controllers;

use App\Auth\Auth;
use App\Exception\CouldNotFoundUserException;
use App\Exception\DeleteDatabaseException;
use App\Exception\GetDatabaseException;
use App\Exception\InsertDatabaseException;
use App\Models\BlockedUser;
use App\Repository\BlockedUserRepository;
use Exception;

class BlockedUserController extends Controller
{
    public function getBlockedUserByUserId($request, $response)
    {
        try {
            $loggedUser = Auth::user();
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
            $loggedUser = Auth::user();
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
        try {
            BlockedUser::destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new DeleteDatabaseException();
        }
    }

    private function validate($data) {
        $user = $this->getBlockedUserByUserId($data['blocked_user_id']);
        if(count($user) == 0) {
            throw new CouldNotFoundUserException();
        }
    }

    private function save($data) {
        try {
            $blockedUserRepository = new BlockedUserRepository();
            return $blockedUserRepository->save($data);
        } catch (Exception $ex) {
            throw new InsertDatabaseException();
        }
    }

    private function query($data) {
        try {
            $blockedUserRepository = new BlockedUserRepository();
            return $blockedUserRepository->getUserById($data['id']);
        } catch (Exception $ex) {
            throw new GetDatabaseException();
        }
    }
}