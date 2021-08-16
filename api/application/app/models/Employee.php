<?php
use Phalcon\Mvc\Model;

class Employee extends Model
{
    public function initialize() {
        $this->setSource('employee');
    }
}