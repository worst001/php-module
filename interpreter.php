<?php
abstract class Expression
{
    private static $keycount = 0;
    private $key;

    abstract function interpret(InterpreterContext $context);
    
    public function getKey()
    {
        if (!isset($this->key)) {
            self::$keycount++;
            $this->key = self::$keycount;
        }
        return $this->key;
    }
}

class LiteralExpression extends Expression
{
    private $value;
    
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function interpret(InterpreterContext $context)
    {
        $context->replace($this, $this->value);
    }
}

class InterpreterContext
{
    private $expressionstore = [];

    public function replace(Expression $exp, $value)
    {
        $this->expressionstore[$exp->getKey()] = $value;
    }

    public function lookup(Expression $exp)
    {
        var_dump($exp->getKey());
        return $this->expressionstore[$exp->getKey()];
    }
}

class VariableExpression extends Expression
{
    private $name;
    private $val;

    public function __construct($name, $val=null)
    {
        $this->name = $name;
        $this->val = $val;
    }

    public function interpret(InterpreterContext $context)
    {
        if (!is_null($this->val)) {
            $context->replace($this, $this->val);
            $this->val = null;
        }
    }

    public function setValue($value)
    {
        $this->val = $value;
    }
    
    public function getKey()
    {
        return $this->name;
    }
}

abstract class OperatorExpression extends Expression
{
    protected $l_op;
    protected $r_op;

    public function __construct(Expression $l_op, Expression $r_op)
    {
        $this->l_op = $l_op;
        $this->r_op = $r_op;
    }

    public function interpret(InterpreterContext $context)
    {
        $this->l_op->interpret($context);
        $this->r_op->interpret($context);
        $result_l = $context->lookup($this->l_op);
        $result_r = $context->lookup($this->r_op);
        $this->doInterpret($context, $result_l, $result_r);
    }

    abstract protected function doInterpret(InterpreterContext $context, $result_l, $result_r);
}

class EqualsExpression extends OperatorExpression
{
    protected function doInterpret(InterpreterContext $context, $result_l, $result_r)
    {
        $context->replace($this, $result_l = $result_r);
    }
}

class BooleanOrExpression extends OperatorExpression
{
    protected function doInterpret(InterpreterContext $context, $result_l, $result_r)
    {
        $context->replace($this, $result_l || $result_r);
    }
}

class BooleanAndExpression extends OperatorExpression
{
    protected function doInterpret(InterpreterContext $context, $result_l, $result_r)
    {
        $context->replace($this, $result_l && $result_r);
    }
}

$context = new InterpreterContext();
//$literal = new LiteralExpression('four');
//$literal->interpret($context);
//$myvar = new VariableExpression('input', 'four');
//$myvar->interpret($context);
//$myvar->setValue("five");
//$myvar->interpret($context);
//print $context->lookup($myvar)."\n";
//print $context->lookup($literal)."\n";

$input = new VariableExpression('input');
$four = new LiteralExpression('four');
$four2 = new LiteralExpression('4');
$equals = new EqualsExpression($input, $four);
$equals2 = new EqualsExpression($input, $four2);
//var_dump($context);
$statement = new BooleanOrExpression($equals, $equals2);

//var_dump($context);
foreach (["four", "4", "52"] as $val) {
    //$context = new InterpreterContext();
    $input->setValue($val);
    print "$val:\n";
    //var_dump($input);
    $statement->interpret($context);
    var_dump("----------------\n");
    var_dump($context);
    if ($context->lookup($statement)) {
        print "top marks\n\n";
    } else {
        print "dunce hat on\n\n";
    }
}
