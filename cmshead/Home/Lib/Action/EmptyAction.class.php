<?php/* 空类 */class EmptyAction extends Action {    //空模块  /abc    public function index() {        $this->dispath();    }    //空操作 /abc/xy    public function _empty() {        $this->dispath();    }    public function dispath() {        $url = $_SERVER['REQUEST_URI'];  //以前用的PATH_INFO，有缺陷	        $rewrite = urldecode(trim($url, '/'));        $exp = explode('/', strtolower($rewrite));        if ($exp[0] == 'index' && count($exp) > 1 && $exp[1] != 'index') {            R(ucfirst($exp[0]) . '/' . $exp[1]);            exit;        }        $r = D("Router")->where("rewrite='" . $rewrite . "'")->getField('url');        if ($r) {            //形如id/2/a/1/b/B/c/C的 参数转化为数组            $exp = explode('/', $r);            $vars = array();            for ($i = 2, $n = count($exp) - 1; $i < $n; $i++) {                $vars[$exp[$i]] = $exp[$i + 1];                $i++;            }            R(ucfirst($exp[0]) . '/' . $exp[1], $vars);        } else {            switch ($exp[0]) {                case 'topic':                    $a = D('Article')->where('rewrite="'.$exp[1].'"')->find();                    if (empty($a)) {                        $this->redirect("index/index");                        exit();                    }                    $vars = array();                    for ($i = 2, $n = count($exp) - 1; $i < $n; $i++) {                        $vars[$exp[$i]] = $exp[$i + 1];                        $i++;                    }                    $vars['id'] = $a['id'];                    $this->redirect('Article/view', $vars);                    break;                case 'node':                    $vars = array();                    for ($i = 2, $n = count($exp) - 1; $i < $n; $i++) {                        $vars[$exp[$i]] = $exp[$i + 1];                        $i++;                    }                    $r = D("Category")->where("title='" . $exp[1] . "'")->find();                    if (empty($r)) {                        $this->redirect("index/index");                        exit();                    }                    $vars['id'] = $r['id'];                    $this->redirect('Article/index', $vars);                    break;                default:                    $this->redirect("index/index");            }        }    }}