<?php
use PHPUnit\Framework\TestCase;
use \App\Models\Message;
use App\Controllers\MessageController;

class MessageTest extends TestCase
{
    protected $message;

    public function setUp()
    {
        $this->message = new Message();
    }

    public function testValidationShouldReturn404()
    {
        $message = new Message();
        $data['receiver_id'] = 9999999;
        MessageController::validate($data);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage('Could not find user which you want to send message!');
    }
}