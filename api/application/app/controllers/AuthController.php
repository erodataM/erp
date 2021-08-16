<?php
declare(strict_types=1);
use Phalcon\Http\Response;
use Phalcon\Http\Request;
use Phalcon\Http\Cookie;
use Phalcon\Http\Response\Cookies;
class AuthController extends ControllerBase
{
    /**
     * Login
     */
    public function loginAction() {
        $response = parent::initResponse();

        if (true === $this->request->isPost()) {
            $input = $this->request->getJsonRawBody();

            $employee = Employee::findFirst(
                [
                    'conditions' => 'name = :name:',
                    'bind' => [
                        'name' => $input->name,
                    ],
                ]
            );

            if ($employee->password) {
                $check = $this
                    ->security
                    ->checkHash($input->password, $employee->password);

                if (true === $check) {
                    $jsonObject = new stdClass();
                    $jsonObject->id = $employee->id;
                    $jsonObject->role = $employee->role;
                    $response->setContent(json_encode($jsonObject));
                    $response->setStatusCode(200);
                    $response->setHeader("Set-Cookie","token=".TokenGeneratorService::getToken($employee->id)."; path=/; Secure; HttpOnly");
                } else {
                    $response->setStatusCode(401);
                    $response->setContent(json_encode(['status' => 'error']));
                }
            } else {
                $response->setStatusCode(401);
                $response->setContent(json_encode(['status' => 'error']));
            }
        } else {
            if (true === $this->request->isOptions()) {
                $response->setStatusCode(200);
            } else {
                $response = self::errorResponse();
            }
        }

        return $response;
    }

    public function logoutAction() {
        $response = parent::initResponse();
        $response->setHeader("Set-Cookie","token=deleted; path=/; Secure; HttpOnly");
        $response->setStatusCode(200);
        return $response;
    }
}

