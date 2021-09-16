<?php
use PHPUnit\Framework\TestCase;
use \App\Models\Message;
use App\Controllers\MessageController;

class MessageTest extends TestCase
{
    private $message;
    private $messageContoller;

    public function setUp()
    {
        $this->message = new Message();
    }

    public function testValidationShouldReturn404()
    {
        $data['receiver_id'] = 9999999;
        when(MessageController::validate(any()));

        try {
            MessageController::validate($data);
            self::fail();
        } catch (Exception $e) {
            assertEquals(404,$e->getCode());
            assertEquals('Could not find user which you want to send message!', $e->getMessage());
        }
    }


}