<?php

namespace \scheme\parser;

class LexerException extends Exception{

}


class Token{
    const Tok_STRING =1;
    const Tok_SYMBOL =2;
    const Tok_INTEGER =3;
    const Tok_FLOAT =4;
    const Tok_CHAR =5;
    const Tok_LP =6;
    const Tok_RP =7;
    const Tok_ADD =8;
    const Tok_SUB =9;
    const Tok_DIV =10;
    const Tok_MUL = 11;
    const Tok_MOD =12;
    const Tok_QUOTE =13;
    const Tok_BOOL = 14;

    protected $_type;
    protected $_content;
    function __construct($type,$c=null){
        $this->_type = $type;
        $this->_content = $c;
    }
}

class Lexer{
    const SPLIT = "\r\n()\t\f";
    const SYMBOL_SPLIT = "()[]{}\",'`;#|\\";
    

    protected $_str ;
    protected $_strlen ;
    protected $_index ;
    function __construct(){

    }


    function set($str){
        $this->_str = $str;
        $this->_strlen = strlen($str);
        $this->_index = 0;
    }

    function tokenize(){}

    function next(){
        for(;$this->_index < $this->_strlen;++$this->_index ){
            if(strpos(self::SPLIT,$this->_str[$this->_index])!==false){
                continue;
            }
            switch($this->_str[$this->_index]){
                case "\"":
                    return $this->parseString();
                    break;
                case "#":
                    if($this->_str[$this->_index+1] =='\\'){
                        $this->_index+=2;
                        return new Token(Token::Tok_CHAR,$this->_str[$this->_index]);
                    }elseif($this->_str[$this->_index+1] =='t' || 
                    $this->_str[$this->_index+1] =='f'){
                        $this->_index+=1;
                        return new Token(Token::Tok_BOOL,$this->_str[$this->_index]=='t');
                    }else{
                        throw new LexerException();
                    }
                    break;
                case "(":
                    return new Token(Token::Tok_LP);
                    break;
                case ")":
                    return new Token(Token::Tok_RP);
                    break;
                case "+":
                    if(strpos(self::SPLIT,$this->_str[$this->_index+1])!==false){
                        $this->_index += 1;
                        return new Token(Token::Tok_ADD);
                    }else{
                        for($n=$this->_index;$n<$this->_strlen;++$n){
                            if(strpos(self::SPLIT,$this->_str[$n])!==false){
                                break;
                            }
                        }
                        $s = substr($this->_str,$this->_index,$n-$this->_index);
                        $this->_index = $n;
                        if(is_float($s)){
                            return new Token(Token::Tok_FLOAT,floatval($s));
                        }elseif(is_numeric($s)){
                            return new Token(Token::Tok_Integer,intval($s));
                        }else{
                            return new Token(Token::Tok_SYMBOL,$s);
                        }
                    }
                    
                    break;
                case "-":
                if(strpos(self::SPLIT,$this->_str[$this->_index+1])!==false){
                        $this->_index += 1;
                        return new Token(Token::Tok_SUB);
                    }else{
                        for($n=$this->_index;$n<$this->_strlen;++$n){
                            if(strpos(self::SPLIT,$this->_str[$n])!==false){
                                break;
                            }
                        }
                        $s = substr($this->_str,$this->_index,$n-$this->_index);
                        $this->_index = $n;
                        if(is_float($s)){
                            return new Token(Token::Tok_FLOAT,floatval($s));
                        }elseif(is_numeric($s)){
                            return new Token(Token::Tok_Integer,intval($s));
                        }else{
                            return new Token(Token::Tok_SYMBOL,$s);
                        }
                    }
                    break;
                case "*":
                    return new Token(Token::Tok_MUL);
                    break;
                case "/":
                    return new Token(Token::Tok_DIV);
                    break;
                case "%":
                    return new Token(Token::Tok_MOD);
                    break;
                case "'":
                    return new Token(Token::Tok_QUOTE);
                    break;
                default:
                    if($this->_str[$this->_index]){}
            }
        }
    }


}