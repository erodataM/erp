<?php

declare(strict_types=1);

namespace Tests\Unit;

use AuthController;
use Phalcon\Di;

class AuthControllerTest extends AbstractUnitTest
{
    public function testLoginActionSuccess(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "name": "admin",
            "password": "123456"
        }');
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new AuthController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->loginAction();

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLoginActionBadMethod(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "name": "admin",
            "password": "123456"
        }');
        $_SERVER["REQUEST_METHOD"] = "GET";
        $controller = new AuthController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->loginAction();

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testLoginActionBadEmployee(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "name": "unknown",
            "password": "123456"
        }');
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new AuthController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->loginAction();

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testLoginActionBadPassword(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "name": "admin",
            "password": "admin"
        }');
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new AuthController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->loginAction();

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testLoginActionOptions(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "name": "admin",
            "password": "admin"
        }');
        $_SERVER["REQUEST_METHOD"] = "OPTIONS";
        $controller = new AuthController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->loginAction();

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLogoutActionSuccess(): void
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new AuthController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->logoutAction();

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());

        $this->assertEquals('token=deleted; path=/; Secure; HttpOnly', $response->getHeaders()->get('Set-Cookie'));
    }
}