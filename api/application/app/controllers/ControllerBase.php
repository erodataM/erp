<?php
declare(strict_types=1);
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Response;

class ControllerBase extends Controller
{
    public $userId;

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $currentController = $this->dispatcher->getControllerName();
        if ($currentController === 'api') {
            if (!$this->authorize()) {
                $this->response->setStatusCode(401);
                $this->response->send();
                die();
            }
        }
        return true;
    }
    private function authorize(): bool
    {
        $cookie = $this->request->getHeader('Cookie');
        if ($cookie && preg_match('/token=(.*)/', $cookie, $matches)) {
            $tokenReceived = $matches[1];

            if (!TokenGeneratorService::validateToken($tokenReceived)) {
                return false;
            } else {
                $parsedToken = TokenGeneratorService::parseToken($tokenReceived);
                $employeeId = $parsedToken->getClaims()->get('sub');
                $employee = Employee::findFirst(
                    [
                        'conditions' => 'id = :id:',
                        'bind' => [
                            'id' => $employeeId,
                        ],
                    ]
                );
                $this->userId = $employeeId;
                if ($employee->role === 'user' && ($this->request->isPut() || $this->request->isPost() || $this->request->isDelete())) {
                    return false;
                } else {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     *
     */
    static public function initResponse()
    {
        return (new Response())
            ->setHeader('Content-Type', 'application/json')
            ->setHeader('Access-Control-Allow-Origin', '*')
            ->setHeader('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, DELETE, OPTIONS')
            ->setHeader('Access-Control-Allow-Headers', 'Origin, Content-Type, X-Auth-Token');
    }

    /**
     * @return \Phalcon\Http\ResponseInterface
     */
    static public function errorResponse() {
        return self::initResponse()
            ->setStatusCode(400)
            ->setContent(json_encode(['status' => 'error']));
    }
}
