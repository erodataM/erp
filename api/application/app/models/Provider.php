<?php
use Phalcon\Mvc\Model;

class Provider extends Model
{
    public function initialize() {
        $this->setSource('provider');
    }
}