<?php
declare(strict_types=1);
use Phalcon\Http\Response;
use Phalcon\Http\Request;


class ApiController extends ControllerBase
{
    /**
     * @param string $entity
     * @param int $page
     * @param int $perPage
     * @return \Phalcon\Http\ResponseInterface
     */
    public function listAction(string $entity, int $page = 1, int $perPage = 25)
    {
        $response = self::initResponse();
        $request = new Request();

        if (true === $request->isGet() && class_exists(ucfirst($entity))) {
            $count = call_user_func(ucfirst($entity) . '::count');
            if ($entity !== 'transaction') {
                $entities = call_user_func(ucfirst($entity) . '::find', ['limit' => $perPage, 'offset' => ($page - 1) * $perPage]);
                $result = [
                    'content' => $entities->toArray(),
                    'count' => $count
                ];
            } else {
                $phql = "SELECT t.*, c.*, p.*, cl.*, pr.*, e.* FROM Transaction t
                         LEFT JOIN Company c ON c.id = t.company_id
                         LEFT JOIN Provider p ON p.id = t.provider_id
                         LEFT JOIN Client cl ON cl.id = t.client_id
                         LEFT JOIN Product pr ON pr.id = t.product_id
                         LEFT JOIN Employee e ON e.id = t.employee_id
                         LIMIT :limit: OFFSET :offset:";
                $queryResult = $this->modelsManager->executeQuery(
                    $phql,
                    [
                        'limit' => $perPage,
                        'offset' => ($page - 1) * $perPage
                    ],
                    [
                        'limit' => Phalcon\Db\Column::BIND_PARAM_INT,
                        'offset' => Phalcon\Db\Column::BIND_PARAM_INT,
                    ]
                );
                $array = [];
                foreach ($queryResult as $res) {
                    $row = new stdClass();
                    $row->id = $res->t->id;
                    $row->company_name = $res->c->name;
                    $row->provider_name = $res->p->name;
                    $row->client_name = $res->cl->name;
                    $row->product_name = $res->pr->name;
                    $row->employee_name = $res->e->name;
                    $row->quantity = $res->t->quantity;
                    $array[] = $row;
                }
                $result = [
                    'content' => $array,
                    'count' => $count
                ];
            }
            $response->setStatusCode(200);
            $response->setContent(json_encode($result));
        } else {
            $response = self::errorResponse();
        }

        return $response;
    }

    /**
     * @param string $entity
     * @param int $id
     * @return \Phalcon\Http\ResponseInterface
     */
    public function getAction(string $entity, int $id)
    {
        $response = self::initResponse();
        if (true === $this->request->isGet() && class_exists(ucfirst($entity))) {
            $entities = call_user_func(ucfirst($entity).'::find', [$id]);
            if ($entities->getFirst() !== null) {
                $response->setStatusCode(200);
                $response->setContent(json_encode($entities->getFirst()->toArray()));
            } else {
                $response->setStatusCode(404);
                $response->setContent(json_encode(['status' => 'error']));
            }
        } else {
            $response = self::errorResponse();
        }
        return $response;
    }

    /**
     * @param string $entity
     * @param int $id
     * @return \Phalcon\Http\ResponseInterface
     */
    public function editAction(string $entity, int $id)
    {
        $response = self::initResponse();
        if (class_exists(ucfirst($entity))) {
            if (true === $this->request->isPut()) {
                $entities = call_user_func(ucfirst($entity) . '::find', [$id]);
                if ($entities->getFirst()) {
                    $entities->getFirst()->assign((array) $this->request->getJsonRawBody());
                    if ($entity === 'transaction') {
                        $entities->employee_id = $this->userId;
                    }
                    if ($entities->getFirst()->save()) {
                        $response->setStatusCode(200);
                        $response->setContent(json_encode($entities->getFirst()->toArray()));
                    } else {
                        $response->setContent(json_encode(['status' => $entities->getFirst()->getMessages()]));
                        $response->setStatusCode(400);
                    }
                } else {
                    $response = self::errorResponse();
                }
            } else {
                if (true === $this->request->isOptions()) {
                    $response->setStatusCode(200);
                } else {
                    $response = self::errorResponse();
                }
            }
        } else {
            $response = self::errorResponse();
        }
        return $response;
    }

    /**
     * @param string $entity
     * @return \Phalcon\Http\ResponseInterface
     */
    public function addAction(string $entity)
    {
        $response = self::initResponse();
        if (class_exists(ucfirst($entity))) {
            if (true === $this->request->isPost()) {
                $className = ucfirst($entity);
                $entities = new $className();
                $entities->assign((array) $this->request->getJsonRawBody());

                if ($entity === 'transaction') {
                    $entities->employee_id = $this->userId;
                }
                if ($entities->save()) {
                    $response->setStatusCode(200);
                    $response->setContent(json_encode($entities->toArray()));
                } else {
                    $response->setStatusCode(400);
                    $response->setContent(json_encode(['status' => $entities->getMessages()]));
                }
            } else {
                if (true === $this->request->isOptions()) {
                    $response->setStatusCode(200);
                } else {
                    $response = self::errorResponse();
                }
            }
        } else {
            $response = self::errorResponse();
        }

        return $response;
    }

    /**
     * @param string $entity
     * @param int $id
     * @return \Phalcon\Http\ResponseInterface
     */
    public function deleteAction(string $entity, int $id)
    {
        $response = self::initResponse();
        if (class_exists(ucfirst($entity))) {
            if (true === $this->request->isDelete()) {
                $entities = call_user_func(ucfirst($entity) . '::find', [$id]);
                if ($entities->getFirst()) {
                    $entities->delete();
                    $response->setStatusCode(204);
                    $response->setContent("");
                } else {
                    $response = self::errorResponse();
                }
            } else {
                if (true === $this->request->isOptions()) {
                    $response->setStatusCode(200);
                } else {
                    $response = self::errorResponse();
                }
            }
        } else {
            $response = self::errorResponse();
        }
        $response->sendHeaders();
        return $response;
    }
}
