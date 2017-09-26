<?php
abstract class Unit
{
    protected $depth;

    public function getComposite()
    {
        return null;
    }

    abstract public function bombardStrength();

    public function textDump($num = 0)
    {
        $ret = "";
        $pad = 4*$num;
        $ret.= sprintf("%{$pad}s", " ");
        $ret.= get_class($this).": ";
        $ret.= "bombard: ".$this->bombardStrength()."\n";
        return $ret;
    }
    
    public function accept(ArmyVisitor $visitor)
    {
        $method = "visit".get_class($this);
        $visitor->$method($this);
    }

    protected function setDepth($depth)
    {
        $this->depth = $depth;
    }
    
    public function getDepth()
    {
        return $this->depth;
    }
}

abstract class CompositeUnit extends Unit
{
    private $units = [];
    
    public function getComposite()
    {
        return $this;
    }

    public function textDump($num = 0)
    {
        $ret = parent::textDump($num);
        foreach ($this->units as $unit) {
            $ret.= $unit->textDump($num + 1);
        }
        return $ret;
    }

    public function accept(ArmyVisitor $visitor)
    {
        parent::accept($visitor);
        foreach ($this->units as $thisunit) {
            $thisunit->accept($visitor);
        }
    }

    protected function units()
    {
        return $this->units;
    }

    public function addUnit(Unit $unit)
    {
        if (in_array($unit, $this->units, true)) {
            return;
        }
        $unit->setDepth($this->depth + 1);
        $this->units[] = $unit;
    }
  
    public function removeUnit(Unit $unit)
    {
        $this->units =
        array_udiff(
            $this->units,
            [$unit],
            function ($a, $b) {
                return ($a == $b)? 0:1;
            }
        );
    }

    public function bombardStrength()
    {
        $ret = 0;
        foreach ($this->units as $unit) {
            $ret+= $unit->bombardStrength();
        }
        return $ret;
    }
}

class Army extends CompositeUnit
{
    private $units = [];

//    public function addUnit(Unit $unit)
//    {
//        if (in_array($unit, $this->units, true)) {
//            return;
//        }
//        $unit->setDepth($this->depth);
//        $this->units[] = $unit;
//    }

    public function removeUnit(Unit $unit)
    {
        $this->units =
        array_udiff(
            $this->units,
            [$unit],
            function ($a, $b) {
                return ($a == $b)? 0:1;
            }
        );
    }
}

class UnitException extends Exception
{
}

class Archer extends Unit
{
    public function bombardStrength()
    {
        return 4;
    }
}

class LaserCannonUnit extends Unit
{
    public function bombardStrength()
    {
        return 44;
    }
}

abstract class ArmyVisitor
{
    abstract public function visit(Unit $node);

    public function visitArcher(Archer $node)
    {
        $this->visit($node);
    }

    public function visitCavalry(Cavalry $node)
    {
        $this->visit($node);
    }

    public function visitLaserCannonUnit(LaserCannonUnit $node)
    {
        $this->visit($node);
    }

    public function visitTroopCarrierUnit(TroopCarrierUnit $node)
    {
        $this->visit($node);
    }

    public function visitArmy(Army $node)
    {
        $this->visit($node);
    }
}

class TextDumpArmyVisitor extends ArmyVisitor
{
    private $text = "";

    public function visit(Unit $node)
    {
        $ret = "";
        $pad = 4*$node->getDepth();
        $ret.= sprintf("%{$pad}s", " ");
        $ret.= get_class($node).": ";
        $ret.= "bombard: ".$node->bombardStrength()."\n";
        $this->text.=$ret;
    }

    public function getText()
    {
        return $this->text;
    }
}

class UnitScript
{
    public static function joinExisting(
        Unit $newUnit,
        Unit $occupyingUnit
    ) {
        $comp;
        if (!is_null($comp = $occupyingUnit->getComposite())) {
            $comp->addUnit($newUnit);
        } else {
            $comp = new Army();
            $comp->addUnit($occupyingUnit);
            $comp->addUnit($newUnit);
        }
        return $comp;
    }
}

$main_army = new Army();
$main_army->addUnit(new Archer());
$main_army->addUnit(new LaserCannonUnit());

//$sub_army = new Army();
//$sub_army->addUnit(new Archer());
//$sub_army->addUnit(new Archer());
//$sub_army->addUnit(new Archer());

//$main_army->addUnit($sub_army);
//var_dump($main_army);
//print "attacking with strength: {$main_army->bombardStrength()}\n";

$textdump = new TextDumpArmyVisitor();
//var_dump($main_army);
$main_army->accept($textdump);
print $textdump->getText();
