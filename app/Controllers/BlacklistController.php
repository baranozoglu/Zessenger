<?php


namespace App\Controllers;


use App\Models\Blacklist;

class BlacklistController extends Controller
{
    public function getBlacklistByUserId($request, $response)
    {
        $data = $request->getQueryParams();
        $blacklist = Blacklist::whereRaw('user_id = ? ', [$data['user_id']])->get();
        $response->getBody()->write(json_encode($blacklist));
        return $response;
    }

    public function addUserToBlacklist($request, $response)
    {
        $data = $request->getParsedBody();

        $blocked_user = Blacklist::create([
            'user_id' => $data['user_id'],
            'blocked_user_id' => $data['blocked_user_id'],
        ]);

        $response->getBody()->write(json_encode($blocked_user));
        return $response;
    }
}