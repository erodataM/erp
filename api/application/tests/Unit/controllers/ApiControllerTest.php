<?php

declare(strict_types=1);

namespace Tests\Unit;

use ApiController;
use Phalcon\Di;

class ApiControllerTest extends AbstractUnitTest
{
    public function testListActionSuccess(): void
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->listAction('product');

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testListActionSuccessPaginate(): void
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->listAction('product', 1, 10);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testListActionSuccessTransaction(): void
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->listAction('transaction');

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testListActionBadEntity(): void
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->listAction('noentity');

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testListActionBadMethod(): void
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->listAction('product');

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testGetActionSuccess(): void
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->getAction('product', 1);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetActionBadEntity(): void
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->getAction('noentity', 1);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testGetActionBadMethod(): void
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->getAction('product', 1);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testGetActionNotFound(): void
    {
        $_SERVER["REQUEST_METHOD"] = "GET";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->getAction('product', 40);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testEditActionSuccess(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "name": "test ajout",
            "balance": 230000,
            "country": "Suisse"
        }');
        $_SERVER["REQUEST_METHOD"] = "PUT";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->editAction('company', 1);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEditActionBadMethod(): void
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->editAction('product', 1);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testEditActionBadEntity(): void
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->editAction('noentity', 1);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testEditActionNotFound(): void
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->editAction('product', 100);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testEditActionOptions(): void
    {
        $_SERVER["REQUEST_METHOD"] = "OPTIONS";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->editAction('product', 100);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEditActionTransaction(): void
    {
        $_SERVER["REQUEST_METHOD"] = "PUT";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->editAction('transaction', 1);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testEditActionBadBody(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "id" : "1",
            "company_id": "1",
            "quantity": "50000"
        }');
        $_SERVER["REQUEST_METHOD"] = "PUT";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->editAction('transaction', 1);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAddActionSuccess(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "id" : "21",
            "name": "test ajout",
            "balance": 230000,
            "country": "Suisse"
        }');
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->addAction('company');

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddActionBadEntity(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "name": "test ajout",
            "balance": 230000,
            "country": "Suisse"
        }');
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->addAction('noentity');

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAddActionBadMethod(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "name": "test ajout",
            "balance": 230000,
            "country": "Suisse"
        }');
        $_SERVER["REQUEST_METHOD"] = "DELETE";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->addAction('company');

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAddActionTransaction(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "id": "2",
            "company_id": "1",
            "client_id": "1",
            "product_id": "1",
            "quantity": 1
        }');
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->addAction('transaction');
        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testAddActionBadBody(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "id": "2",
            "company_id": "1",
            "client_id": "1",
            "product_id": "1",
            "quantity": 50000
        }');
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->addAction('transaction');
        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testAddActionOptions(): void
    {
        Di::getDefault()->get('request')->setRawBody('{
            "id": "2",
            "company_id": "1",
            "client_id": "1",
            "product_id": "1",
            "quantity": 50000
        }');
        $_SERVER["REQUEST_METHOD"] = "OPTIONS";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->addAction('transaction');
        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteActionSuccess(): void
    {
        $_SERVER["REQUEST_METHOD"] = "DELETE";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->deleteAction('company', 21);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteActionBadEntity(): void
    {
        $_SERVER["REQUEST_METHOD"] = "DELETE";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->deleteAction('noentity', 21);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testDeleteActionBadMethod(): void
    {
        $_SERVER["REQUEST_METHOD"] = "POST";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->deleteAction('product', 1);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testDeleteActionTransaction(): void
    {
        $_SERVER["REQUEST_METHOD"] = "DELETE";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->deleteAction('transaction', 2);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(204, $response->getStatusCode());
    }

    public function testDeleteActionOptions(): void
    {
        $_SERVER["REQUEST_METHOD"] = "OPTIONS";
        $controller = new ApiController();
        /** @var Phalcon\Http\Response $response */
        $response = $controller->deleteAction('transaction', 2);

        $this->assertEquals('Phalcon\Http\Response', get_class($response));
        $this->assertEquals(200, $response->getStatusCode());
    }
}