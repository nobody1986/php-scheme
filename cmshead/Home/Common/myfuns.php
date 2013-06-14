<?php
 /**
 * 获取七天内星期几的函数
 * @param $daynum 传入星期几，例如：1
 * @return string 返回日期格式，例如：2013-04-06
 */
 function get_weekday($daynum=7){
	if(intval($daynum)===7){
		$daynum = 0;	
	}
	$today_time = time();
	if(intval(date('w',$today_time))===$daynum){
		return (date('Y-m-d',$today_time));
	}
	$onedaytime = 60*60*24;//24小时秒数
	for($i=1;$i<7;$i++){
		if(intval(date('w',$today_time-$i*$onedaytime))===$daynum){
			return (date('Y-m-d',$today_time-$i*$onedaytime));
		}
	}
 }