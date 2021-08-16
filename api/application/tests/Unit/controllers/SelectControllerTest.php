<?php

declare(strict_types=1);

namespace Tests\Unit;

use SelectController;

class SelectControllerTest extends AbstractUnitTest
{
    public function testListActionSuccess(): void
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $controller = new SelectController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->listAction('product');

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }
}