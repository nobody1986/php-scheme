<?php

class Parser {

    protected $regex = "~\\(|\\)|#t|#f|[\\+\\-]?\\d+|\\d+\\.\\d+|'|\"([^\"]|\\\\|\\\")*\"|[^\\s\\(\\)\"']+~m";
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
            while (($t = array_pop($stack))) {
                if ($t['t'] == 'lp') {
                    break;
                }
                $cons = [
                    't' => "cons",
                    'car' => t,
                    'cdr' => cons,
                ];
            }
            array_push($stack, $cons);
        }
        return $ast;
    }

}

class Vm {
    function __construct() {
        $this->a=null;
        $this->x=null;
        $this->f=null;
        $this->c=null;
        $this->s=null;
        $this->stack=[];
        $this->callStack=[];
        $this->lastRefer=[];
        $this->tcoCounter =[];
    }
    
    function lookUp(){}
    function saveStack(){}
    function restoreStack(){}
    
    function run(){
        
    }
}

$s = "((lambda (x) (* x 5)))";
$p = new Parser($s);
$ast = $p->parse($s);
print_r($ast);
