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
                array_push($syms, ','.$tmp['car']['val']);
                $tmp = $tmp['cdr'];
            }
            $this->_macros[$name] = [
                'syms' => $syms,
                'body' => $body ,
            ];
            $ast = [];
            return;
        }
        $this->onePass($ast['car']);
        $this->onePass($ast['cdr']);
    }

    function twoPass(&$ast) {
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
            return;
        }
        $this->twoPass($ast['car']);
        $this->twoPass($ast['cdr']);
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
    
    function display($ast){
        
    }

    function expandAll() {
        
    }

}

class Vm {

    function __construct() {
        $this->a = null;
        $this->x = null;
        $this->f = null;
        $this->c = null;
        $this->s = 0;
        $this->stack = [];
        $this->callStack = [];
        $this->lastRefer = null;
        $this->tcoCounter = [];
        $this->topEnv = [];
    }

    function lookUp($sym) {
        
    }

    function saveStack() {
        
    }

    function restoreStack() {
        
    }

    function run() {
        while ($this->x) {
            switch ($this->x[0]) {
                case 'halt':
                    return $this->a;
                    break;
                case 'constant':
                    $this->a = $this->x[1];
                    $this->x = $this->x[2];
                    $this->lastRefer = "(anon)";
                    break;
                case 'argument':
                    $this->x = $this->x[1];
                    array_push($this->stack, $this->a);
                    $this->s++;
                    break;
                case 'assign-local':
                    $this->x = $this->x[1];
                    array_push($this->stack, $this->a);
                    break;
                case 'assign-global':
                    $this->x = $this->x[1];
                    array_push($this->stack, $this->a);
                    break;
                case 'assign-free':
                    $this->x = $this->x[1];
                    array_push($this->stack, $this->a);
                    break;
                case 'test':
                    $this->x = $this->x[1];
                    array_push($this->stack, $this->a);
                    break;
            }
        }
    }

}

$s = "(define-macro (test expr)
  `(if ,expr
    #t
    #f))
(test (= 1 2)) ";
$p = new Parser($s);
$ast = $p->parse($s);
$a = new Ast($ast);
//$as = $a->onePass($ast);
$as = $a->twoPass($a->_ast);
print_r($a->_ast);
