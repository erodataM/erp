<?php
use Phalcon\Mvc\Model;

class Client extends Model
{
    public function initialize() {
        $this->setSource('client');
    }
}