<?php
namespace Tests\Infrastructure\Persistence;

use App\Controllers\UserController;
use App\Exception\DeleteDatabaseException;
use App\Repository\UserRepository;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

require __DIR__ . '/../../../bootstrap/app.php';

class UserApiTest extends PHPUnit_TestCase
{
    private $app;
    public function setUp()
    {
        global $app;
        $this->app = $app;
    }

    public function userProvider()
    {
        return [
            ['Baran', 'Ozoglu', 'baranozoglu', 'admin'],
            ['Hakan', 'Ozoglu', 'hakanozoglu', 'admin'],
            ['Akif', 'Ozoglu', 'akifozoglu', 'admin'],
        ];
    }

    /**
     * @dataProvider userProvider
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     * @param string $password
     */
    public function testFindAll(string $firstName, string $lastName, string $username, string $password)
    {
        $user = [
            "first_name" => $firstName,
            "last_name" => $lastName,
            "username" => $username
        ];
        $this->createMock(UserRepository::class)
            ->method('getAll')->willReturn($user);

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
