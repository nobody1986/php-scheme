<?php

class Spider {

    protected $_config = '';
    protected $_config_parsed = array();
    protected $_urls = array();
    protected $_urls_visit = array();
    protected $_db_connection = null;

    const LEVEL = 2;

    function __construct($config = 'spider.txt') {
        $this->_config = $config;
        $this->_db_connection = new PDO('sqlite:data.db');
    }

    function parseConfigFile() {
        $config_content = file_get_contents($this->_config);
        $tmp = explode("\n", $config_content);
        foreach ($tmp as $t) {
            $t = trim($t);
            $this->_config_parsed [] = explode(' ', $t);
        }
    }

    function walk() {
        $urls_visit = array();
        foreach ($this->_config_parsed as $item) {
            $urls = array($item[0]);
            $level = 0;

            while ($level < self::LEVEL) {
                $urls_tmp = array();
                foreach ($urls as $u) {
                    $urls_tmp +=$this->visit($u, $item);
                    $urls_visit[$u] = 1;
                }
                foreach ($urls_tmp as $k => $l) {
                    if (isset($urls_visit[$l])) {
                        unset($urls_tmp[$k]);
                    }
                }
                $urls = $urls_tmp;
            }
        }
    }

    function fetchurl($src) {
        $content = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $src);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $content = curl_exec($ch);
        curl_close($ch);
        if ($content) {
            return $content;
        }
        return null;
    }

    function getArticleByTitle($title) {
        $sql = 'SELECT count(1) as count FROM ch_article where title=?';
        $stmt = $this->_db_connection->prepare($sql);
        $stmt->execute(array($title));
        $row = $stmt->fetch();
        return $row['count'];
    }
    
    function getCataByName($name) {
        $sql = 'SELECT * FROM ch_category where title=?';
        $stmt = $this->_db_connection->prepare($sql);
        $stmt->execute(array($name));
        $row = $stmt->fetch();
        return $row;
    }
    
    function createArticle($data) {
        $sql = 'insert into  ch_article ';
        $fields = array();
        $values = array();
        foreach($data as $k => $v){
            $fields []= $k;
            $values []= $v;
        }
        $this->_db_connection->beginTransaction();
        $sql = "{$sql} (".  implode(',', $fields).") values (".trim(str_repeat('?,', sizeof($fields)),',').")";
        $stmt = $this->_db_connection->prepare($sql);
        $stmt->execute($values);
        $this->_db_connection->commit();
        return $this->_db_connection->lastInsertId();
    }

        function restoreImg($content, $url) {
            if (preg_match_all('#<img.*?src=[\'""](.+?)[\'""].*?>#i', $content, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $tmp_url = $match[1];
                    $tmp = parse_url($tmp_url);

                    if (empty($tmp['host'])) {
                        $tmp_url = "{$url['scheme']}://{$url['host']}/" . $tmp_url;
                    }
                    $filename = md5($tmp_url) . substr($tmp['path'], strrpos($tmp['path'], '.'));
                    $img_content = $this->fetchurl($tmp_url);
                    file_put_contents('pictures/' . $filename, $img_content);
                    $content = str_replace($match[1], '/pictures/' . $filename, $content);
                }
            }
            return $content;
        }

        function visit($url_main, $config) {
            var_dump($config);
            $content = $this->fetchurl(urldecode($url_main));
            preg_match_all('#<a.+?href\s*=\s*["\'](.+?)["\']#i', $content, $matches, PREG_SET_ORDER);
            $baseurl_parsed = parse_url(urldecode($url_main));
            $ret_urls = array();
            if ($matches) {
                foreach ($matches as $match) {
                    $url = $match[1];
                    $url_parsed = parse_url($url);
                    if (!preg_match($config[2], $url)) {
                        continue;
                    }
                    echo $url . "\n";
                    if (empty($url_parsed['host'])) {
                        $url = "{$baseurl_parsed['scheme']}://{$baseurl_parsed['host']}" . $url;
                        $url_parsed['scheme'] = $baseurl_parsed['scheme'];
                        $url_parsed['host'] = $baseurl_parsed['host'];
                    }
                    $ret_urls [] = $url;
                    //
                    $getcontent = $this->fetchurl($url);
                    $article = str_replace("\n", " ", $getcontent);
                    preg_match($config[3], $article, $titlearray);
                    $cat = $config[5];
                    $data = array();
                    $data['title'] = isset($titlearray[1]) ? $titlearray[1] : '';
                    //
                    preg_match($config[4], $article, $code);
                    if ($data['title'] == '' || !$code) {
                        continue;
                    }
                    //
                    $article = $this->getArticleByTitle($data['title']);
                    if (!empty($article)) {
                        continue;
                    }
                    $clearCode = $code[1];
                    $clearCode = trim($clearCode);
                    //
                    $clearCode = str_replace("&nbsp;", " ", $clearCode);
                    if (empty($clearCode)) {
                        continue;
                    }

                    $clearCode = $this->restoreImg($clearCode, $url_parsed);
                    $cata = $this->getCataByName($cat);
                    $data['content'] = preg_replace('#<a[^>]+?href=[\'"]/.+?[\'"](>.+?)</a>#i', '$1', $clearCode);

                    $data['add_time'] = time();
                    $data['update_time'] = time();
                    $data['description'] = strip_tags($data['content']);
                    $data['description'] = mb_substr($data['description'],0,255,'utf8');
                    $data['adder_id'] = 1;
                    $data['sort'] = 0;
                    $data['apv'] = 0;
                    $data['tid'] = $cata['id'];
                    $data['status'] = 1;

                    $ret = $this->createArticle($data);
                    var_dump($ret);
                }
            }
            return $ret_urls;
        }

    }
    
    $spider = new Spider();
    $spider->parseConfigFile();
    $spider->walk();