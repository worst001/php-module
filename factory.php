<?php
abstract class ApptEncoder
{
    abstract public function encode();
}

class BloggsApptEncoder extends ApptEncoder
{
    public function encode()
    {
        return "Appointment data encoded in BloggsCal format\n";
    }
}

class MegaApptEncoder extends ApptEncoder
{
    public function encode()
    {
        return "Appointment data encoded in MegaCal format\n";
    }
}

abstract class CommsManager2
{
    const APPT = 1;
    const TTD = 2;
    const CONTACT = 3;
    abstract public function make(int $flag);
    abstract public function getHeaderText();
    abstract public function getApptEncoder();
    abstract public function getFooterText();
}

class BloggsCommsManager extends CommsManager2
{
    public function make(int $flag)
    {
        switch ($flag) {
            case self::APPT:
                return new BloggsApptEncoder();
            case self::CONTACT:
                return new BloggsContactEncoder();
            case self::TTD:
                return new BloggsTtdEncoder();
            default:
        }
    }

    public function getHeaderText()
    {
        return "BloggsCal header\n";
    }

    public function getApptEncoder()
    {
        return new BloggsApptEncoder();
    }

    public function getFooterText()
    {
        return "BloggsCal footer\n";
    }
}

class CommsManager
{
    const BLOGGS = 1;
    const MEGA = 2;
    private $mode;

    public function __construct($mode)
    {
        $this->mode = $mode;
    }

    public function getHeaderText()
    {
        switch ($this->mode) {
            case (self::MEGA):
                return "MegaCal header\n";
            case (self::BLOGGS):
                return "BloggsCal header\n";
            default:
        }
    }

    public function getApptEncoder()
    {
        switch ($this->mode) {
            case (self::MEGA):
                return new MegaApptEncoder();
            case (self::BLOGGS):
                return new BloggsApptEncoder();
            default:
        }
    }
}
$comms2 = new BloggsCommsManager();
$comms = new CommsManager(CommsManager::MEGA);
$apptEncoder2 = $comms2->getApptEncoder()->encode();
//print $apptEncoder2;
$apptEncoder = $comms->getApptEncoder();
$appHeader = $comms->getHeaderText();
//print $apptEncoder->encode();
//print $appHeader;
