<?php
class Login implements Observable
{
    const LOGIN_USER_UNKNOWN = 1;
    const LOGIN_WRONG_PASS = 2;
    const LOGIN_ACCESS = 3;
    private $status = [];

    public function handleLogin($user, $pass, $ip)
    {
        switch (rand(1,3)) {
            case 1:
                $this->setStatus(self::LOGIN_ACCESS, $user, $ip);
                $ret = true;
                break;
            case 2:
                $this->setStatus(self::LOGIN_WRONG_PASS, $user, $ip);
                $ret = false;
                break;
            case 3:
                $this->setStatus(self::LOGIN_USER_UNKNOWN, $user, $ip);
                $ret = false;
                break;
        }
        //Logger:logIP(); todo
        return $ret;
    }

    private function setStatus($status, $user, $ip)
    {
        $this->status = [$status, $user, $ip];
    }

    public function getStatus()
    {
        return $this->status;
    }
}


