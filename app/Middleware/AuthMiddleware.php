<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Http\Response;
use Exception;

class AuthMiddleware extends Middleware
{

	public function __invoke(Request $request, RequestHandler $handler): Response
	{
        $response = $handler->handle($request);
        try {
            if(! $this->container->get('auth')->check()) {
                throw new AuthException();
                return $response;
            }
            return $response;
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode($ex->getMessage()));
            return $response->withStatus($ex->getCode());
        }

	}
}