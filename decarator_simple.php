<?php
abstract class Tile
{
    abstract public function getWealthFactor();
}

class Plains extends Tile
{
    private $wealthfactor = 2;
    public function getWealthFactor()
    {
        return $this->wealthfactor;
    }
}

class DiamondPlains extends Plains
{
    public function getWealthFactor()
    {
        return parent::getWealthFactor() + 2;
    }
}

class PollutedPlains extends Plains
{
    public function getWealthFactor()
    {
        return parent::getWealthFactor() - 4;
    }
}

$tile = new PollutedPlains();
print $tile->getWealthFactor()."\n";
