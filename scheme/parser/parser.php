<?php

namespace scheme\parser;

class LexerException extends \Exception{

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
    const Tok_BEGIN = 15;

    protected $_type;
    protected $_content;
    function __construct($type,$c=null){
        $this->_type = $type;
        $this->_content = $c;
    }

    function type(){
        return $this->_type;
    }
    function content(){
        return $this->_content;
    }
}

class Lexer{
    const SPLIT = "\r\n()\t\f ";
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

    function tokenize(){
        $tokens = $this->split();
        $ret  = [];
        foreach($tokens as $token){
            if(is_float($token)){
                $ret []= new Token(Token::Tok_FLOAT,floatval($token));
            }elseif(ctype_digit($token)){
                $ret []= new Token(Token::Tok_INTEGER,intval($token));
            }elseif($token[0] == '"'){
                $ret []= new Token(Token::Tok_STRING,stripslashes(substr($token,1,strlen($token)-2)));
            }elseif($token[0] == '#'&& $token[1] == '\\'){
                $ret []= new Token(Token::Tok_CHAR,$token);
            }elseif($token[0] == '#'&& ($token[1] == 't' || $token[1] == 'f')){
                $ret []= new Token(Token::Tok_BOOL,$token[1] == 't');
            }elseif($token == '\''){
                $ret []= new Token(Token::Tok_QUOTE);
            }
            elseif($token == '('){
                $ret []= new Token(Token::Tok_LP);
            }
            elseif($token == ')'){
                $ret []= new Token(Token::Tok_RP);
            }
            elseif($token == '+'){
                $ret []= new Token(Token::Tok_ADD);
            }elseif($token == '-'){
                $ret []= new Token(Token::Tok_SUB);
            }elseif($token == '*'){
                $ret []= new Token(Token::Tok_MUL);
            }elseif($token == '/'){
                $ret []= new Token(Token::Tok_DIV);
            }elseif($token == '%'){
                $ret []= new Token(Token::Tok_MOD);
            }elseif(strtolower($token) == 'begin'){
                $ret []= new Token(Token::Tok_BEGIN);
            }else{
                $ret []= new Token(Token::Tok_SYMBOL,$token);
            }
        }
        return $ret;
    }
    function split(){
        $tokens = []; 
            for(;$this->_index < $this->_strlen;++$this->_index ){
                if( $this->_str[$this->_index] == '\''){
                    $tokens []= substr($this->_str, $this->_index,1);
                }elseif(strpos(self::SPLIT,$this->_str[$this->_index])!==false){
                    if($this->_str[$this->_index] == '('||$this->_str[$this->_index]==')'){
                        $tokens []= substr($this->_str, $this->_index,1);
                    }
                }else{
                    if($this->_str[$this->_index]=='"'){
                        for($n=$this->_index+1;$n<$this->_strlen;++$n){
                            if($this->_str[$n]=='\\'){
                                ++$n;
                                continue;
                            }
                            if($this->_str[$n]=='"'){
                                $tokens []= substr($this->_str,$this->_index,$n - $this->_index+1);
                                $this->_index  = $n;
                                break;
                            }
                        }
                    }else{
                        for($n=$this->_index;$n<$this->_strlen;++$n){
                            if(strpos(self::SPLIT,$this->_str[$n])!==false){
                                $tokens []= substr($this->_str,$this->_index,$n - $this->_index);
                                $this->_index  = $n-1;
                                break;
                            }
                        }
                    }
                    
                }
            }
            return $tokens;
    }

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

$le = new Lexer();
$le->set('(define name (lambda (x) (string-length "xj\n\"a23j"))');
$tokens = $le->tokenize();
include('syntax.php');
var_dump($tokens);
$ret = analyzeForm($tokens);
// /var_dump($ret);
