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
            } else if ($tokens[$i] == ')' || $tokens[$i] == ']') {
                $cons = [];
                while (($t = array_pop($stack))) {
                    if (!empty($t['t']) && $t['t'] == 'lp') {
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
        $this->expandAll();
        $this->threePass($this->_ast);
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
        if (!empty($ast[0]) && !empty($ast[0]['t']) && $ast[0]['t'] == 'symbol' && $ast[0]['val'] == 'define-macro') {
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
            $ast = null;
            return;
        } else {
            foreach ($ast as &$child) {
                if (is_array($child) && !isset($child['t'])) {
                    $this->onePass($child);
                }
            }
        }
    }

    function twoPass(&$ast, &$expanded) {
        if (empty($ast)) {
            return;
        }
//        print_r($ast);
        if (!empty($ast[0]) && !empty($ast[0]['t']) && $ast[0]['t'] == 'symbol' && isset($this->_macros[$ast[0]['val']])) {
            $args = $this->_macros[$ast[0]['val']]['syms'];
            $body = $this->_macros[$ast[0]['val']]['body'];
            $realArgs = [];
            $tmp = array_slice($ast, 1);
            foreach ($tmp as $arg) {
                array_push($realArgs, $arg);
            }
//可以判断参数是否对齐
            if (sizeof($args) != sizeof($realArgs)) {
                echo "macro {$ast[0]['val']} arguments error.\n";
                exit();
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

        if (!empty($ast[0]) && !empty($ast[0]['t']) && $ast[0]['t'] == 'symbol' && $ast[0]['val'] == 'let') {
            $args = $ast[1];
            $body = $ast[2];
            $arglist = [];
            $real = [];
            foreach ($args as $arg) {
                array_push($arglist, $arg[0]);
                array_push($real, $arg[1]);
            }
            $lambdaAst = [
                [
                    't' => 'symbol',
                    'val' => 'lambda',
                ],
                $arglist, $body
            ];
            $lambdaAst = array_merge([$lambdaAst], $real);
            $ast = $lambdaAst;
            $this->threePass($ast[0][2]);
            return;
        } else {
            foreach ($ast as &$child) {
                if (is_array($child) && !isset($child['t'])) {
                    $this->threePass($child);
                }
            }
        }
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
        while(true){
            $expanded = false;
            $this->twoPass($this->_ast,$expanded);
            if(!$expanded){
                break;
            }
        }
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

    function generate($ast) {
        $topenv = [];
        $ir = $this->_generate($ast,$topenv);
        $ir []= Vm::STOP;
        return $ir;
    }

    function _generate($ast, &$env = [],$inLambda = false,$isQuote=false) {
        if (empty($ast)) {
            return [];
        }
        $ret = [];
        if(isset($ast['t'])){
            switch($ast['t']){
                case 'string':
                    return ['LDS',$ast['val']]; 
                    break;
                case 'char':
                    return ['LDC',$ast['val']]; 
                    break;
                case 'bool':
                    return ['LDB',$ast['val']]; 
                    break;
                case 'int':
                    return ['LDI',$ast['val']]; 
                    break;
                case 'float':
                    return ['LDF',$ast['val']]; 
                    break;
                case 'symbol':
                    if($isQuote){
                        return ['LDSY',$ast['val']]; 
                    }else{
                        switch($ast['val']){
                            case '+':
                                return [Vm::ADD]; 
                                break;
                            case '-':
                                return [Vm::SUB]; 
                                break;
                            case '*':
                                return [Vm::MUL]; 
                                break;
                            case '/':
                                return [Vm::DIV]; 
                                break;
                            case '%':
                                return [Vm::MOD]; 
                                break;
                            case '=':
                                return [Vm::EQ]; 
                                break;
                            case '>':
                                return [Vm::GT]; 
                                break;
                            case '<':
                                return [Vm::LT]; 
                                break;
                            case '<=':
                                return [Vm::LE]; 
                                break;
                            case '>=':
                                return [Vm::GE]; 
                                break;
                            default:
                                $tmp = $this->lookup($ast['val'],$env);
                                if(!empty($tmp)){
                                    return $tmp;
                                }else{
                                    return [Vm::LDN,$ast['val']];
                                }
                        }
                    }
                    break;
            }
        }else{
            if(isset($ast[0]['t']) && $ast[0]['t']=='symbol'){
                switch($ast[0]['val']){
                    case 'lambda':
                        $args = $ast[1];
                        $newEnv = [];
                        foreach($args as $arg){
                            $newEnv[$arg['val']] = sizeof($newEnv);
                        }
                        $newEnv['parent'] = &$env;
                        array_push($ret,Vm::LDF);
                        $body = $this->_generate($ast[2],$newEnv,true,$isQuote);
                        array_push($body,Vm::RTN);
                        array_push($ret,$body);
                        break;
                    case '+':
                        array_push($ret,Vm::NIL);
                        for($index = 1;$index<sizeof($ast);++$index){
                            $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                            array_push($ret,Vm::CONS);
                        }
                        array_push($ret,Vm::ADD);
                                return $ret; 
                                break;
                            case '-':
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                array_push($ret,Vm::SUB);

                                return $ret; 
                                break;
                            case '*':
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                array_push($ret,Vm::MUL);

                                return $ret; 
                                break;
                            case '/':
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                array_push($ret,Vm::DIV);

                                return $ret; 
                                break;
                            case '%':
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                array_push($ret,Vm::MOD);
                                return $ret; 
                                break;
                            case '=':
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                array_push($ret,Vm::EQ);
                                return $ret; 
                                break;
                            case '>':
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                array_push($ret,Vm::GT);
                                return $ret;
                                break;
                            case '<':
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                array_push($ret,Vm::LT);
                                return $ret;
                                break;
                            case '<=':
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                array_push($ret,Vm::LE);
                                return $ret;
                                break;
                            case '>=':
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                array_push($ret,Vm::GE);
                                return $ret;
                                break;
                            case 'if':
                                $ret = array_merge($ret,$this->_generate($ast[1],$env,$inLambda,$isQuote));
                                array_push($ret,Vm::SEL);
                                $left = array_merge($ret,$this->_generate($ast[2],$env,$inLambda,$isQuote));
                                array_push($left,Vm::JOIN);
                                array_push($ret,$left);
                                $right = array_merge($ret,$this->_generate($ast[2],$env,$inLambda,$isQuote));
                                array_push($right,Vm::JOIN);
                                array_push($ret,$right);
                                return $ret;
                                break;
                            default:
                                array_push($ret,Vm::NIL);
                                for($index = 1;$index<sizeof($ast);++$index){
                                    $ret = array_merge($ret,$this->_generate($_ast[$index],$env,$inLambda,$isQuote));
                                    array_push($ret,Vm::CONS);
                                }
                                $tmp = $this->lookup($ast['val'],$env);
                                if(!empty($tmp)){
                                    $ret  = array_merge($ret,$tmp);
                                }else{
                                    array_push($ret,Vm::LDN);
                                    array_push($ret,$ast['val']);
                                }
                                array_push($ret,Vm::AP);
                                return $ret;


                    }
                
            }else{
                if(!$isQuote){
                    array_push($ret,Vm::NIL);
                    for($index = 1;$index<sizeof($ast);++$index){
                         $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,$isQuote));
                         array_push($ret,Vm::CONS);
                    }
                    $ret = array_merge($ret,$this->_generate($ast[0],$env,$inLambda,$isQuote));
                    array_push($ret,Vm::AP);
                }else{
                    for($index=0;$index<sizeof($ast);++$index){
                        $subast = $ast[$index];
                        if(isset($subast['t']) && $subast['t'] == 'quote'){
                            ++$index;
                            if(!isset($ast[$index]['t'])){
                                array_push($ret,Vm::LDL);
                            }
                            $ret = array_merge($ret,$this->_generate($ast[$index],$env,$inLambda,true));
                        }else{
                            $ret = array_merge($ret,$this->_generate($subast,$env,$inLambda,$isQuote));
                        }
                    }
                }
                
            }
            
            return $ret;
        }
        return $ret;
    }

}

class Gc{
    function __construct(){
        $this->mempool = [];
    }
    function &makeInteger(int $i):array{
        $ret =  ['type'=>'int','value' => $i];
        $this->mempool []= ['cell'=>&$ret];
        return $ret;
    }
    function &makeFloat(float $i):array{
        $ret =  ['type'=>'float','value' => $i];
        $this->mempool []= ['cell'=>&$ret];
        return $ret;
    }
    function &makeBool(bool $i):array{
        $ret =  ['type'=>'bool','value' => $i];
        $this->mempool []= ['cell'=>&$ret];
        return $ret;
    }
    function &makeString(string $i):array{
        $ret =  ['type'=>'string','value' => &$i];
        $this->mempool []= ['cell'=>&$ret];
        return $ret;
    }
    function &makeSymbol(string $i):array{
        $ret =  ['type'=>'symbol','value' => &$i];
        $this->mempool []= ['cell'=>&$ret];
        return $ret;
    }
    function &makeChar(char $i):array{
        $ret =  ['type'=>'char','value' => $i];
        $this->mempool []= ['cell'=>&$ret];
        return $ret;
    }
    function &makeClosure(array $i,array $env):array{
        $ret =  ['type'=>'int','value' => $i,'env' => &$env];
        $this->mempool []= ['cell'=>&$ret];
        return $ret;
    }
    function &makeList(array $i):array{
        $ret =  ['type'=>'list','value' => $i];
        $this->mempool []= ['cell'=>&$ret];
        return $ret;
    }
}

class Vm {

    const ADD = 'ADD'; //1 ; # integer addition
    const MUL = 'MUL'; //2 ; # integer multiplication
    const SUB = 'SUB'; //3 ; # integer subtraction
    const DIV = 'DIV'; //4 ; # integer division
    const NIL = 'NIL'; //5 ; # push nil pointer onto the stack
    const CONS = 'CONS'; //6 ; # cons the top of the stack onto the next list
    const LDC = 'LDC'; //7 ; # push a constant argument
    const LDS = 'LDS'; //7 ; # push a constant argument
    const LDD = 'LDD'; //7 ; # push a constant argument
    const LDI = 'LDI'; //7 ; # push a constant argument
    const LDF = 'LDF'; //8 ; # load function
    const LDB = 'LDB'; //7 ; # push a constant argument
    const LDSY = 'LDSY'; //7 ; # push a constant argument
    const AP = 'AP'; //9 ; # function application
    const LD = 'LD'; //10 ; # load a variable
    const LDL = 'LDL'; //10 ; # load a variable
    const CAR = 'CAR'; //11 ; # value of car cell
    const CDR = 'CDR'; //12 ; # value of cdr cell
    const DUM = 'DUM'; //13 ; # setup recursive closure list
    const RAP = 'RAP'; //14 ; # recursive apply
    const JOIN = 'JOIN'; //15 ; # C = pop dump
    const RTN = 'RTN'; //16 ; # return from function
    const SEL = 'SEL'; //17 ; # logical selection (if/ then / else )
    const TAP = 'TAP';
    const LDN = 'LDN';
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
    const EQ = 'QE';
    const GT = 'GT';
    const LT = 'LT';
    const LE = 'LE';
    const GE = 'GE';

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
    $this->_gc = new Gc();
}


function op_SEL(&$s, &$c) {
    array_shift($c);
    $d = array_pop($this->D);
    $cond = array_pop($s);
    $right = array_shift($c);
    $wrong = array_shift($c);
    array_push($this->D, ['c'=> &$this->C]);
    if ($cond) {
        $this->C = &$right;
    } else {
        $this->C = &$wrong;
    }
}

function op_CONS(&$s, &$c) {
    array_shift($c);
    $arg = array_pop($s);
    if (sizeof($s) == 0) {
        array_push($s, [$arg]);
    } else {
        array_push($s[sizeof($s) - 1], $arg);
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

function op_NIL(&$s, &$c) {
    array_shift($c);
    array_push($s, []);
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

function op_WRITEI(&$s, &$c) {
    array_shift($c);
    $num = array_pop($s);
    echo $num;
}

function op_WRITEC(&$s, &$c) {
    array_shift($c);
    $char = array_pop($s);
    echo $char;
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
    $this->E = $closure['E'];
    array_push($this->E, $args);
}

function run($code) {
    $this->C = $code;
    while (true) {
        if(empty($this->C)){
            break;
        }
//        var_dump($this->C[0]);
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
            case self::CONS:
                $this->op_CONS($this->S, $this->C);
                break;
            case self::NIL:
                $this->op_NIL($this->S, $this->C);
                break;
        }
    }
}

}

//$s = "(lambda (x y) (+ x y 1)) ";
//$s = "(define-macro (test expr)
//  `(if ,expr
//    #t
//    #f))
//(test (= 1 2)) ";
$s = "(let ((x 0) (y 1)) ((lambda (z)  (+ x y z)) 5))";
$p = new Parser($s);
$ast = $p->parse($s);
$a = new Ast($ast);
//$as = $a->onePass($ast);
//$as = $a->twoPass($a->_ast, $expanded);
$as = $a->threePass($a->_ast);
//print_r($a->_ast);
$gener = new CodeGenerater($a->_ast);
$ir = $gener->generate($a->_ast);
print_r($ir);
//print_r($gener->generate($a->_ast));
$vm = new Vm();
//$code = [
//Vm::LDC, [3, 4], Vm::LDF, [Vm::LD, [0, 1], Vm::LD, [0, 0], Vm::LDC, 2, Vm::ADD, Vm::RTN], Vm::AP, Vm::STOP
//];
//$ir[8][7] = Vm::RTN;
//$vm->run($ir);
//var_dump($vm->S);
