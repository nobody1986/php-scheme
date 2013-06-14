/**
 * 此js为__PUBLIC__/Plugin/progress/videoPlayer.swf的配套js。flash播放器内部会自动调用这里的函数。
**/
var id=0,start=0,current=0,isRead=0,isalt=0;
var video_read='';
//=============ajax封装========================================
var req = false;
var comp = null;
function createReq() {
	if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		if (window.XMLHttpRequest) {
			req = new XMLHttpRequest();
		}
	}
	if (!req) {
		alert("Error initializing XMLHttpRequest!");
	}
}
function action(url, param, comp) {
	this.comp = comp;
	createReq(); 
	req.open("POST", url, true);
	req.onreadystatechange = action_cl;
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	req.send(param);
}
function action_cl() {
	if (req.readyState == 4) {
		if(req.responseText.indexOf("完成")>-1){
			isRead = 1;	
		}
		document.getElementById(comp).innerHTML = req.responseText;
	}
}
var currentTime = -999;
function updatePlayTime(current)
{
	if(isRead) return;
	if(currentTime<0){
		currentTime=current;
		submitUpdatePlayTime();
	}else{
		currentTime=current;
	}
}
function submitUpdatePlayTime(){  
	if(isRead) return;
	if(isalt==0 && start>0){
		isalt = 1;
		alert("欢迎你继续学习，已学"+currentTime+"秒，已记录"+start+"秒！");
	}
	if(start<=currentTime){
		var url = _ajaxURL;
		var param = "time="+currentTime+"&id="+id+"&no_cache="+Math.random();
		action(url, param, "mpdiv");
	}	
	window.setTimeout(submitUpdatePlayTime,5000);
}
//flash视频初始从上次的进度开始播放，靠此函数起作用。
function flashPlayerReady()
{
	if(isRead) return;
	mplayer.recoverProgress(start);
}