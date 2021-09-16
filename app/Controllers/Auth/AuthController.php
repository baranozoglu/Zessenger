<?php
namespace App\Controllers\Auth;

use App\Models\Login;
use App\Models\User;
use App\Controllers\Controller;

class AuthController extends Controller
{

	public function getSignOut($request, $response)
	{
		$this->auth->logout();
		return $response;
	}

	public function getSignIn($request, $response)
	{
		return $response;
	}

	public function postSignIn($request, $response)
	{
		$data = $request->getParsedBody();
		$auth = $this->auth->attempt(
			$data['username'],
			$data['password']
		);
        $user = $this->auth->user();
		if (! $auth) {
            $response->getBody()->write(json_encode("faaiiilll"));
            return $response;
		}

        Login::create([
            'user_id' => $user['id'],
        ]);

		return $response;
	}

	public function getSignUp($request, $response)
	{
		return $response;
	}

	public function postSignUp($request, $response)
	{
		$data = $request->getParsedBody();

		$user = User::create([
			'email' => $data['email'],
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'phone' => $data['phone'],
            'photo_url' => $data['photo_url'],
            'username' => $data['username'],
		]);

        $response->getBody()->write(json_encode($user));
        return $response;
	}
}