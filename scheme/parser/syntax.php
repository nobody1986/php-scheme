<?php

namespace scheme\parser;


class SyntaxException extends \Exception{

}

/*
abstract class Syntax{
    abstract function __toString();
}

class Atom extends Syntax{
    protected $_value;
    function __construct($value){
        $this->_value = $value;
    }
    function __toString(){
        return $this->_value;
    }
}

class Symbol extends Atom{

}

class Str extends Atom{
    
}
class Integer extends Atom{
    
}

class Double extends Atom{
    
}

class Char extends Atom{
    
}

class Boolean extends Atom{
    
}

class Nil extends Atom{
    
}

class Expr extends Syntax{
    protected $_action;
    protected $_args;
    function __construct($action){
        $this->_action = $action;
        $this->_args = [];
    }
    function add($arg){
        $this->_args []= $arg;
    }
    function __toString(){
        $str ='( '.$this->_action;
        foreach($this->_args as $arg){
            $str .= $arg;
        }
        $str .=' )';
        return $str;
    }
}

class AddExpr extends Expr{
    function __construct(){
        $this->_action = '+';
        $this->_args = [];
    }
}
class SubExpr extends AddExpr{
    protected $_action='-';
}
class DivExpr extends AddExpr{
    protected $_action='/';
}
class MulExpr extends AddExpr{
    protected $_action='*';
}
class ModExpr extends AddExpr{
    protected $_action='%';
}
class QuoteExpr extends AddExpr{
    protected $_action='quote';
}


class Form extends Syntax{
    protected $_args;
    function __construct(){
        $this->_args = [];
    }
    function add($arg){
        $this->_args []= $arg;
    }
    function __toString(){
        $str ='( ';
        foreach($this->_args as $arg){
            $str .= $arg;
        }
        $str .=' )';
        return $str;
    }
}



function analyzeExpr($tokens,&$index = 0){
    $size= sizeof($tokens);
    if($index >= $size){
        return null;
    }
    switch($tokens[$index]->type()){
            case Token::Tok_SYMBOL:
                $ret = new Expr($tokens[$index]->content());
                ++$index;
                break;
            case Token::Tok_LP:
                if($tokens[$index+1]->type() == Token::Tok_BEGIN){
                    $index +=2;
                    $item = analyzeForm($tokens ,$index);
                }else{
                    ++$index;
                    $item = analyzeExpr($tokens ,$index);
                }
                $ret = new Expr($item);
                break;
            case Token::Tok_RP:
                ++$index;
                return $ret;
                break;
            case Token::Tok_ADD:
                $item = AddExpr();
                ++$index;
                break;
            case Token::Tok_SUB:
                $item = SubExpr();
                ++$index;
                break;
            case Token::Tok_DIV:
                $item = DivExpr();
                ++$index;
                break;
            case Token::Tok_MUL:
                $item = MulExpr();
                ++$index;
                break;
            case Token::Tok_MOD:
                $item = ModExpr();
                ++$index;
                break;
            case Token::Tok_QUOTE:
                $item = QuoteExpr();
                ++$index;
                break;
        }
    for(;$index<$size;++$index){
        switch($tokens[$index]->type()){
            case Token::Tok_SYMBOL:
                $item = new Symbol($tokens[$index]->content());
                break;
            case Token::Tok_STRING:
                $item = new Str($tokens[$index]->content());
                break;
            case Token::Tok_INTEGER:
                $item = new Integer($tokens[$index]->content());
                break;
            case Token::Tok_FLOAT:
                $item = new Double($tokens[$index]->content());
                break;
            case Token::Tok_CHAR:
                $item = new Char($tokens[$index]->content());
                break;
            case Token::Tok_LP:
                if($tokens[$index+1]->type() == Token::Tok_BEGIN){
                    $index +=2;
                    $item = analyzeForm($tokens ,$index);
                }else{
                    ++$index;
                    $item = analyzeExpr($tokens ,$index);
                }
                --$index;
                break;
            case Token::Tok_RP:
                ++$index;
                return $ret;
                break;
            case Token::Tok_ADD:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_SUB:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_DIV:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_MUL:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_MOD:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_QUOTE:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_BOOL:
                $item = new Boolean($tokens[$index]->content());
                break;
            
        }
        $ret->add($item);
    }
    return $ret;
}
//function analyzeAtom($tokens,&$index = 0){}

function analyzeForm($tokens,&$index = 0){
    $size= sizeof($tokens);
    if($index >= $size){
        return null;
    }
    $ret =  new Form();
    for(;$index<$size;++$index){
        switch($tokens[$index]->type()){
            case Token::Tok_SYMBOL:
                $item = new Symbol($tokens[$index]->content());
                break;
            case Token::Tok_STRING:
                $item = new Str($tokens[$index]->content());
                break;
            case Token::Tok_INTEGER:
                $item = new Integer($tokens[$index]->content());
                break;
            case Token::Tok_FLOAT:
                $item = new Double($tokens[$index]->content());
                break;
            case Token::Tok_CHAR:
                $item = new Char($tokens[$index]->content());
                break;
            case Token::Tok_LP:
                if($tokens[$index+1]->type() == Token::Tok_BEGIN){
                    $index += 2;
                    $item = analyzeForm($tokens ,$index);
                }else{
                    ++$index;
                    $item = analyzeExpr($tokens ,$index);
                }
                --$index;
                break;
            case Token::Tok_RP:
                ++$index ;
                return $ret;
                break;
            case Token::Tok_ADD:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_SUB:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_DIV:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_MUL:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_MOD:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_QUOTE:
                $item = analyzeExpr($tokens ,$index);
                break;
            case Token::Tok_BOOL:
                $item = analyzeExpr($tokens ,$index);
                break;
        }
        $ret->add($item);
    }
    return $ret;
        
}

*/

