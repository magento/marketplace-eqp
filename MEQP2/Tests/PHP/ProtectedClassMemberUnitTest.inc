<?php

class TestClass extends AbstractSniffUnitTest
{

    public $config = [];
    protected $item = '';
    private $data = [];

    public function isEnabled()
    {
        return true;
    }

    protected function isDisabled()
    {
        return false;
    }

    private function sendRequest()
    {
        return true;
    }
}
