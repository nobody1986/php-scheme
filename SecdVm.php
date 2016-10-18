<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
            if ($tokens[$i] == '(' || $tokens[$i] == '[') {
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
            } else if ($tokens[$i] == ')' || $tokens[$i] == '】') {
                $cons = [];
                while (($t = array_pop($stack))) {
                    if (!empty($t['t']) && $t['t'] == 'lp') {
                        break;
                    }
                    array_unshift($cons, $t);
                }
                array_push($stack, $cons);
            } else {
                array_push($stack, ['t' => 'symbol', 'val' => $tokens[$i]]);
            }
        }
        $ast = $stack;
        return $ast;
    }

}


class CodeGenerator{
    function __construct($ast) {
        $this->_ast = $ast;
    }
    
    function generate(){
        $ir = $this->_generate($this->_ast);
    }
    
    function lookup($var,$env){
        while(!empty($env)){
            if(!empty($env[$var])){
                return $env[$var];
            }
            if(empty($env['uplevel'])){
                break;
            }
            $env = $env['uplevel'];
        }
        return null;
    }
    
    function _generate($ast,$env=[]){
        foreach($ast as $node){
            if(empty($node['t'])){
                
            }else{
                
            }
        }
    }
}

/**
 * Description of SecdVm
 *
 * @author Administrator
 */
class SecdAssembler {

    function __construct($code) {
        $this->code = $code;
        $this->string = "";
    }

    function assemble($code) {
        if (is_array($code)) {
            
        } elseif (is_numeric($code)) {
            
        } elseif (is_scalar($code)) {
            //string
        }
    }

}

class SecdVm {

