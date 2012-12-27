<?php
include 'phpcc/phpcc.php';

class Scheme {
    protected $parser;
    protected $stack = [];
    
    function __construct() {
        $tokens = [
            '(', ')','-',
            'd'=>'[1-9][0-9]*',
            'f'=>'[0-9]+\.[0-9]+',
            'sp'=>'\s+',
            'atom'=>'[A-Za-z#_\-\?!+\*/%]+',
            'string' => '[\'"].+?[^\\][\'"]',
        ];
        $rules = [
            'Exp' => [
                [['(','Form',')'], true, 'Eval'],
            ],
            'Form' =>[
                [['Form','Form'], false],
                [['Atom'],false]
            ],
            'Atom' => [
                [[['|','atom']],false],
                [[['|','Number']],false],
                [[['|','string']],false],
            ],
            'Number' => [
                //[[['|','Scala','Const']], false],
                [[['|','Scala']], false],
                [['-', 'Number'], true, 'Reverse'],
            ],
            'Scala' => [
                [[['|','d','f']], true],
            ],
            /*'Const' => [
                [[['|','pi','e']], true],
            ]*/
        ];
        $lexer = new phpcc\Lexer($tokens);
        $parser = new phpcc\Parser();
        $parser->setLexer($lexer);
        $parser->init($rules);
        $parser->setSkipTokens(['sp']);
        $this->parser = $parser;
    }
    
    function _calc($rule, $items) {
        if ($rule == 'Scala') {
            switch ($items[0][0]) {
            case 'd':
                $this->stack[]= intval($items[0][1]);
                break;
            case 'f':
                $this->stack[]= floatval($items[0][1]);
                break;
            }
        } else {
            $need_push = true;
            switch ($rule) {
            case 'Reverse':
                $d1 = array_pop($this->stack);
                $r = -$d1;
                break;
            case 'Eval':
                $need_push = false;
                var_dump($this->stack);
                break;
            default:
                $need_push = true;
                $d1 = array_pop($this->stack);
                var_dump($d1);
                $r = $d1;
                break;
            }
            if ($need_push) {
                array_push($this->stack, $r);
            }
        }
    }
    
    function calc($expression) {
        $this->stack = [];
        $this->parser->parse($expression, [$this, '_calc']);
        return $this->stack[0];
    }
    
    function tree($expression) {
        $this->parser->printTree($expression);
    }
}

/////////////////////////////////////////////////////////////

    $calc = new Scheme();
    while ($exp = fgets(STDIN)) {
        try {
            $result = $calc->calc($exp);
            echo "$result\n";
        } catch (Exception $e) {
            echo $e->getMessage(), "\n";
        }
    }