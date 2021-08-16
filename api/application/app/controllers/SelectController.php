<?php
declare(strict_types=1);

class SelectController extends ControllerBase
{
    /**
     * @param string $entity
     * @param int $page
     * @param int $perPage
     * @return \Phalcon\Http\ResponseInterface
     */
    public function listAction(string $entity)
    {
        $response = self::initResponse();
        $entities = call_user_func(ucfirst($entity) . '::find', ['columns' => 'id, name']);
        $result = [
            'content' => $entities->toArray(),
        ];
        $response->setStatusCode(200);
        $response->setContent(json_encode($result));
        return $response;
    }
}
