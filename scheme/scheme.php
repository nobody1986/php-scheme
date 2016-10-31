<?php
namespace scheme;
require_once('./parser/parser.php');
require_once('./parser/syntax.php');


class Intepreter{
    protected $_env  ;
    protected $_toplevel  ;
    protected $_callFrame = [];
    function __construct(){
        $this->_toplevel = new parser\Env(null);
        $this->registGlobal('+',
        new parser\Procedure('+',function($interp,$args,$env){
            $ret = $args[0]->val();
            $size = sizeof($args);
            for($i=1;$i<$size;++$i){
                $ret += $args[$i]->val();
            }
            return is_float($ret)?new parser\Double($ret):new parser\Integer($ret);
        }));
        $this->registGlobal('-',
        new parser\Procedure('-',function($interp,$args,$env){
            $ret = $args[0]->val();
            $size = sizeof($args);
            for($i=1;$i<$size;++$i){
                $ret -= $args[$i]->val();
            }
            return is_float($ret)?new parser\Double($ret):new parser\Integer($ret);
        }));
        $this->registGlobal('*',
        new parser\Procedure('*',function($interp,$args,$env){
            $ret = $args[0]->val();
            $size = sizeof($args);
            for($i=1;$i<$size;++$i){
                $ret *= $args[$i]->val();
            }
            return is_float($ret)?new parser\Double($ret):new parser\Integer($ret);
        }));
        $this->registGlobal('/',
        new parser\Procedure('/',function($interp,$args,$env){
            $ret = $args[0]->val();
            $size = sizeof($args);
            for($i=1;$i<$size;++$i){
                $ret /= $args[$i]->val();
            }
            return is_float($ret)?new parser\Double($ret):new parser\Integer($ret);
        }));
        $this->registGlobal('%',
        new parser\Procedure('%',function($interp,$args,$env){
            $ret = $args[0]->val()%$args[1]->val();
            return  new parser\Integer($ret);
        }));
        $this->registGlobal('atom?',
        new parser\Func(function($interp,$args,$env){
            return  new parser\Boolean($args[0] instanceof parser\Atomic);
        }));
        $this->registGlobal('eval',
        new parser\Procedure('eval',function($interp,$args,$env){
            return  $interp->eval($args[0],$env);
        }));
        $this->registGlobal('apply',
        new parser\Procedure('apply',function($interp,$args,$env){
            return  $interp->eval($args[0],$env);
        }));
        $this->registGlobal('string-parse',
        new parser\Procedure('string-parse',function($interp,$args,$env){
            $le = new parser\Lexer();
            $le->set($args[0]->val());
            $tokens = $le->tokenize();
            $ret =  parser\analyzeAst($tokens);
            return $ret[0];
        }));
        $this->registGlobal('read-line',
        new parser\Procedure('read-line',function($interp,$args,$env){
            $line = trim(fgets(STDIN));
            return new parser\Str($line);
        }));
        $this->registGlobal('display',
        new parser\Procedure('display',function($interp,$args,$env){
            echo $args[0];
            return parser\Nil::instance();
        }));
        $this->registGlobal('println',
        new parser\Procedure('println',function($interp,$args,$env){
            echo $args[0]."\n";
            return parser\Nil::instance();
        }));
        $this->registGlobal('cons',
        new parser\Procedure('cons',function($interp,$args,$env){
            return $interp->cons($args[0],$args[1]);
        }));
        $this->registGlobal('car',
        new parser\Procedure('car',function($interp,$args,$env){
            return $args[0]->car();
        }));
        $this->registGlobal('cdr',
        new parser\Procedure('cdr',function($interp,$args,$env){
            return $args[0]->cdr();
        }));
        $this->registGlobal('set-car!',
        new parser\Procedure('set-car!',function($interp,$args,$env){
             $args[0]->setCar($args[1]);
             return parser\Nil::instance();
        }));
        $this->registGlobal('set-cdr!',
        new parser\Procedure('set-cdr!',function($interp,$args,$env){
             $args[0]->setCdr($args[1]);
             return parser\Nil::instance();
        }));
        $this->registGlobal('string->list',
        new parser\Procedure('string->list',function($interp,$args,$env){
             $str = $args[0]->val();
             $len = strlen($str);
             $ret = parser\Nil::instance();
             for($i = $len-1;$i>=0;--$i){
                 $ret = new parser\Lists(new parser\Char($str[$i]),$ret);
             }
             return $ret;
        }));
        $this->registGlobal('<=',
        new parser\Procedure('<=',function($interp,$args,$env){
             return new parser\Boolean($args[0]->val() <= $args[1]->val());
        }));
    }

