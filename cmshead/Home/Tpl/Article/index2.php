模板：index2.php<br />

<include file="Public:header" />
    <div class="wrap_left">
        <volist name="list" id="vo">
        <div class="art_block">
            <div class="art_left"></div>
            <if condition="$vo.sort gt 0">
			<div class="art_center art_top">
			<else />
			<div class="art_center">
			</if>
                <div class="left"><empty name="vo.img"><img src="__PUBLIC__/images/noimg.jpg" /><else/><img src="__PUBLIC__/Upload/Article/{$vo.img}" /></empty></div>
                <div class="right">
                    <h1><a href="{$vo.url}">{$vo.title|msubstr=0,30}</a></h1>
                    <div>[<a href="__APP__/article/index/id/{$vo.tid|idsfirst}">{$vo.tid|getCategoryName}</a>]  点击次数：{$vo.apv}次  发表时间：{$vo.add_time|date='Y-m-d H:i:s',###}</div>
                    <p>{$vo.content|strip_tags|str_replace='　', '',###|msubstr=0,300}</p>
                </div>
            </div>
        </div>          
        </volist><!--文档列表-->
        <div class="page">{$page}</div>
    </div>        
<include file="Public:footer" />
