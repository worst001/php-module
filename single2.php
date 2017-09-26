<?php
require_once('./factory.php');
class Settings
{
    public static $COMMSTYPE = 'Bloggs';
}

class AppConfig
{
    private static $instance;
    private $commsManager;
    
    private function __construct()
    {
        $this->init();
    }

    private function init()
    {
        switch (Settings::$COMMSTYPE) {
            case 'Mega':
                $this->commsManager = new MegaCommsManager();
                break;
            case 'Bloggs':
                $this->commsManager = new BloggsCommsManager();
                break;
            default:
        }
    }

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getCommsManager()
    {
        return $this->commsManager;
    }
}

$commsMgr = AppConfig::getInstance()->getCommsManager();
print $commsMgr->getApptEncoder()->encode();
