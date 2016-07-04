<?php

class Parser {

    protected $regex = "~\\(|\\)|#t|#f|[\\+\\-]?\\d+|\\d+\\.\\d+|'|`|\"([^\"]|\\\\|\\\")*\"|[^\\s\\(\\)\"']+~m";
    protected $regexInt = "~^\\d+\$~m";
    protected $regexFloat = "~^\\d+\\.\\d+\$~m";

//    this.regex.compile();
    function tokenize(string $s) {
        preg_match_all($this->regex, $s, $matches);
        return $matches[0];
    }

    function parse(string $s) {
        $tokens = $this->tokenize($s);
        $stack = [];
        $ast = [];
        foreach ($tokens as $i => $v) {
            if ($tokens[$i] == '(') {
                array_push($stack, ['t' => 'lp']);
            } else if ($tokens[$i] == '#t') {
                array_push($stack, ['t' => 'bool', 'val' => true]);
            } else if ($tokens[$i] == '#f') {
                array_push($stack, ['t' => 'bool', 'val' => false]);
            } else if ($tokens[$i] == '\'') {
                array_push($stack, ['t' => 'quote']);
            } else if ($tokens[$i] == '`') {
                array_push($stack, ['t' => 'backquote']);
            } else if ($tokens[$i][0] == '"') {
                array_push($stack, ['t' => 'string', 'val' => $tokens[$i] . substr(1, $tokens[$i] . length - 2)]);
            } else if (preg_match($this->regexInt, $tokens[$i])) {
                array_push($stack, ['t' => 'int', 'val' => intval($tokens[$i])]);
            } else if (preg_match($this->regexFloat, $tokens[$i])) {
                array_push($stack, ['t' => 'float', 'val' => floatval($tokens[$i])]);
            } else if ($tokens[$i] == ')') {
                $cons = [];
                while (($t = array_pop($stack))) {
                    if ($t['t'] == 'lp') {
                        break;
                    }
                    array_unshift($cons, $t);
                }
//                var_dump($cons);
                array_push($stack, $cons);
            } else {
                array_push($stack, ['t' => 'symbol', 'val' => $tokens[$i]]);
            }
        }
        if (sizeof($stack) == 1) {
            $ast = array_pop($stack);
        } else {
            $cons = [];
            while (($t = array_pop($stack))) {
                if ($t['t'] == 'quote' || $t['t'] == 'backquote') {
                    $cons = [$t, $cons];
                    continue;
                }
                $cons = [$t, $cons];
            }
            $ast = $cons;
        }
        return $ast;
    }

}

class Ast {

    public $_ast;
    public $_macros = [];

    function __construct($ast) {
        $this->_ast = $ast;
        $this->onePass($this->_ast);
    }

    function macroSyms(&$ast) {
        if (empty($ast)) {
            return;
        }

        $this->macroSyms($ast[0]);
        $this->macroSyms(array_slice($ast, 1));
    }

    function onePass(&$ast) {
        if (empty($ast)) {
            return;
        }
        //[define-macro [name expr] `[,expr]]
        if (!empty($ast) && $ast[0]['t'] == 'symbol' && $ast[0]['val'] == 'define-macro') {
            $macro = [];
            $syms = [];
            $name = $ast[1][0]['val'];
            $args = array_slice($ast[1], 1);
            $body = $ast[3];
            foreach ($args as $sym) {
                array_push($syms, ',' . $sym['val']);
            }
            $this->_macros[$name] = [
                'syms' => $syms,
                'body' => $body,
            ];
            $ast = [];
            return;
        }
        $this->onePass($ast[0]);
        $this->onePass(array_slice($ast, 1));
    }

    function twoPass(&$ast, &$expanded) {
        if (empty($ast)) {
            return;
        }
//        print_r($ast);
        if (!empty($ast[0]) && $ast[0]['t'] == 'symbol' && isset($this->_macros[$ast[0]['val']])) {
            $args = $this->_macros[$ast[0]['val']]['syms'];
            $body = $this->_macros[$ast[0]['val']]['body'];
            $realArgs = [];
            $tmp = array_slice($ast, 1);
            foreach ($tmp as $arg) {
                array_push($realArgs, $arg);
            }
//可以判断参数是否对齐
            if (sizeof($args) != sizeof($realArgs)) {
                
            }
            $argsMap = array_combine($args, $realArgs);
            $reBody = $body;
            $this->expand($reBody, $argsMap);
            $ast = $reBody;
            $expanded = true;
            return;
        } else {
            foreach ($ast as &$child) {
                if (is_array($child) && !isset($child['t'])) {
                    $this->twoPass($child, $expanded);
                }
            }
        }
//        $this->twoPass($ast, $expanded);
//        $this->twoPass($ast[0], $expanded);
    }

