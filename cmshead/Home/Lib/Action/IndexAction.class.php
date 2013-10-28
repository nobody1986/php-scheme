<?php

/**
 * 在rewrite 模式下Index模块的其他方法可能会转入空操作，这是PATH_INFO参数缺陷。
 * 修复办法：
 * 1、在空操作中做单独处理
 * 2、dispaly强制指定模板
 * @author Administrator
 *
 */
class IndexAction extends CommonAction {

    //首页    
    public function index() {
        $this->assign('diary', D('Diary')->where('status=1')->order('add_time DESC')->limit(5)->select());
        $top_art = D('Article')->where('status=1')->order('(sort*50 + add_time) DESC')->limit(10)->select();
        foreach ($top_art as $key => $val) {
            $val['method'] = 'Article/view';
            $top_art[$key] = $this->changurl($val);
        }
        $this->assign('top_art', $top_art);

        $this->assign('slide', D('Photo')->where('status=1')->select()); //幻灯片调用ID  AND tid=5
        //幻灯片调用  临时生成缩略图  使用了缓存
        import('ORG.Util.Image');
        if (!($slide = S('Index_SlideNews'))) {
            $thumbPrefix = array(500, 500); //w h
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/Public/Upload/photo/';
            $slide = D('Photo')->field('id,img,title')->where("status=1 and img!=''")->order('id desc')->limit(10)->select();
            foreach ($slide as $key => $val) {
                $thumbImg = dirname($slide[$key]['img']) ? dirname($slide[$key]['img']) . '/' . $thumbPrefix[0] . '_' . $thumbPrefix[1] . '_' . basename($slide[$key]['img']) : $thumbPrefix[0] . '_' . $thumbPrefix[1] . '_' . $slide[$key]['img'];
                if (!is_file($dir . $thumbImg)) {
                    Image::thumb($dir . $slide[$key]['img'], $dir . $thumbImg, '', $thumbPrefix[0], $thumbPrefix[1]);
                }
                $slide[$key]['img'] = $thumbImg;
            }
            S('Index_SlideNews', $slide, 600); //缓存的写法
        }
        $this->assign('slide', $slide);

        $this->assign('video', D('Video')->where('status=1')->find(1)); //视频调用ID
        $this->seo(C('SITE_NAME'), C('SITE_KEYWORDS'), C('SITE_DESCRIPTION'), 0);
        $this->display();
    }

    //站长日记
    public function diary() {
        $Diary = D("Diary");
        import("ORG.Util.Page");
        $count = $Diary->count();
        $Page = new Page($count, 18);
        $show = $Page->show();
        $list = $Diary->order('add_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->seo('站长日记', C('SITE_KEYWORDS'), C('SITE_DESCRIPTION'), 0);
        $this->display('Index:diary');
    }

    //站内搜索

    public function search() {
        $keyword = $this->_post('keyword', 'strip_tags');
        $where = getSearchMap(array('title' => 'keyword', 'content' => 'keyword'));
        if ($where) {
            $where['_logic'] = 'OR';
            $map['_complex'] = $where;
        }
        $map['status'] = 1;
        $r = D('Article')->where($map)->order('(sort*50 + add_time) DESC')->limit(10)->select();
        foreach ($r as $val) {
            setSearchKey($val['title'], $keyword);
            $val['method'] = 'Article/view';
            $list[] = $this->changurl($val);
        }
        $this->assign('list', $list);
        $this->assign('keyword', $keyword);
        $this->seo('搜索"' . $keyword . '"结果', C('SITE_KEYWORDS'), C('SITE_DESCRIPTION'), 0);
        $this->display('Index:search');
    }

}