abstract class Object{
    protected $_value;
    abstract function __toString();
    function val(){
        return $this->_value;
    }
}
class Atomic extends Object{
    protected $_value;
    function __construct($value){
        $this->_value = $value;
    }
    function __toString(){
        return ''.$this->_value;
    }
}
class Nil extends Atomic{
    private static $_instance;
    function __construct(){
        $this->_value = '()';
    }
    static function instance(){
        if(empty(self::$_instance)){
            self::$_instance = new Nil();
        }
        return self::$_instance;
    }
}
class Symbol extends Atomic{}
class Integer extends Atomic{}
class Double extends Atomic{}
class Char extends Atomic{}
class Str extends Atomic{}
class Boolean extends Atomic{
    function __toString(){
        return $this->_value?"#t":"#f";
    }
}

class Pair extends Object{
    protected $_car;
    protected $_cdr;
    function __construct($car,$cdr){
        $this->_car = $car;
        $this->_cdr = $cdr;
    }
    function __toString(){
        return $this->_car.' . '.$this->_cdr;
    }
    function car(){
        return $this->_car;
    }
    function cdr(){
        return $this->_cdr;
    }

    function setCar($car){
          $this->_car = $car;
    }
    function setCdr($cdr){
          $this->_cdr = $cdr;
    }
}
class Lists extends Pair{
    function __toString(){
        if($this->_cdr instanceof Nil){
            return $this->_car . '';
        }else{
            return $this->_car.' '.$this->_cdr;
        }
        
    }
}
class Lambda extends Object{
    protected $_code;
    protected $_env;
    protected $_args;
    function __construct($code,$env){
        $this->_code = $code->cdr()->cdr()->car();
        $this->_env = $env;
        $this->_args = [];
        $args = $code->cdr()->car();
        while(!($args instanceof Nil)){
            $this->_args []= $args->car()->val();
            $args = $args->cdr();
        }
    }
    function call($args){
        $env = new Env($this->_env);
        foreach($this->_args as $k => $v){
            $env->set($v,$args[$k]);
        }
        return $env;
    }
    function code(){
        return $this->_code;
    }

    function __toString(){
        return "<lambda>";
    }
}

class Env extends Object{
    protected $_env;
    protected $_uplevel;
    function __construct($uplevel){
        $this->_uplevel = $uplevel;
        $this->_env = [];
    }
    function set($name,$value){
        $this->_env[$name] = $value;
    }
    function lookup($name){
        if(!empty($this->_env[$name])){
            return $this->_env[$name];
        }else{
            if(empty($this->_uplevel)){
                return null;
            }else{
                return $this->_uplevel->lookup($name);
            }
        }
    }
    function __toString(){
        return "<Env>";
    }
}

class Procedure extends Object{
    protected $_name;
    function __construct($name,$value){
        $this->_name = $name;
        $this->_value = $value;
    }
    function __toString(){
        return sprintf("<Procedure %s>",$this->_name);
    }
}

class Func extends Object{
    function __construct($value){
        $this->_value = $value;
    }
    function __toString(){
        return sprintf("<Func>");
    }
}
class Callcc extends Object{
    function __toString(){
        return "<call-with-current-continuation>";
    }
}


function analyzeAst($tokens){
    $stack = [];
    foreach($tokens as $token){
        switch($token->type()){
            case Token::Tok_SYMBOL:
                $item = new Symbol($token->content());
                break;
            case Token::Tok_STRING:
                $item = new Str($token->content());
                break;
            case Token::Tok_INTEGER:
                $item = new Integer($token->content());
                break;
            case Token::Tok_FLOAT:
                $item = new Double($token->content());
                break;
            case Token::Tok_CHAR:
                $item = new Char($token->content());
                break;
            case Token::Tok_LP:
                $item = null;
                break;
            case Token::Tok_RP:
                $ret = Nil::instance();
                while(($t = array_pop($stack))!=null){
                    $ret = new Lists($t,$ret);
                }
                array_push($stack,$ret);
                goto nopush;
                break;
            case Token::Tok_ADD:
                $item = new Symbol('+');
                break;
            case Token::Tok_SUB:
                $item = new Symbol('-');
                break;
            case Token::Tok_DIV:
                $item = new Symbol('/');
                break;
            case Token::Tok_MUL:
                $item = new Symbol('*');
                break;
            case Token::Tok_MOD:
                $item = new Symbol('%');
                break;
            case Token::Tok_QUOTE:
                $item = new Symbol('quote');
                break;
            case Token::Tok_BEGIN:
                $item = new Symbol('begin');
                break;
            case Token::Tok_BOOL:
                $item = new Boolean($token->content());
                break;
        }
        
        array_push($stack,$item);
        nopush:
        if(sizeof($stack) >1 
                && $stack[sizeof($stack)-2] instanceof Symbol
                && $stack[sizeof($stack)-2]->val()=='quote'
                &&  $stack[sizeof($stack)-1] !== null 
                  ){
                    $val = new Lists(array_pop($stack),Nil::instance());
                    $ret = new Lists(array_pop($stack),$val);
                    array_push($stack,$ret);
                }
    }
    return $stack;
}