    function threePass(&$ast) {
//展开let 
        /**
         * (let ((x 1) (y 2)) (...) )
         * ((lambda (x y) (...)) 1 2)
         */
        if (empty($ast)) {
            return;
        }
        if (!empty($ast['car']) && $ast['car']['t'] == 'symbol' && $ast['car']['val'] == 'let') {
            $args = $ast['cdr']['car'];
            $body = $ast['cdr']['cdr'];
            $arglist = [];
            $arglistAst = &$arglist;
            $real = [];
            $realAst = &$real;
            $tmp = $args;
            while (!empty($tmp)) {
                $arglistAst = [
                    'car' => $tmp['car']['car'],
                    't' => 'cons',
                    'cdr' => [],
                ];
                $arglistAst = &$arglistAst['cdr'];
                $this->threePass($tmp['car']['cdr']);
                $realAst = [
                    'car' => $tmp['car']['cdr']['car'],
                    't' => 'cons',
                    'cdr' => [],
                ];
                $realAst = &$realAst['cdr'];
                $tmp = $tmp['cdr'];
            }
            $lambdaAst = [
                'car' => [
                    't' => 'symbol',
                    'val' => 'lambda',
                ],
                'cdr' => [
                    'car' => $arglist,
                    't' => 'cons',
                    'cdr' => [
                        'car' => $body,
                        't' => 'cons',
                        'cdr' => [],
                    ],
                ],
                't' => 'cons',
            ];
            $ast = [
                't' => 'cons',
                'car' => $lambdaAst,
                'cdr' => $real,
            ];
            return;
        }
        $this->threePass($ast['car']);
        $this->threePass($ast['cdr']);
    }

    function fourPass(&$ast) {
        $expanded = true;
        while ($expanded) {
            $expanded = false;
            $this->twoPass($ast, $expanded);
        }
    }

    function fivePass(&$ast) {
        $expanded = true;
        while ($expanded) {
            $expanded = false;
            $this->twoPass($ast, $expanded);
        }
    }

    function expand(&$body, &$argsMap) {
        if (empty($body)) {
            return;
        }
        foreach ($body as &$child) {
            if (!empty($child) && $child['t'] == 'symbol' && isset($argsMap[$child['val']])) {
                $child = $argsMap[$child['val']];
            } else {
//            $this->expand($body[0], $argsMap);
//            $this->expand($body['cdr'], $argsMap);
                if (is_array($child) && !isset($child['t'])) {
                    $this->expand($child, $argsMap);
                }
            }
        }
    }

    function display($ast) {
        
    }

    function expandAll() {
        
    }

}

class CodeGenerater {

    protected $_ast;
    protected $_IR;

    function __construct($ast) {
        $this->_ast = $ast;
        $this->_IR = [];
    }

    function lookup($var, $env) {
        $tmp = $env;
        $i = 0;
        while (!empty($tmp)) {
            if (isset($tmp[$var])) {
                return [Vm::LD, [$i, $tmp[$var]]];
            }
            ++$i;
            $tmp = $tmp['parent'];
        }
        return null;
    }

    function generate($ast, $env = []) {
        if (empty($ast)) {
            return [];
        }
        switch ($ast['t']) {
            case 'bool':
            case 'string':
            case 'int':
            case 'float':
                return [Vm::LDC, $ast['val']];
            case 'cons':
                if (empty($ast['car']['val'])) {
                    return array_merge($this->generate($ast['car'], $env), $this->generate($ast['cdr'], $env));
                }
                switch ($ast['car']['val']) {
                    case '+':
                        return array_merge($this->generate($ast['cdr'], $env), [Vm::ADD]);
                        break;
                    case '-':
                        return array_merge($this->generate($ast['cdr'], $env), [Vm::SUB]);
                        break;
                    case '*':
                        return array_merge($this->generate($ast['cdr'], $env), [Vm::MUL]);
                        break;
                    case '/':
                        return array_merge($this->generate($ast['cdr'], $env), [Vm::DIV]);
                        break;
                    case '%':
                        return array_merge($this->generate($ast['cdr'], $env), [Vm::MOD]);
                        break;
                    case 'lambda':
                        $tmp = [];
                        $args = $ast['cdr']['car'];
                        $index = 0;
                        $cenv = [
                            'parent' => &$env
                        ];
                        while (!empty($args)) {
                            $cenv[$args['car']['val']] = $index;
                            ++$index;
                            $args = $args['cdr'];
                        }
                        $tmp = array_merge($tmp, $this->generate($ast['cdr']['cdr'], $cenv));
                        return array_merge([Vm::LDF], [$tmp]);
                        break;
                    default:
                        return array_merge($this->generate($ast['car'], $env), $this->generate($ast['cdr'], $env));
                        break;
                }
                break;
            case 'form':
                return array_merge($this->generate($ast['car'], $env), $this->generate($ast['cdr'], $env));
                break;
            case 'symbol':
                $ret = $this->lookup($ast['val'], $env);
                if (empty($ret)) {
                    exit("val {$ast['val']} not defined.");
                }
                return $ret;
                break;
        }
    }

}

