<?php
class Preferences
{
    private $props = [];
    private static $instance;

    private function __construct()
    {
    }

    public function setProperty($key, $val)
    {
        $this->props[$key] = $val;
    }

    public function getProperty($key)
    {
        return $this->props[$key];
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Preferences();
        }
        return self::$instance;
    }
}

$pref = Preferences::getInstance();
$pref->setProperty("name", "matt");

unset($pref);

$pref2 = Preferences::getInstance();
$pref2->setProperty("name", "wenhao");
print $pref2->getProperty("name")."\n";
