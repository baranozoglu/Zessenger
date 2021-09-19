<?php
declare(strict_types=1);

namespace Tests\Domain\User;

use App\Models\Message;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class MessageTest extends PHPUnit_TestCase
{
    public function messageProvider()
    {
        return [
            ['hi', '1', '2'],
            ['hi, how r u', '2', '1'],
            ['i am great', '1', '2'],
            ['hi babe', '3', '2'],
            ['darling', '3', '1'],
        ];
    }

    /**
     * @dataProvider messageProvider
     * @param $text
     * @param $sender_id
     * @param $receiver_id
     */
    public function testGetters(string $text, int $sender_id,  int $receiver_id)
    {
        $message = new Message($text, $sender_id, $receiver_id);

        $this->assertEquals($text, $message->getText());
        $this->assertEquals($sender_id, $message->getSenderId());
        $this->assertEquals($receiver_id, $message->getReceiverId());
    }
    
}
