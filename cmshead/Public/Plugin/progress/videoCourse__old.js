//=============ajax封装========================================
var request = false;
var component = null;
function createRequest() {
	if (window.ActiveXObject) {
		request = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
		}
	}
	if (!request) {
		alert("Error initializing XMLHttpRequest!");
	}
}

function action(url, param, component) {
	this.component = component;
	createRequest();
	request.open("POST", url, true);
	request.onreadystatechange = action_cl;
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	request.send(param);
}
function action_cl() {
	if (request.readyState == 4) {
		document.getElementById(component).innerHTML = "";
		document.getElementById(component).innerHTML = request.responseText;
	}
}
//=============ajax封装结束========================================
var id ;
var start;
var current;
var video_read;
var c_desc;
function answerQuestion(){
	var objs= document.getElementsByName("answer");
	var answer1;
	for(var i=0;i<objs.length;i++){
				if(objs[i].checked== true )
			{
				answer1 = objs[i].value;
				break;
			}
	}
	var param="course.course_Id="+id+"&answer="+answer1;
	 action("answerQuestion.action", param,"questionDiv");
}
function ccriList(){
	var param="course.course_Id="+id+"&pageNow=0&pageSize=5";	
	action("ccri_list.action", param,"ccri");
}
function addCri(){
	var param="course.course_Id="+id+"&pageNow="+document.getElementById("pageNow").value+
	"&pageSize="+document.getElementById("pageSize").value+"&cri.cri_content="+
	document.getElementById("cri_content").value;
	action("ccri_add.action", param,"ccri");
}
function page(i){
	var param="course.course_Id="+id+"&pageNow="+i+"&pageSize=" +document.getElementById("pageSize").value;	
	action("ccri_list.action", param,"ccri");
}
function deleteCri(id1){
		var param="course.course_Id="+id+"&cri.cri_id="+id1+"&pageNow="+document.getElementById("pageNow").value+"&pageSize=" +document.getElementById("pageSize").value;	
		action("ccri_delete.action", param,"ccri");
}

function hiddenDesc(){
	if(c_desc.length >80) 
	document.getElementById("c_descDiv").innerHTML=c_desc.substring(0,80)+"..."+"<a href='javascript:showDesc()'>&gt;&gt;查看详情</a>";
	else
	document.getElementById("c_descDiv").innerHTML=c_desc+"<a href='javascript:showDesc()'>&gt;&gt;查看详情</a>";
}

function showDesc(){
	document.getElementById("c_descDiv").innerHTML=c_desc+"	<a href='javascript:hiddenDesc()'>&gt;&gt;隐藏详情</a>";
}
//--------------------------------------------------------
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
function action1(url, param, comp) {
	this.comp = comp;
	createReq();
	req.open("POST", url, true);
	req.onreadystatechange = action_cl1;
	req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");
	req.send(param);
}
function action_cl1() {
	if (req.readyState == 4) {
		document.getElementById(comp).innerHTML = "";
		document.getElementById(comp).innerHTML = req.responseText;
	}
}
function submitNowTime(){
	var obj = document.getElementById("mplayer");
	if(obj.CurrentPosition>current)
		current =obj.CurrentPosition;
	
	if(start<current&&!isRead ){
		var url = "updateVideoTime.action";
		var param = "time="+(current)+"&course.course_Id="+id+"&scourse.video_read="+video_read;			
		action1(url, param,"mpdiv");
		//start= start+3;
		}
		window.setTimeout( submitNowTime ,1000);
}
function submitPlayTime(current){
	if(start<current&&!isRead ){
		var url = "updateVideoTime.action";
		var param = "time="+(current)+"&course.course_Id="+id+"&scourse.video_read="+video_read;
		action1(url, param,"mpdiv");
		//start= start+3;
		}
		window.setTimeout( submitNowTime ,1000);
}
var currentTime = -999;

function updatePlayTime(current)
{
	if(currentTime<0){
		currentTime=current;
		submitUpdatePlayTime();		
	}
	currentTime=current;
}

function submitUpdatePlayTime(){  
	//alert("----Flash video player ... "+ currentTime+"  --start-- "+start+" **isRead**  "+isRead);  	
	if(start<currentTime &&!isRead ){
			
		var url = "updateVideoTime.action";
		var param = "time="+currentTime+"&course.course_Id="+id+"&scourse.video_read="+video_read;
		action1(url, param,"mpdiv");
				
	}	
	window.setTimeout(submitUpdatePlayTime,1000);
}

function flashPlayerReady()
{
	mplayer.recoverProgress(start);
}