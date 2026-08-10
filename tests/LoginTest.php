<?php

namespace tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Src\App\Http\Requests\LoginRequest;

#[CoversClass(LoginRequest::class)]
class LoginTest extends TestCase
{
    public function testLoginValidatedErrors(): void
    {
        $login = [
            'email' => 'email-invalido',
            'senha' => '123',
        ];

        $loginRequest = new LoginRequest($login);

        $this->assertTrue($loginRequest->fails());

        $errors = $loginRequest->errors();

        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('senha', $errors);
    }

    public function testLoginValidatedSuccess(): void
    {
        $login = [
            'email' => 'lucas@gmail.com',
            'senha' => '12345678',
        ];

        $loginRequest = new LoginRequest($login);

        $this->assertFalse($loginRequest->fails());
    }
}
