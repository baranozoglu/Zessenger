<?php
declare(strict_types=1);

namespace Tests\Domain\User;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class UserTest extends PHPUnit_TestCase
{
    public function userProvider()
    {
        return [
            ['Baran', 'Ozoglu', 'baranozoglu'],
            ['Hakan', 'Ozoglu', 'hakanozoglu'],
            ['Akif', 'Ozoglu', 'akifozoglu'],
        ];
    }

    /**
     * @dataProvider userProvider
     * @param string $firstName
     * @param string $lastName
     * @param string $username
     */
    public function testGetters(string $firstName, string $lastName, string $username)
    {
        $user = [
            "first_name" => $firstName,
            "last_name" => $lastName,
            "username" => $username
        ];

        $this->assertEquals($username, $user['username']);
        $this->assertEquals($firstName, $user['first_name']);
        $this->assertEquals($lastName, $user['last_name']);
    }

}
