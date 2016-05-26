<?php

class Foo
{
    protected $isEnabled = true;

    public function go()
    {
        if (!$this->isEnabled) {
            throw new Exception('Action disabled.');
        }
    }

    public function exceptionTest()
    {
        if (!$this->isEnabled) {
            throw new \Exception('Action disabled.');
        }
    }

    public function zendExceptionTest()
    {
        if (!$this->isEnabled) {
            throw new Zend_Acl_Role_Registry_Exception('Child Role id does not exist.');
        }
    }
}
