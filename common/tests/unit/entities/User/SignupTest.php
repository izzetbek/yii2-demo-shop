<?php

namespace common\tests\unit\entities\User;

use Codeception\Test\Unit;
use shop\entities\User\User;

class SignupTest extends Unit
{
    public function testSuccess()
    {
        $user = User::signup(
            $username = 'username',
            $email = 'example@example.com',
            $password = 'password123'
        );

        $this->assertEquals($username, $user->username);
        $this->assertEquals($email, $user->email);
        $this->assertNotEmpty($user->password_hash);
        $this->assertNotEquals($password, $user->password_hash);
        $this->assertNotEmpty($user->auth_key);
        $this->assertNotEmpty($user->created_at);
        $this->assertTrue($user->isActive());
    }
}
