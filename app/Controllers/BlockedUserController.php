<?php
namespace App\Controllers;

use App\Models\BlockedUser;
use Exception;

class BlockedUserController extends Controller
{
    public function getBlockedUserByUserId($request, $response, $args)
    {
        try {
            $blacklist = BlockedUser::whereRaw('user_id = ? ', [$args['user_id']])->get();
            $response->getBody()->write(json_encode($blacklist));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function addBlockedUser($request, $response)
    {
        try {
            $data = $request->getParsedBody();
            $this->validate($data);
            $blocked_user = $this->save($data);
            $response->getBody()->write(json_encode($blocked_user));
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode('errorMessage: '.$ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }
    }

    public function delete($request, $response, $args) {
        try {
            BlockedUser::destroy($args['id']);
            return $response;
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while deleting data from database!',500);
        }
    }

    private function validate($data) {
        $user = $this->getBlockedUserByUserId($data['blocked_user_id']);
        if(count($user) == 0) {
            throw new Exception('Could not find user which you want to block!',404);
        }
    }

    private function save($data) {
        try {
            return BlockedUser::updateOrCreate(['id' => $data['id']],
                [
                    'user_id' => $data['user_id'],
                    'blocked_user_id' => $data['blocked_user_id'],
                ]
            );
        } catch (Exception $ex) {
            throw new Exception('Something went wrong while inserting data to database!',500);
        }
    }
}