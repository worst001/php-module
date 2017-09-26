<?php
class Registry
{
    private static $instance;
    private $request;
    private $values = [];

    private function __construct()
    {
    }
    
    public static function instance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function get($key)
    {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }
        return null;
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }
}

class Request {}

$reg = Registry::instance();
$reg->setRequest(new Request());

print_r($reg->getRequest());
