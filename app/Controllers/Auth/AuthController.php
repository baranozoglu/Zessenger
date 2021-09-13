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
		return null;
	}

	public function getSignIn($request, $response)
	{
		return null;
	}

	public function postSignIn($request, $response)
	{
		$data = $request->getParsedBody();
		$auth = $this->auth->attempt(
			$data['email'],
			$data['password']
		);

		if (! $auth) {
			$this->flash->addMessage('error', 'Could not sign you in with those details');
			return $response->withHeader('Location', $this->router->urlFor('auth.signin'));
		}

        Login::create([
            'user_id' => $data['user_id'],
        ]);

		return null;
	}

	public function getSignUp($request, $response)
	{
		return null;
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