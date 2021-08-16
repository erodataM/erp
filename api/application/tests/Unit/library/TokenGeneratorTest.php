<?php

declare(strict_types=1);

namespace Tests\Unit;


use TokenGeneratorService;

class TokenGeneratorTest extends AbstractUnitTest
{
    public function testValidateTokenSuccess(): void
    {
        $validate = TokenGeneratorService::validateToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImN0eSI6ImFwcGxpY2F0aW9uXC9qc29uIn0.eyJhdWQiOlsiaHR0cHM6XC9cL2VycC5pb1wvIl0sImV4cCI6MTYyOTIxNjk1MSwianRpIjoiYWJjZDEyMzQ1Njc4OSIsImlhdCI6MTYyOTEzMDU1MSwiaXNzIjoiaHR0cHM6XC9cL3BoYWxjb24uaW9cLyIsIm5iZiI6MTYyOTEzMDQ5MSwic3ViIjoiMSJ9.3gbRGG2DJYgLg3VuAw0JQz5WuHeWqv3fYtp4mX5_0kDFcYaPxJ8WB9Ygxs-lNRRXdMlKIxFuVYEoAgtVmj0zkQ');
        $this->assertEquals(true, $validate);
    }
    public function testValidateTokenError(): void
    {
        $validate = TokenGeneratorService::validateToken('test');
        $this->assertEquals(false, $validate);
    }
    public function testParseToken(): void
    {
        $parse = TokenGeneratorService::parseToken('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiIsImN0eSI6ImFwcGxpY2F0aW9uXC9qc29uIn0.eyJhdWQiOlsiaHR0cHM6XC9cL2VycC5pb1wvIl0sImV4cCI6MTYyOTIxNjk1MSwianRpIjoiYWJjZDEyMzQ1Njc4OSIsImlhdCI6MTYyOTEzMDU1MSwiaXNzIjoiaHR0cHM6XC9cL3BoYWxjb24uaW9cLyIsIm5iZiI6MTYyOTEzMDQ5MSwic3ViIjoiMSJ9.3gbRGG2DJYgLg3VuAw0JQz5WuHeWqv3fYtp4mX5_0kDFcYaPxJ8WB9Ygxs-lNRRXdMlKIxFuVYEoAgtVmj0zkQ');
        $this->assertEquals("Phalcon\Security\JWT\Token\Token", get_class($parse));
    }
}