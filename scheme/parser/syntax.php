<?php

namespace scheme\parser;


class SyntaxException extends \Exception{

}


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
    for($i = $index;$i<$size;++$i){
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
                $item = analyzeExpr($tokens ,$index);
                if($tokens[$index+1]->type() == Token::Tok_BEGIN){
                    $index +=2;
                    $item = analyzeForm($tokens ,$index);
                }
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
                $item = analyzeExpr($tokens ,$index);
                if($tokens[$index+1]->type() == Token::Tok_BEGIN){
                    $index += 2;
                    $item = analyzeForm($tokens ,$index);
                }
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