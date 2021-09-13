<?php


namespace App\Controllers;


use App\Models\BlockedUser;

class BlockedUserController extends Controller
{
    public function getBlockedUserByUserId($request, $response)
    {
        $data = $request->getQueryParams();
        $blacklist = BlockedUser::whereRaw('user_id = ? ', [$data['user_id']])->get();
        $response->getBody()->write(json_encode($blacklist));
        return $response;
    }

    public function addBlockedUser($request, $response)
    {
        $data = $request->getParsedBody();

        $blocked_user = BlockedUser::create([
            'user_id' => $data['user_id'],
            'blocked_user_id' => $data['blocked_user_id'],
        ]);

        $response->getBody()->write(json_encode($blocked_user));
        return $response;
    }
}