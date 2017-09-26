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

abstract class TileDecorator extends Tile
{
    protected $tile;
    
    public function __construct(Tile $tile)
    {
        $this->tile = $tile;
    }
}

class DiamondDecorator extends TileDecorator
{
    public function getWealthFactor()
    {
        return $this->tile->getWealthFactor() + 2;
    }
}

class PollutionDecorator extends TileDecorator
{
    public function getWealthFactor()
    {
        return $this->tile->getWealthFactor() - 4;
    }
}

$tile = new Plains();
print $tile->getWealthFactor()."\n";
$tile = new DiamondDecorator(new Plains());
print $tile->getWealthFactor()."\n";
$tile = new PollutionDecorator(new DiamondDecorator(new Plains()));
print $tile->getWealthFactor()."\n";
