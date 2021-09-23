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
     * @param $user_id
     * @param $messaged_user_id
     */
    public function testGetters(string $text, int $user_id,  int $messaged_user_id)
    {
        $message = [
            "text" => $text,
            "user_id" => $user_id,
            "messaged_user_id" => $messaged_user_id
        ];

        $this->assertEquals($text, $message['text']);
        $this->assertEquals($user_id, $message['user_id']);
        $this->assertEquals($messaged_user_id, $message['messaged_user_id']);
    }
    
}
