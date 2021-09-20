<?php
namespace Tests\Api;

use App\Controllers\UserController;
use App\Exception\DeleteDatabaseException;
use App\Repository\UserRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

require __DIR__ . '/../../bootstrap/app.php';

class MessageApiTest extends PHPUnit_TestCase
{
    private $app;
    public function setUp()
    {
        global $app;
        $this->app = $app;
    }

    public function messageProvider()
    {
        return [
            ['Baran Hi how are you today?', 2, 1],
            ['Hakan, thank you very much I am great', 1, 2],
        ];
    }

    /**
     * @dataProvider userProvider
     * @param string $text
     * @param string $user_id
     * @param string $message_user_id
     */
    public function testFindAll(string $text, int $user_id, int $message_user_id)
    {
        $message = [
            "text" => $text,
            "user_id" => $user_id,
            "message_user_id" => $message_user_id
        ];
        $this->createMock(UserRepository::class)
            ->method('getAll')->willReturn($message);

        $request = $this->createRequest('GET','/users');
        $response = $this->app->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteUserOfIdThrowsDeleteDatabaseException()
    {
        $this->expectException(DeleteDatabaseException::class);

        $this->createMock(UserRepository::class)
            ->method('destroy')->willReturn(null);
        $this->createMock(UserController::class)
            ->method('permission')->willReturn(true);

        $request = $this->createRequest('DELETE','/users/9999');
        $response = $this->app->handle($request);

        $this->assertEquals(404, $response->getStatusCode());

    }

    protected function createRequest(
        string $method,
        string $path,
        array $headers = ['HTTP_ACCEPT' => 'application/json'],
        array $cookies = [],
        array $serverParams = []
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, $cookies, $serverParams, $stream);
    }

}
