<?php
class Field
{
    protected $name = null;
    protected $operator = null;
    protected $comps = [];
    protected $incomplete = false;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addTest($operator, $value)
    {
        $this->comps[] = [
            'name'      =>  $this->name,
            'operator'  =>  $operator,
            'value'     =>  $value,
        ];
    }
    
    public function getComps()
    {
        return $this->comps;
    }

    public function isIncomplete()
    {
        return empty($this->comps);
    }
}

class IdentityObject
{
    protected $currentfield = null;
    protected $fields = [];
    private $and = null;
    private $enforce = [];

    public function __construct($field = null, array $enforce = null)
    {
        if (!is_null($enforce)) {
            $this->enforce = $enforce;
        }
        if (!is_null($field)) {
            $this->field($field);
        }
    }

    public function getObjectFields()
    {
        return $this->enforce;
    }

    public function field($fieldname)
    {
        if (!$this->isVoid() && $this->currentfield->isIncomplete()) {
            throw new Exception("Incomplete field");
        }

        $this->enforceField($fieldname);
        if (isset($this->fields[$fieldname])) {
            $this->currentfield = $this->fields[$fieldname];
        } else {
            $this->currentfield = new Field($fieldname);
            $this->fields[$fieldname] = $this->currentfield;
        }
        return $this;
    }

    public function isVoid()
    {
        return empty($this->fields);
    }

    public function enforceField($fieldname)
    {
        if (!in_array($fieldname, $this->enforce) &&
            !empty($this->enforce)
           ) {
            $forcelist = implode(',', $this->enforce);
            throw new Exception("{$fieldname} not a legal field {$forcelist}");
        }
    }

    public function eq($value)
    {
        return $this->operator("=", $value);
    }

    public function lt($value)
    {
        return $this->operator("<", $value);
    }

    public function gt($value)
    {
        return $this->operator(">", $value);
    }

    private function operator($symbol, $value)
    {
        if ($this->isVoid()) {
            throw new Exception("no object field defined");
        }
        $this->currentfield->addTest($symbol, $value);
        return $this;
    }

    public function getComps()
    {
        $ret = [];
        foreach ($this->fields as $key => $field) {
            $ret = array_merge($ret, $field->getComps());
        }
        return $ret;
    }
}
