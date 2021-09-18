<?php

use App\Exception\CouldNotFoundUserException;
use PHPUnit\Framework\TestCase;
use App\Models\Message;
use App\Controllers\MessageController;

class MessageTest extends TestCase
{
    private $message;

    public function setUp()
    {
        $this->message = new Message();
    }

    public function testValidationShouldReturn404()
    {
        $this->expectException(CouldNotFoundUserException::class);
        $data['receiver_id'] = 9999999;

        $controller = new MessageController();
        $response = $controller->validate($data);
        var_dump(json_encode($response));
    }

    public function testCanSignIn() {
        $user = [
            'username' => 'akifozoglu',
            'password' => 'admin'
        ];
        $response = $this->post('localhost:8080/auth/signin', $user);
        $this->assertEquals(false, $response);
        //$this->assertEquals(1, $response->status);
    }

    public function testSaveMethod() {
        $this->message->setText("testing message");
        $this->message->setSenderId(1);
        $this->message->setReceiverId(2);
        $user = [
            'username' => 'akifozoglu',
            'password' => 'admin'
        ];
        $this->json('POST', '/auth/signin', $user);
        $data = [
            'text' => 'testing message',
            'messaged_user_id' => '2'
        ];

        $response = MessageController::save($this->message);
        assertEquals(1, count($response));

    }

    private function post($url, $data)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

}