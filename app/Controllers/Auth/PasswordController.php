<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use Respect\Validation\Validator as v;

class PasswordController extends Controller
{
	public function getChangePassword($request, $response)
	{
		return null;
	}

	public function postChangePassword($request, $response)
	{
		$data = $request->getParsedBody();

		$validation = $this->validator->validate($request, [
			'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->auth->user()->password),
			'password' => v::noWhitespace()->notEmpty(),
		]);

		if ($validation->failed()) {
			return $response->withHeader('Location', $this->router->urlFor('auth.password.change'));
		}

		$this->auth->user()->setPassword($data['password']);

		$this->flash->addMessage('info', 'Your password was changed');

		return $response->withHeader('Location', $this->router->urlFor('home'));

	}
}