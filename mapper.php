<?php
abstract class DomainObject
{
    private $id;

    public function __construct($id=null)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public static function getCollection($type)
    {
        return [];
    }

    public function collection()
    {
        return self::getCollection(get_class($this));
    }
}

class Venue extends DomainObject
{
    private $name;
    private $spaces;

    public function __construct($id=null, $name=null)
    {
        $this->name = $name;
        $this->spaces = self::getCollection("\\woo\\domain\\Space");
        parent::__construct($id);
    }
}

abstract class Mapper
{
    protected static $PDO;
    public function __construct()
    {
        if (!isset(self::$PDO)) {
            $dsn = $this->getDSN();
            if (is_null($dsn)) {
                throw new Exception("No DSN");
            }
            self::$PDO = new PDO($dsn);
            self::$PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    public function find($id)
    {
        $this->selectStmt()->execute([$id]);
        $array = $this->selectStmt()->fetch();
        $this->selectStmt()->closeCursor();
        if (!is_array($array)) {return null;}
        if (!isset($array['id'])) {return null;}
        $object = $this->createObject($array);
        return $object;
    }
    
    public function createObject($array)
    {
        $obj = $this->doCreateObject($array);
        return $obj;
    }

    public function insert(DomainObject $obj)
    {
        $this->doInsert($obj);
    }

    abstract public function update(DomainObject $object);
    abstract protected function doCreateObject(array $array);
    abstract protected function doInsert(DomainObject $object);
    abstract protected function selectStmt();
}

class VenueMapper extends Mapper
{
    public function __construct()
    {
        parent::__construct();
        $this->selectStmt = self::$PDO->prepare(
            "select * from venue where id=?"
        );
        $this->updateStmt = self::$PDO->prepare(
            "update venue set name=?, id=? where id=?"
        );
        $this->insertStmt = self::$PDO->prepare(
            "insert into venue(name) values(?)"
        );
    }

    public function getCollection(array $raw)
    {
        return new SpaceCollection($raw, $this);
    }

    protected function doCreateObject(array $array)
    {
        $obj = new Venue($array['id']);
        $obj->setname($array['name']);
        return $obj;
    }

    protected function doInsert(DomainObject $object)
    {
        print "inserting\n";
        debug_print_backtrace();
        $values = [$object->getName()];
        $this->insertStmt->execute($values);
        $id = self::$PDO->lastInsertId();
        $object->setId($id);
    }

    public function update(DomainObject $object)
    {
        print "updating\n";
        $values = array($object->getName(), $object->getId(), $object->getId());
        $this->updateStmt->execute($values);
    }

    public function selectStmt()
    {
        return $this->selectStmt;
    }
}

abstract class Collection implements Iterator
{
    protected $mapper;
    protected $total = 0;
    protected $raw = [];

    private $result;
    private $pointer = 0;
    private $objects = [];

    public function __construct(array $raw=null, Mapper $mapper=null)
    {
        if (!is_null($raw) && !is_null($mapper)) {
            $this->raw = $raw;
            $this->total = count($raw);
        }
        $this->mapper = $mapper;
    }

    public function add(DomainObject $object)
    {
        $class = $this->targetClass();
        if (!($object instanceof $class)) {
            throw new Exception("This is a {$class} collection");
        }
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }
    
    abstract public function targetClass();

    protected function notifyAccess()
    {
    }

    private function getRow($num)
    {
        $this->notifyAccess();
        if ($num >= $this->total || $num < 0) {
            return null;
        }
        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }
        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->mapper->createObject($this->raw[$num]);
            return $this->objects[$num];
        }
    }

    public function rewind()
    {
        $this->pointer = 0;
    }

    public function current()
    {
        return $this->getRow($this->pointer);
    }

    public function key()
    {
        return $this->pointer;
    }

    public function next()
    {
        $row = $this->getRow($this->pointer);
        if ($row) {$this->pointer++;}
        return $row;
    }

    public function valid()
    {
        return (!is_null($this->current()));
    }
}

class ObjectWatcher
{
    private $all = [];
    private static $instance;

    private function __construct()
    {
    }

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new ObjectWatcher();
        }
        return self::$instance;
    }

    public function globalKey(DomainObject $object)
    {
        $key = get_class($object).".".$object->getId();
        return $key;
    }

    public static function add(DomainObject $obj)
    {
        $inst = self::instance();
        $inst->all[$inst->globalKey($obj)] = $obj;
    }

    public static function exists($classname, $id)
    {
        $inst = self::instance();
        $key = "$classname.$id";
        if (isset($inst->all[$key])) {
            return $inst->all[$key];
        }
        return null;
    }
}