    const ADD = 'ADD'; //1 ; # integer addition
    const MUL = 'MUL'; //2 ; # integer multiplication
    const SUB = 'SUB'; //3 ; # integer subtraction
    const DIV = 'DIV'; //4 ; # integer division
    const NIL = 'NIL'; //5 ; # push nil pointer onto the stack
    const CONS = 'CONS'; //6 ; # cons the top of the stack onto the next list
    const LDC = 'LDC'; //7 ; # push a constant argument
    const LDF = 'LDF'; //8 ; # load function
    const AP = 'AP'; //9 ; # function application
    const LD = 'LD'; //10 ; # load a variable
    const CAR = 'CAR'; //11 ; # value of car cell
    const CDR = 'CDR'; //12 ; # value of cdr cell
    const DUM = 'DUM'; //13 ; # setup recursive closure list
    const RAP = 'RAP'; //14 ; # recursive apply
    const JOIN = 'JOIN'; //15 ; # C = pop dump
    const RTN = 'RTN'; //16 ; # return from function
    const SEL = 'SEL'; //17 ; # logical selection (if/ then / else )
    const NULL = 'NULL'; //18 ; # test if list is empty
    const WRITEI =
    'WRITEI'; //19 ; # write an integer to the terminal
    const WRITEC =
    'WRITEC'; //20 ; # write a character to the terminal
    const READC =
    'READC'; //21 ; # read a single character from the terminal
    const READI =
    'READI'; //22 ; # read an integer from the terminal
    const ZEROP =
    'ZEROP'; //23 ; # test if top of stack = 0; [ nonstandard opcode ]
    const GT0P =
    'GT0P'; //24 ; # test if top of stack > 0 [ nonstandard opcode ]
    const LT0P =
    'LT0P'; //25 ; # test if top of stack < 0 [ nonstandard opcode ]
    const STOP =
    'STOP'; //26 ; # halt the machine
    const ASSIGN =
    'ASSIGN'; //27 ; # halt the machine
    const LOAD =
    'LOAD'; //28 ; # halt the machine
    const MOD =
    'MOD'; //29 ;
    function __construct(

) {
    $this->S = [];
    $this->E = [];
    $this->C = null;
    $this->D = [];
    $this->GLOBALS = [];
}

function op_SEL() {
    array_shift($this->C);
    $d = array_pop($this->D);
    $cond = array_pop($this->S);
    $right = array_shift($this->C);
    $wrong = array_shift($this->C);
    array_push($this->D, $this->C);
    if ($cond) {
        $this->C = $right;
    } else {
        $this->C = $wrong;
    }
}

function op_CONS() {
    array_shift($this->C);
    $arg = array_pop($this->S);
    if (sizeof($this->S) == 0) {
        array_push($this->S, [$arg]);
    } else {
        array_push($this->S[sizeof($this->S) - 1], $arg);
    }
}

function op_JOIN() {
    array_shift($this->C);
    $d = array_pop($this->D);
    $this->C = $d;
}

function op_LDC() {
    array_shift($this->C);
    array_push($this->S, array_shift($this->C));
}

function op_NIL() {
    array_shift($this->C);
    array_push($this->S, []);
}

function op_LD() {
    array_shift($this->C);
    $index = array_shift($this->C);
    array_push($this->S, $this->E[sizeof($this->E) - $index[0] - 1][$index[1]]);
}

function op_LDF() {
    array_shift($this->C);
    $body = array_shift($this->C);
    array_push($this->S, [
        'C' => $body,
        'E' => &$this->E,
    ]);
}

function op_RTN() {
    array_shift($this->C);
    $env = array_pop($this->D);
    $this->E = $env['E'];
    $this->C = $env['C'];
}

function op_ADD() {
    array_shift($this->C);
    $num = array_pop($this->S);
    $i = 0;
    $ret = null;
    while ($i < $num) {
        if ($ret === null) {
            $ret = array_pop($this->S);
        } else {
            $ret += array_pop($this->S);
        }
        ++$i;
    }
    array_push($this->S, $ret);
}

function op_SUB() {
    array_shift($this->C);
    $num = array_pop($this->S);
    $i = 0;
    $ret = null;
    while ($i < $num) {
        if ($ret === null) {
            $ret = array_pop($this->S);
        } else {
            $ret -= array_pop($this->S);
        }
        ++$i;
    }
    array_push($this->S, $ret);
}

function op_DIV() {
    array_shift($this->C);
    $num = array_pop($this->S);
    $i = 0;
    $ret = null;
    while ($i < $num) {
        if ($ret === null) {
            $ret = array_pop($this->S);
        } else {
            $ret /= array_pop($this->S);
        }
        ++$i;
    }
    array_push($this->S, $ret);
}

function op_WRITEI() {
    array_shift($this->C);
    $num = array_pop($this->S);
    echo $num;
}

function op_WRITEC() {
    array_shift($this->C);
    $char = array_pop($this->S);
    echo $char;
}

function op_MUL() {
    array_shift($this->C);
    $num = array_pop($this->S);
    $i = 0;
    $ret = null;
    while ($i < $num) {
        if ($ret === null) {
            $ret = array_pop($this->S);
        } else {
            $ret *= array_pop($this->S);
        }
        ++$i;
    }
    array_push($this->S, $ret);
}

function op_LOAD() {
    array_shift($this->C);
    $key = array_shift($this->C);
    if (isset($this->GLOBALS[$key])) {
        array_push($this->S, $this->GLOBALS[$key]);
    } else {
        array_push($this->S, null);
    }
}

function op_ASSIGN() {
    array_shift($this->C);
    $key = array_pop($this->S);
    $val = array_pop($this->S);
    $this->GLOBALS[$key] = $val;
    array_push($this->S, $val);
}

function op_AP() {
    array_shift($this->C);
    $closure = array_pop($this->S);
    $args = array_pop($this->S);
    array_push($this->D, [
        'S' => $this->S,
        'E' => $this->E,
        'C' => $this->C,
    ]);
    $this->C = $closure['C'];
    $this->E = $closure['E'];
    array_push($this->E, $args);
}

function expand($str) {
    
}

function run($code) {
    /**
     *  len .data .code
     */
    $this->C = $code;
    while (true) {
        if (empty($this->C)) {
            break;
        }
        switch ($this->C[0]) {
            case self::STOP:
                break 2;
            case self::ADD:
                $this->op_ADD();
                break;
            case self::SUB:
                $this->op_SUB();
                break;
            case self::MUL:
                $this->op_MUL();
                break;
            case self::DIV:
                $this->op_DIV();
                break;
            case self::LDC:
                $this->op_LDC();
                break;
            case self::LD:
                $this->op_LD();
                break;
            case self::LDF:
                $this->op_LDF();
                break;
            case self::AP:
                $this->op_AP();
                break;
            case self::RTN:
                $this->op_RTN();
                break;
            case self::LOAD:
                $this->op_LOAD();
                break;
            case self::ASSIGN:
                $this->op_ASSIGN();
                break;
            case self::CONS:
                $this->op_CONS();
                break;
            case self::NIL:
                $this->op_NIL();
                break;
        }
    }
}

}


$p = new Parser();
$r = $p->parse('((lambda (x) (+ x 1)) 2)');
print_r($r);