    function registGlobal($name,$value){
        $this->_toplevel->set($name,$value);
    }

    function eval($ast,$env){
        if($ast instanceof parser\Str){
            return $ast;
        }elseif($ast instanceof parser\Symbol){
            return $ast;
        }elseif($ast instanceof parser\Integer){
            return $ast;
        }elseif($ast instanceof parser\Double){
            return $ast;
        }elseif($ast instanceof parser\Char){
            return $ast;
        }elseif($ast instanceof parser\Boolean){
            return $ast;
        }elseif($ast instanceof parser\Nil){
            return $ast;
        }elseif($ast instanceof parser\Lambda){
            return $ast;
        }elseif($ast instanceof parser\Lists){
           // array_push($this->_callFrame,[
            //    'env' => $this->_env,
            //    'code' => $this->_env, 
            //]); 
            
            $func =  $ast->car();
            $args = $ast->cdr();
            //var_dump($func);
            if($func instanceof parser\Lists){
                $func = $this->eval($func,$env);
            }elseif($func instanceof parser\Symbol){
                switch($func->val()){
                    case 'lambda':
                        return new parser\Lambda($ast,$env);
                        break;
                    case 'apply':
                        return $this->apply($ast->cdr()->car(),$ast->cdr()->cdr());
                        break;
                    case 'if':
                        $cond =  $this->eval($ast->cdr()->car(),$env);
                        if($cond->val()){
                            return $this->eval($ast->cdr()->cdr()->car(),$env);
                        }else{
                            return $this->eval($ast->cdr()->cdr()->cdr()->car(),$env);
                        }
                        break;
                    case 'quote':
                        return $ast->cdr();
                        break;
                    case 'define':
                        $name = $ast->cdr()->car()->val();
                        $value = $this->eval($ast->cdr()->cdr()->car(),$env);
                        $this->registGlobal($name,$value);
                        return parser\Nil::instance();
                        break;
                    case 'begin':
                        while(!($args instanceof parser\Nil) ){
                            $line = $args->car();
                            $ret = $this->eval($line,$env);
                            $args = $args->cdr();
                        }
                        return $ret;
                        break;
                    default:
                        $func = $env->lookup($func->val());
                }
            }
            if($func instanceof parser\Lambda){
                $argsArr = [];
                while(!($args instanceof parser\Nil)){
                    $r =  $this->eval($args->car(),$env);
                    if($r instanceof parser\Symbol){
                        $r = $env->lookup($r->val());
                    }
                    $argsArr []= $r;
                    $args = $args->cdr();
                }
                
                return $this->eval($func->code(),$func->call($argsArr));
            }elseif($func instanceof parser\Procedure){
                $argsArr = [];
                while(!($args instanceof parser\Nil)){
                    $r =  $this->eval($args->car(),$env);
                    if($r instanceof parser\Symbol){
                        $r = $env->lookup($r->val());
                    }
                    $argsArr []= $r;
                    $args = $args->cdr();
                }
                $func = $func->val();
                return $func($this,$argsArr,$env);
                }elseif($func instanceof parser\Func){
                $argsArr = [];
                while(!($args instanceof parser\Nil)){
                    $r =  $this->eval($args->car(),$env);
                    if($r instanceof parser\Symbol){
                        $r = $env->lookup($r->val());
                    }
                    $argsArr []= $r;
                    $args = $args->cdr();
                }
                $func = $func->val();
                return $func($this,$argsArr,$env);
            }
        }elseif($ast instanceof parser\Pair){
            return $ast;
        }elseif($ast instanceof parser\Procedure){
            return $ast;
        }
    }
    function cons($car,$cdr){
        if($cdr instanceof parser\Lists){
            return new parser\Lists($car,$cdr);
        }else{
            return new parser\Pair($car,$cdr);
        }
    }
    function apply($func,$args,$env){
        return $this->eval($this->cons($func,$args),$env);
    }
    function run($ast){
        if(is_array($ast)){
            foreach($ast as $a){
                $ret = $this->eval($a,$this->_toplevel);
            }
        }else{
            $ret = $this->eval($ast,$this->_toplevel);
        }
        return $ret;
    }
}

$le = new parser\Lexer();
$le->set('(define repl (lambda () (
    begin (display ">>> ") 
    (println 
    (eval (string-parse (read-line) ) ))  (repl )  )  )) (repl )');
//$le->set('(begin (display 1) (display "sss") )');
$tokens = $le->tokenize();
//var_dump($tokens);
$ret =  parser\analyzeAst($tokens);
var_dump($ret);
$interp = new Intepreter();
$ret = $interp->run($ret);
var_dump($ret);
