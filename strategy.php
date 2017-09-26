<?php
abstract class Question
{
    protected $prompt;
    protected $marker;

    public function __construct($prompt, Marker $marker)
    {
        $this->marker = $marker;
        $this->prompt = $prompt;
    }

    public function mark($response)
    {
        return $this->marker->mark($response);
    }
}

class TextQuestion extends Question
{}

class AVQuestion extends  Question
{}

abstract class Marker
{
    protected $test;

    public function __construct($test)
    {
        $this->test = $test;
    }

    abstract public function mark($response);
}

class MarkLogicMarker extends Marker
{
    private $engine;

    public function __construct($test)
    {
        parent::__construct($test);
    }

    public function mark($response)
    {
        return true;
    }
}

class MatchMarker extends  Marker
{
    public function mark($response)
    {
        return ($this->test == $response);
    }
}

class RegexpMarker extends Marker
{
    public function mark($response)
    {
        return (preg_match($this->test, $response));
    }
}

$markers = [new RegexpMarker("/f.ve/"),new MatchMarker("five"),new MarkLogicMarker('$input equals "five"')];

foreach ($markers as $marker) {
    print get_class($marker)."\n";
    $question = new TextQuestion("how many beans make five", $marker);
    foreach (["five", "four"] as $response) {
        print "\tresponse: $response: ";
        if ($question->mark($response)) {
            print "well done\n";
        } else {
            print "never mind\n";
        }
    }
}