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
                    $cons = [
                        't' => "cons",
                        'car' => $t,
                        'cdr' => $cons,
                    ];
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
                    $cons['car'] = [
                        't' => 'cons',
                        'car' => $t,
                        'cdr' => $cons['car'],
                    ];
                    continue;
                }
                $cons = [
                    't' => "form",
                    'car' => $t,
                    'cdr' => $cons,
                ];
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

        $this->macroSyms($ast['car']);
        $this->macroSyms($ast['cdr']);
    }

    function onePass(&$ast) {
        if (empty($ast)) {
            return;
        }
        if (!empty($ast['car']) && $ast['car']['t'] == 'symbol' && $ast['car']['val'] == 'define-macro') {
            $macro = [];
            $syms = [];
            $name = $ast['cdr']['car']['car']['val'];
            $args = $ast['cdr']['car']['cdr'];
            $body = $ast['cdr']['cdr']['cdr'];
            $tmp = $args;
            while (!empty($tmp)) {
                array_push($syms, ',' . $tmp['car']['val']);
                $tmp = $tmp['cdr'];
            }
            $this->_macros[$name] = [
                'syms' => $syms,
                'body' => $body,
            ];
            $ast = [];
            return;
        }
        $this->onePass($ast['car']);
        $this->onePass($ast['cdr']);
    }

    function twoPass(&$ast, &$expanded) {
        if (empty($ast)) {
            return;
        }
        if (!empty($ast['car']) && $ast['car']['t'] == 'symbol' && isset($this->_macros[$ast['car']['val']])) {
            $args = $this->_macros[$ast['car']['val']]['syms'];
            $body = $this->_macros[$ast['car']['val']]['body'];
            $realArgs = [];
            $tmp = $ast['cdr'];
            while (!empty($tmp)) {
                array_push($realArgs, $tmp['car']);
                $tmp = $tmp['cdr'];
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
        }
        $this->twoPass($ast['car'], $expanded);
        $this->twoPass($ast['cdr'], $expanded);
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
        if (!empty($body['car']) && $body['car']['t'] == 'symbol' && isset($argsMap[$body['car']['val']])) {
            $body['car'] = $argsMap[$body['car']['val']];
        } else {
            $this->expand($body['car'], $argsMap);
            $this->expand($body['cdr'], $argsMap);
        }
    }

    function display($ast) {
        
    }

    function expandAll() {
        
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
}

function op_LDC(&$s, &$c) {
    array_shift($c);
    array_push($s, array_shift($c));
}

function op_LD(&$s, &$c) {
    array_shift($c);
    $index = array_shift($c);
    array_push($s, $this->E[0][$index]);
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
    $env = array_shift($this->D);
    $this->E=$env['E'];
    $this->C=$env['C'];
}

function op_ADD(&$s, &$c) {
    array_shift($c);
    $num = array_pop($s);
    $i = 0;
    $ret = 0;
    while ($i < $num) {
        $ret += array_pop($s);
        ++$i;
    }
    array_push($s, $ret);
}

function op_SUB(&$s, &$c) {
    array_shift($c);
    $num = array_pop($s);
    $i = 0;
    $ret = 0;
    while ($i < $num) {
        $ret -= array_pop($s);
        ++$i;
    }
    array_push($s, $ret);
}

function op_DIV(&$s, &$c) {
    array_shift($c);
    $num = array_pop($s);
    $i = 0;
    $ret = 0;
    while ($i < $num) {
        $ret /= array_pop($s);
        ++$i;
    }
    array_push($s, $ret);
}

function op_MUL(&$s, &$c) {
    array_shift($c);
    $num = array_pop($s);
    $i = 0;
    $ret = 0;
    while ($i < $num) {
        $ret *= array_pop($s);
        ++$i;
    }
    array_push($s, $ret);
}

function op_AP(&$s, &$c) {
    array_shift($c);
    $args  = array_pop($s);
    $closure = array_pop($s);
    array_push($this->D,[
        'S'=>$this->S,
        'E'=>$this->E,
        'C'=>$this->C,
    ]);
    $this->C=$closure['C'];
    array_push($this->E,$args);
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
        }
    }
}

}

//$s = "(let ((x 1)) (+ x 2)) ";
////$s = "(define-macro (test expr)
////  `(if ,expr
////    #t
////    #f))
////(test (= 1 2)) ";
//$p = new Parser($s);
//$ast = $p->parse($s);
//$a = new Ast($ast);
////$as = $a->onePass($ast);
//$as = $a->threePass($a->_ast);
//print_r($a->_ast);
$vm = new Vm();
$code = [
    Vm::LDC,[3,4],  Vm::LDF,[Vm::LD, 2, Vm::LD, 1, Vm::ADD, Vm::RTN] , Vm::AP , Vm::STOP 
];
$vm->run($code);
var_dump($vm->S);