class Vm {

    const ADD = 1; # integer addition
    const MUL = 2; # integer multiplication
    const SUB = 3; # integer subtraction
    const DIV = 4; # integer division
    const NIL = 5; # push nil pointer onto the stack
    const CONS = 6; # cons the top of the stack onto the next list
    const LDC = 7; # push a constant argument
    const LDF = 8; # load function
    const AP = 9; # function application
    const LD = 10; # load a variable
    const CAR = 11; # value of car cell
    const CDR = 12; # value of cdr cell
    const DUM = 13; # setup recursive closure list
    const RAP = 14; # recursive apply
    const JOIN = 15; # C = pop dump
    const RTN = 16; # return from function
    const SEL = 17; # logical selection (if/ then / else )
    const NULL = 18; # test if list is empty
    const WRITEI =
    19; # write an integer to the terminal
    const WRITEC =
    20; # write a character to the terminal
    const READC =
    21; # read a single character from the terminal
    const READI =
    22; # read an integer from the terminal
    const ZEROP =
    23; # test if top of stack = 0; [ nonstandard opcode ]
    const GT0P =
    24; # test if top of stack > 0 [ nonstandard opcode ]
    const LT0P =
    25; # test if top of stack < 0 [ nonstandard opcode ]
    const STOP =
    26; # halt the machine
    const ASSIGNE =
    27; # halt the machine
    const LOAD =
    28; # halt the machine
    const MOD =
    29;
    function __construct(

) {
    /**
     * class Vm:
      def __init__(self):
      #Registers: S (stack), E (environment), C (code), D (dump).
      self.S=[]
      self.E=[]
      self.C=None
      self.D=[]
      def op_LDC(self,s,c):
      self.C.pop(0)
      self.S.insert(0,self.C.pop(0))
      def op_LDF(self,s,c):
      self.C.pop(0)
      self.S.insert(0,self.C.pop(0))
      def op_LD(self,s,c):
      self.C.pop(0)
      self.S.insert(0,self.C.pop(0))
      pass
      def op_ADD(self,s,c):
      self.C.pop(0)
      right = self.S.pop(0)
      left = self.S.pop(0)
      self.S.insert(0,left + right)
      def op_MUL(self,s,c):
      self.C.pop(0)
      right = self.S.pop(0)
      left = self.S.pop(0)
      self.S.insert(0,left * right)
      def op_SUB(self,s,c):
      self.C.pop(0)
      right = self.S.pop(0)
      left = self.S.pop(0)
      self.S.insert(0,left - right)
      def op_DIV(self,s,c):
      self.C.pop(0)
      right = self.S.pop(0)
      left = self.S.pop(0)
      self.S.insert(0,left / right)
      pass
      def run(self,code):
      self.C=code
      while True:
      if self.C[0]==STOP:break
      elif self.C[0] == ADD:
      self.op_ADD(self.S,self.C)
      elif self.C[0] == LDC:
      self.op_LDC(self.S,self.C)
     */
    $this->S = [];
    $this->E = [];
    $this->C = null;
    $this->D = [];
    $this->GLOBALS = [];
}

function op_SEL(&$s, &$c) {
    array_shift($c);
    $d = array_pop($this->D);
    $cond = array_pop($s);
    $right = array_shift($c);
    $wrong = array_shift($c);
    array_push($this->D, $this->C);
    if ($cond) {
        $this->C = $right;
    } else {
        $this->C = $wrong;
    }
}

function op_JOIN(&$s, &$c) {
    array_shift($c);
    $d = array_pop($this->D);
    $this->C = $d;
}

function op_LDC(&$s, &$c) {
    array_shift($c);
    array_push($s, array_shift($c));
}

function op_LD(&$s, &$c) {
    array_shift($c);
    $index = array_shift($c);
    array_push($s, $this->E[sizeof($this->E) - $index[0] - 1][$index[1]]);
}

function op_LDF(&$s, &$c) {
    array_shift($c);
    $body = array_shift($c);
    array_push($s, [
        'C' => $body,
        'E' => &$this->E,
    ]);
}

function op_RTN(&$s, &$c) {
    array_shift($c);
    $env = array_pop($this->D);
    $this->E = $env['E'];
    $this->C = $env['C'];
}

function op_ADD(&$s, &$c) {
    array_shift($c);
    $num = array_pop($s);
    $i = 0;
    $ret = null;
    while ($i < $num) {
        if ($ret === null) {
            $ret = array_pop($s);
        } else {
            $ret += array_pop($s);
        }
        ++$i;
    }
    array_push($s, $ret);
}

function op_SUB(&$s, &$c) {
    array_shift($c);
    $num = array_pop($s);
    $i = 0;
    $ret = null;
    while ($i < $num) {
        if ($ret === null) {
            $ret = array_pop($s);
        } else {
            $ret -= array_pop($s);
        }
        ++$i;
    }
    array_push($s, $ret);
}

function op_DIV(&$s, &$c) {
    array_shift($c);
    $num = array_pop($s);
    $i = 0;
    $ret = null;
    while ($i < $num) {
        if ($ret === null) {
            $ret = array_pop($s);
        } else {
            $ret /= array_pop($s);
        }

        ++$i;
    }
    array_push($s, $ret);
}

function op_MUL(&$s, &$c) {
    array_shift($c);
    $num = array_pop($s);
    $i = 0;
    $ret = null;
    while ($i < $num) {
        if ($ret === null) {
            $ret = array_pop($s);
        } else {
            $ret *= array_pop($s);
        }
        ++$i;
    }
    array_push($s, $ret);
}

function op_LOAD(&$s, &$c) {
    array_shift($c);
    $key = array_shift($c);
    if (isset($this->GLOBALS[$key])) {
        array_push($s, $this->GLOBALS[$key]);
    } else {
        array_push($s, null);
    }
}

function op_ASSIGN(&$s, &$c) {
    array_shift($c);
    $key = array_pop($s);
    $val = array_pop($s);
    $this->GLOBALS[$key] = $val;
    array_push($s, $val);
}

function op_AP(&$s, &$c) {
    array_shift($c);
    $closure = array_pop($s);
    $args = array_pop($s);
    array_push($this->D, [
        'S' => $this->S,
        'E' => $this->E,
        'C' => $this->C,
    ]);
    $this->C = $closure['C'];
    array_push($this->E, $args);
}

function run($code) {
    $this->C = $code;
    while (true) {
        switch ($this->C[0]) {
            case self::STOP:
                break 2;
            case self::ADD:
                $this->op_ADD($this->S, $this->C);
                break;
            case self::SUB:
                $this->op_SUB($this->S, $this->C);
                break;
            case self::MUL:
                $this->op_MUL($this->S, $this->C);
                break;
            case self::DIV:
                $this->op_DIV($this->S, $this->C);
                break;
            case self::LDC:
                $this->op_LDC($this->S, $this->C);
                break;
            case self::LD:
                $this->op_LD($this->S, $this->C);
                break;
            case self::LDF:
                $this->op_LDF($this->S, $this->C);
                break;
            case self::AP:
                $this->op_AP($this->S, $this->C);
                break;
            case self::RTN:
                $this->op_RTN($this->S, $this->C);
                break;
            case self::LOAD:
                $this->op_LOAD($this->S, $this->C);
                break;
            case self::ASSIGN:
                $this->op_ASSIGN($this->S, $this->C);
                break;
        }
    }
}

}

//$s = "(lambda (x y) (+ x y 1)) ";
$s = "(define-macro (test expr)
  `(if ,expr
    #t
    #f))
(test (= 1 2)) ";
$p = new Parser($s);
$ast = $p->parse($s);
$a = new Ast($ast);
//$as = $a->onePass($ast);
$as = $a->twoPass($a->_ast, $expanded);
print_r($a->_ast);
//$gener = new CodeGenerater($a->_ast);
//print_r($gener->generate($a->_ast));
/*$vm = new Vm();
$code = [
Vm::LDC, [3, 4], Vm::LDF, [Vm::LD, [0, 1], Vm::LD, [0, 0], Vm::LDC, 2, Vm::ADD, Vm::RTN], Vm::AP, Vm::STOP
];
$vm->run($code);
var_dump($vm->S);
*/