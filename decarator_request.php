<?php
class RequestHelper
{
}

abstract class ProcessRequest
{
    abstract public function process(RequestHelper $req);
}

class MainProcess extends ProcessRequest
{
    public function process(RequestHelper $req)
    {
        print __class__.": doing something useful with request\n";
    }
}

abstract class DecorateProcess extends ProcessRequest
{
    protected $processrequest;
    
    public function __construct(ProcessRequest $pr)
    {
        $this->processrequest = $pr;
    }
}

class LogRequest extends DecorateProcess
{
    public function process(RequestHelper $req)
    {
        print __class__.": logging request\n";
        $this->processrequest->process($req);
    }
}

class AuthenticateRequest extends DecorateProcess
{
    public function process(RequestHelper $req)
    {
        print __class__.": authenticating request\n";
        $this->processrequest->process($req);
    }
}

class StructureRequest extends DecorateProcess
{
    public function process(RequestHelper $req)
    {
        print __class__.": structuring request\n";
        $this->processrequest->process($req);
    }
}

$process =
new AuthenticateRequest(
    new StructureRequest(
        new LogRequest(
            new MainProcess()
        )
    )
);
var_dump($process);
$process->process(new RequestHelper());
