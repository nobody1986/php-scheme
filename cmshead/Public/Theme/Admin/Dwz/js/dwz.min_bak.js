
var DWZ={
keyCode:{
ENTER:13,ESC:27,END:35,HOME:36,
SHIFT:16,TAB:9,
LEFT:37,RIGHT:39,UP:38,DOWN:40,
DELETE:46,BACKSPACE:8},
statusCode:{ok:200,error:300,timeout:301},
ui:{sbar:true},
frag:{},
_msg:{},
_set:{
loginUrl:"",
loginTitle:"",
debug:false},
msg:function(key,args){
var _format=function(str,args){
args=args||[];
var result=str
for(var i=0;i<args.length;i++){
result=result.replace(new RegExp("\\{"+i+"\\}","g"),args[i]);}
return result;}
return _format(this._msg[key],args);},
debug:function(msg){
if(this._set.debug)alert(msg);},
loadLogin:function(){
if($.pdialog&&DWZ._set.loginTitle){
$.pdialog.open(DWZ._set.loginUrl,"login",DWZ._set.loginTitle,{mask:true,width:400,height:200});}else{
window.location=DWZ._set.loginUrl;}},
jsonEval:function(json){
try{
return eval('('+json+')');}catch(e){
return{};}},
ajaxError:function(xhr,ajaxOptions,thrownError){
if(alertMsg){
alertMsg.error("<p>Http status: "+xhr.status+" "+xhr.statusText+"</p>");}
DWZ.debug("Http status: "+xhr.status+" "+xhr.statusText+"\najaxOptions: "+ajaxOptions+"\nthrownError:"+thrownError);
DWZ.debug(xhr.responseText);},
ajaxDone:function(json){
if(json.statusCode==DWZ.statusCode.error){
if(json.message&&alertMsg)alertMsg.error(json.message);}else if(json.statusCode==DWZ.statusCode.timeout){
if(json.message&&alertMsg)alertMsg.error(json.message,{okCall:DWZ.loadLogin});
else loadLogin();}else{
if(json.message&&alertMsg)alertMsg.correct(json.message);};},
init:function(pageFrag,options){
var op=$.extend({
loginUrl:"login.html",loginTitle:null,callback:null,debug:false,
statusCode:{}},options);
this._set.loginUrl=op.loginUrl;
this._set.loginTitle=op.loginTitle;
this._set.debug=op.debug;
$.extend(DWZ.statusCode,op.statusCode);
jQuery.ajax({
type:'GET',
url:pageFrag,
dataType:'xml',
timeout:50000,
cache:false,
error:function(xhr){
alert('Error loading XML document: '+pageFrag+"\nHttp status: "+xhr.status+" "+xhr.statusText);},
success:function(xml){
$(xml).find("_PAGE_").each(function(){
var pageId=$(this).attr("id");
if(pageId)DWZ.frag[pageId]=$(this).text();});
$(xml).find("_MSG_").each(function(){
var id=$(this).attr("id");
if(id)DWZ._msg[id]=$(this).text();});
if(jQuery.isFunction(op.callback))op.callback();}});}};(function($){
$.setRegional=function(key,value){
if(!$.regional)$.regional={};
$.regional[key]=value;};
$.fn.extend({
loadUrl:function(url,data,callback){
var $this=$(this);
if($.fn.xheditor){
$("textarea.editor",$this).xheditor(false);}
$.ajax({
type:'POST',
url:url,
cache:false,
data:data,
success:function(html){
var json=DWZ.jsonEval(html);
if(json.statusCode==DWZ.statusCode.timeout){
alertMsg.error(DWZ.msg("sessionTimout"),{okCall:function(){
DWZ.loadLogin();}});}if(json.statusCode==DWZ.statusCode.error){
if(json.message)alertMsg.error(json.message);}else{
$this.html(html).initUI();
if($.isFunction(callback))callback();}},
error:DWZ.ajaxError});},
initUI:function(){
return this.each(function(){
if($.isFunction(initUI))initUI(this);});},
layoutH:function($refBox){
return this.each(function(){
var $this=$(this);
if(!$refBox)$refBox=("dialog"==$this.attr("layoutType")&&$.pdialog)?$.pdialog.getCurrent().find(".dialogContent"):$("#container .tabsPageContent");
var iRefH=$refBox.height();
var iLayoutH=parseInt($this.attr("layoutH"));
$this.height(iRefH-iLayoutH>50?iRefH-iLayoutH:50);});},
hoverClass:function(className){
var _className=className||"hover";
return this.each(function(){
$(this).hover(function(){
$(this).addClass(_className);},function(){
$(this).removeClass(_className);});});},
focusClass:function(className){
var _className=className||"textInputFocus";
return this.each(function(){
$(this).focus(function(){
$(this).addClass(_className);}).blur(function(){
$(this).removeClass(_className);});});},
inputAlert:function(){
return this.each(function(){
var $this=$(this);
function getAltBox(){
return $this.parent().find("label.alt");}
function altBoxCss(opacity){
var position=$this.position();
return{
width:$this.width(),
top:position.top+'px',
left:position.left+'px',
opacity:opacity||1}}
if(!$this.val()&&getAltBox().size()<1){
if(!$this.attr("id"))$this.attr("id",$this.attr("name")+"_"+Math.round(Math.random()*10000));
$('<label class="alt" for="'+$this.attr("id")+'">'+$this.attr("alt")+'</label>').appendTo($this.parent()).css(altBoxCss(1));}
$this.focus(function(){
getAltBox().css(altBoxCss(0.3));}).blur(function(){
if(!$(this).val())getAltBox().show().css("opacity",1);}).keydown(function(){
getAltBox().hide();});});},
isTag:function(tn){
if(!tn)return false;
return $(this)[0].tagName.toLowerCase()==tn?true:false;}});
$.extend(String.prototype,{
isPositiveInteger:function(){
return(new RegExp(/^[1-9]\d*$/).test(this));},
isInteger:function(){
return(new RegExp(/^\d+$/).test(this));},
isNumber:function(value,element){
return(new RegExp(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/).test(this));},
trim:function(){
return this.replace(/(^\s*)|(\s*$)|\r|\n/g,"");},
trans:function(){
return this.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&quot;/g,'"');},
replaceAll:function(os,ns){
return this.replace(new RegExp(os,"gm"),ns);},
replaceTm:function($data){
if(!$data)return this;
return this.replace(RegExp("({[A-Za-z_]+[A-Za-z0-9_]*})","g"),function($1){
return $data[$1.replace(/[{}]+/g,"")];});},
replaceTmById:function(_box){
var $parent=_box||$(document);
return this.replace(RegExp("({[A-Za-z_]+[A-Za-z0-9_]*})","g"),function($1){
var $input=$parent.find("#"+$1.replace(/[{}]+/g,""));
return $input.size()>0?$input.val():$1;});},
isFinishedTm:function(){
return !(new RegExp("{[A-Za-z_]+[A-Za-z0-9_]*}").test(this));},
skipChar:function(ch){
if(!this||this.length===0){return '';}
if(this.charAt(0)===ch){return this.substring(1).skipChar(ch);}
return this;},
isValidPwd:function(){
return(new RegExp(/^([_]|[a-zA-Z0-9]){6,32}$/).test(this));},
isValidMail:function(){
return(new RegExp(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/).test(this.trim()));},
isSpaces:function(){
for(var i=0;i<this.length;i+=1){
var ch=this.charAt(i);
if(ch!=' '&&ch!="\n"&&ch!="\t"&&ch!="\r"){return false;}}
return true;},
isPhone:function(){
return(new RegExp(/(^([0-9]{3,4}[-])?\d{3,8}(-\d{1,6})?$)|(^\([0-9]{3,4}\)\d{3,8}(\(\d{1,6}\))?$)|(^\d{3,8}$)/).test(this));},
isUrl:function(){
return(new RegExp(/^[a-zA-z]+:\/\/([a-zA-Z0-9\-\.]+)([-\w .\/?%&=:]*)$/).test(this));},
isExternalUrl:function(){
return this.isUrl()&&this.indexOf("://"+document.domain)==-1;}});})(jQuery);
function Map(){
this.elements=new Array();
this.size=function(){
return this.elements.length;}
this.isEmpty=function(){
return(this.elements.length<1);}
this.clear=function(){
this.elements=new Array();}
this.put=function(_key,_value){
this.remove(_key);
this.elements.push({key:_key,value:_value});}
this.remove=function(_key){
try{
for(i=0;i<this.elements.length;i++){
if(this.elements[i].key==_key){
this.elements.splice(i,1);
return true;}}}catch(e){
return false;}
return false;}
this.get=function(_key){
try{
for(i=0;i<this.elements.length;i++){
if(this.elements[i].key==_key){return this.elements[i].value;}}}catch(e){
return null;}}
this.element=function(_index){
if(_index<0||_index>=this.elements.length){return null;}
return this.elements[_index];}
this.containsKey=function(_key){
try{
for(i=0;i<this.elements.length;i++){
if(this.elements[i].key==_key){
return true;}}}catch(e){
return false;}
return false;}
this.values=function(){
var arr=new Array();
for(i=0;i<this.elements.length;i++){
arr.push(this.elements[i].value);}
return arr;}
this.keys=function(){
var arr=new Array();
for(i=0;i<this.elements.length;i++){
arr.push(this.elements[i].key);}
return arr;}}(function(){
var MONTH_NAMES=new Array('January','February','March','April','May','June','July','August','September','October','November','December','Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
var DAY_NAMES=new Array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sun','Mon','Tue','Wed','Thu','Fri','Sat');
function LZ(x){return(x<0||x>9?"":"0")+x}
function formatDate(date,format){
format=format+"";
var result="";
var i_format=0;
var c="";
var token="";
var y=date.getYear()+"";
var M=date.getMonth()+1;
var d=date.getDate();
var E=date.getDay();
var H=date.getHours();
var m=date.getMinutes();
var s=date.getSeconds();
var yyyy,yy,MMM,MM,dd,hh,h,mm,ss,ampm,HH,H,KK,K,kk,k;
var value={};
if(y.length<4){y=""+(y-0+1900);}
value["y"]=""+y;
value["yyyy"]=y;
value["yy"]=y.substring(2,4);
value["M"]=M;
value["MM"]=LZ(M);
value["MMM"]=MONTH_NAMES[M-1];
value["NNN"]=MONTH_NAMES[M+11];
value["d"]=d;
value["dd"]=LZ(d);
value["E"]=DAY_NAMES[E+7];
value["EE"]=DAY_NAMES[E];
value["H"]=H;
value["HH"]=LZ(H);
if(H==0){value["h"]=12;}
else if(H>12){value["h"]=H-12;}
else{value["h"]=H;}
value["hh"]=LZ(value["h"]);
if(H>11){value["K"]=H-12;}else{value["K"]=H;}
value["k"]=H+1;
value["KK"]=LZ(value["K"]);
value["kk"]=LZ(value["k"]);
if(H>11){value["a"]="PM";}
else{value["a"]="AM";}
value["m"]=m;
value["mm"]=LZ(m);
value["s"]=s;
value["ss"]=LZ(s);
while(i_format<format.length){
c=format.charAt(i_format);
token="";
while((format.charAt(i_format)==c)&&(i_format<format.length)){
token+=format.charAt(i_format++);}
if(value[token]!=null){result+=value[token];}
else{result+=token;}}
return result;}
function _isInteger(val){
return(new RegExp(/^\d+$/).test(val));}
function _getInt(str,i,minlength,maxlength){
for(var x=maxlength;x>=minlength;x--){
var token=str.substring(i,i+x);
if(token.length<minlength){return null;}
if(_isInteger(token)){return token;}}
return null;}
function parseDate(val,format){
val=val+"";
format=format+"";
var i_val=0;
var i_format=0;
var c="";
var token="";
var token2="";
var x,y;
var now=new Date();
var year=now.getYear();
var month=now.getMonth()+1;
var date=1;
var hh=now.getHours();
var mm=now.getMinutes();
var ss=now.getSeconds();
var ampm="";
while(i_format<format.length){
c=format.charAt(i_format);
token="";
while((format.charAt(i_format)==c)&&(i_format<format.length)){
token+=format.charAt(i_format++);}
if(token=="yyyy"||token=="yy"||token=="y"){
if(token=="yyyy"){x=4;y=4;}
if(token=="yy"){x=2;y=2;}
if(token=="y"){x=2;y=4;}
year=_getInt(val,i_val,x,y);
if(year==null){return 0;}
i_val+=year.length;
if(year.length==2){
if(year>70){year=1900+(year-0);}
else{year=2000+(year-0);}}}else if(token=="MMM"||token=="NNN"){
month=0;
for(var i=0;i<MONTH_NAMES.length;i++){
var month_name=MONTH_NAMES[i];
if(val.substring(i_val,i_val+month_name.length).toLowerCase()==month_name.toLowerCase()){
if(token=="MMM"||(token=="NNN"&&i>11)){
month=i+1;
if(month>12){month-=12;}
i_val+=month_name.length;
break;}}}
if((month<1)||(month>12)){return 0;}}else if(token=="EE"||token=="E"){
for(var i=0;i<DAY_NAMES.length;i++){
var day_name=DAY_NAMES[i];
if(val.substring(i_val,i_val+day_name.length).toLowerCase()==day_name.toLowerCase()){
i_val+=day_name.length;
break;}}}else if(token=="MM"||token=="M"){
month=_getInt(val,i_val,token.length,2);
if(month==null||(month<1)||(month>12)){return 0;}
i_val+=month.length;}else if(token=="dd"||token=="d"){
date=_getInt(val,i_val,token.length,2);
if(date==null||(date<1)||(date>31)){return 0;}
i_val+=date.length;}else if(token=="hh"||token=="h"){
hh=_getInt(val,i_val,token.length,2);
if(hh==null||(hh<1)||(hh>12)){return 0;}
i_val+=hh.length;}else if(token=="HH"||token=="H"){
hh=_getInt(val,i_val,token.length,2);
if(hh==null||(hh<0)||(hh>23)){return 0;}
i_val+=hh.length;}
else if(token=="KK"||token=="K"){
hh=_getInt(val,i_val,token.length,2);
if(hh==null||(hh<0)||(hh>11)){return 0;}
i_val+=hh.length;}else if(token=="kk"||token=="k"){
hh=_getInt(val,i_val,token.length,2);
if(hh==null||(hh<1)||(hh>24)){return 0;}
i_val+=hh.length;hh--;}else if(token=="mm"||token=="m"){
mm=_getInt(val,i_val,token.length,2);
if(mm==null||(mm<0)||(mm>59)){return 0;}
i_val+=mm.length;}else if(token=="ss"||token=="s"){
ss=_getInt(val,i_val,token.length,2);
if(ss==null||(ss<0)||(ss>59)){return 0;}
i_val+=ss.length;}else if(token=="a"){
if(val.substring(i_val,i_val+2).toLowerCase()=="am"){ampm="AM";}
else if(val.substring(i_val,i_val+2).toLowerCase()=="pm"){ampm="PM";}
else{return 0;}
i_val+=2;}else{
if(val.substring(i_val,i_val+token.length)!=token){return 0;}
else{i_val+=token.length;}}}
if(i_val!=val.length){return 0;}
if(month==2){
if(((year%4==0)&&(year%100!=0))||(year%400==0)){
if(date>29){return 0;}}else{if(date>28){return 0;}}}
if((month==4)||(month==6)||(month==9)||(month==11)){
if(date>30){return 0;}}
if(hh<12&&ampm=="PM"){hh=hh-0+12;}
else if(hh>11&&ampm=="AM"){hh-=12;}
return new Date(year,month-1,date,hh,mm,ss);}
Date.prototype.formatDate=function(format){
return formatDate(this,format);};
String.prototype.parseDate=function(format){
return parseDate(this,format);}})();(function($){
$.validator.addMethod("alphanumeric",function(value,element){
return this.optional(element)||/^\w+$/i.test(value);},"Letters, numbers or underscores only please");
$.validator.addMethod("lettersonly",function(value,element){
return this.optional(element)||/^[a-z]+$/i.test(value);},"Letters only please");
$.validator.addMethod("phone",function(v,element){
v=v.replace(/\s+/g,"");
return this.optional(element)||v.match(/^[0-9 \(\)]{7,30}$/);},"Please specify a valid phone number");
$.validator.addMethod("postcode",function(v,element){
v=v.replace(/\s+/g,"");
return this.optional(element)||v.match(/^[0-9 A-Za-z]{5,20}$/);},"Please specify a valid postcode");
$.validator.addMethod("date",function(v,element){
v=v.replace(/\s+/g,"");
return this.optional(element)||v.match(/^\d{4}[\/-]\d{1,2}[\/-]\d{1,2}$/);});
$.validator.addClassRules({
date:{date:false},
alphanumeric:{alphanumeric:true},
lettersonly:{lettersonly:true},
phone:{phone:true},
postcode:{postcode:true}});
$.validator.setDefaults({errorElement:"span"});
$.validator.autoCreateRanges=true;})(jQuery);(function($){
$.fn.cssv=function(pre){
var cssPre=$(this).css(pre);
return cssPre.substring(0,cssPre.indexOf("px"))*1;};
$.fn.jBar=function(options){
var op=$.extend({container:"#container",collapse:".collapse",toggleBut:".toggleCollapse div",sideBar:"#sidebar",sideBar2:"#sidebar_s",splitBar:"#splitBar",splitBar2:"#splitBarProxy"},options);
return this.each(function(){
var jbar=this;
var sbar=$(op.sideBar2,jbar);
var bar=$(op.sideBar,jbar);
$(op.toggleBut,bar).click(function(){
DWZ.ui.sbar=false;
$(op.splitBar).hide();
var sbarwidth=sbar.cssv("left")+sbar.outerWidth();
var barleft=sbarwidth-bar.outerWidth();
var cleft=$(op.container).cssv("left")-(bar.outerWidth()-sbar.outerWidth());
var cwidth=bar.outerWidth()-sbar.outerWidth()+$(op.container).outerWidth();
$(op.container).animate({left:cleft,width:cwidth},50,function(){
bar.animate({left:barleft},500,function(){
bar.hide();
sbar.show().css("left",-50).animate({left:5},200);});});
$(op.collapse,sbar).click(function(){
var sbarwidth=sbar.cssv("left")+sbar.outerWidth();
if(bar.is(":hidden")){
$(op.toggleBut,bar).hide();
bar.show().animate({left:sbarwidth},500);
$(op.container).click(_hideBar);}else{
bar.animate({left:barleft},500,function(){
bar.hide();});}
function _hideBar(){
$(op.container).unbind("click",_hideBar);
if(!DWZ.ui.sbar){
bar.animate({left:barleft},500,function(){
bar.hide();});}}
return false;});
return false;});
$(op.toggleBut,sbar).click(function(){
DWZ.ui.sbar=true;
sbar.animate({left:-25},200,function(){
bar.show();});
bar.animate({left:5},800,function(){
$(op.splitBar).show();
$(op.toggleBut,bar).show();
var cleft=5+bar.outerWidth()+$(op.splitBar).outerWidth();
var cwidth=$(op.container).outerWidth()-(cleft-$(op.container).cssv("left"));
$(op.container).css({left:cleft,width:cwidth});
$(op.collapse,sbar).unbind('click');});
return false;});
$(op.splitBar).mousedown(function(event){
$(op.splitBar2).each(function(){
var spbar2=$(this);
setTimeout(function(){spbar2.show();},100);
spbar2.css({visibility:"visible",left:$(op.splitBar).css("left")});
spbar2.jDrag($.extend(options,{obj:$("#sidebar"),move:"horizontal",event:event,stop:function(){
$(this).css("visibility","hidden");
var move=$(this).cssv("left")-$(op.splitBar).cssv("left");
var sbarwidth=bar.outerWidth()+move;
var cleft=$(op.container).cssv("left")+move;
var cwidth=$(op.container).outerWidth()-move;
bar.css("width",sbarwidth);
$(op.splitBar).css("left",$(this).css("left"));
$(op.container).css({left:cleft,width:cwidth});}}));
return false;});});});}})(jQuery);(function($){
$.fn.jDrag=function(options){
if(typeof options=='string'){
if(options=='destroy')
return this.each(function(){
$(this).unbind('mousedown',$.rwdrag.start);
$.data(this,'pp-rwdrag',null);});}
return this.each(function(){
var el=$(this);
$.data($.rwdrag,'pp-rwdrag',{
options:$.extend({
el:el,
obj:el},options)});
if(options.event)
$.rwdrag.start(options.event);
else{
var select=options.selector;
$(select,obj).bind('mousedown',$.rwdrag.start);}});};
$.rwdrag={
start:function(e){
var data=$.data(this,'pp-rwdrag');
var el=data.options.el[0];
$.data(el,'pp-rwdrag',{
options:data.options});
if(!$.rwdrag.current){
$.rwdrag.current={
el:el,
oleft:parseInt(el.style.left)||0,
otop:parseInt(el.style.top)||0,
ox:e.pageX||e.screenX,
oy:e.pageY||e.screenY};
$(document).bind("mouseup",$.rwdrag.stop);
$(document).bind("mousemove",$.rwdrag.drag);}},
drag:function(e){
if(!e)var e=window.event;
var current=$.rwdrag.current;
var data=$.data(current.el,'pp-rwdrag');
var left=(current.oleft+(e.pageX||e.clientX)-current.ox);
var top=(current.otop+(e.pageY||e.clientY)-current.oy);
if(top<1)top=0;
if(data.options.move=='horizontal'){
if((data.options.minW&&left>=$(data.options.obj).cssv("left")+data.options.minW)&&(data.options.maxW&&left<=$(data.options.obj).cssv("left")+data.options.maxW))
current.el.style.left=left+'px';
else if(data.options.scop){
if(data.options.relObj){
if((left-parseInt(data.options.relObj.style.left))>data.options.cellMinW){
current.el.style.left=left+'px';}}else
current.el.style.left=left+'px';}}else if(data.options.move=='vertical'){
current.el.style.top=top+'px';}else{
var selector=$(data.options.selector,$(data.options.obj));
if(left>=-selector.outerWidth()*2/ 3&& top>= 0&&(left+ selector.outerWidth()/ 3<$(window).width())&&(top+selector.outerHeight()<$(window).height())){
current.el.style.left=left+'px';
current.el.style.top=top+'px';}}
return $.rwdrag.preventEvent(e);},
stop:function(e){
var current=$.rwdrag.current;
var data=$.data(current.el,'pp-rwdrag');
$(document).unbind('mousemove',$.rwdrag.drag);
$(document).unbind('mouseup',$.rwdrag.stop);
if(data.options.stop){
data.options.stop.apply(current.el,[current.el]);}
$.rwdrag.current=null;
return $.rwdrag.preventEvent(e);},
preventEvent:function(e){
if(e.stopPropagation)e.stopPropagation();
if(e.preventDefault)e.preventDefault();
return false;}};})(jQuery);(function($){
$.extend($.fn,{
jTree:function(options){
var op=$.extend({checkFn:null,selected:"selected",exp:"expandable",coll:"collapsable",firstExp:"first_expandable",firstColl:"first_collapsable",lastExp:"last_expandable",lastColl:"last_collapsable",folderExp:"folder_expandable",folderColl:"folder_collapsable",endExp:"end_expandable",endColl:"end_collapsable",file:"file",ck:"checked",unck:"unchecked"},options);
return this.each(function(){
var $this=$(this);
var cnum=$this.children().length;
$(">li",$this).each(function(){
var $li=$(this);
var first=$li.prev()[0]?false:true;
var last=$li.next()[0]?false:true;
$li.genTree({
icon:$this.hasClass("treeFolder"),
ckbox:$this.hasClass("treeCheck"),
options:op,
level:0,
exp:(cnum>1?(first?op.firstExp:(last?op.lastExp:op.exp)):op.endExp),
coll:(cnum>1?(first?op.firstColl:(last?op.lastColl:op.coll)):op.endColl),
showSub:(!$this.hasClass("collapse")&&($this.hasClass("expand")||(cnum>1?(first?true:false):true))),
isLast:(cnum>1?(last?true:false):true)});});
setTimeout(function(){
if($this.hasClass("treeCheck")){
var checkFn=eval($this.attr("oncheck"));
if(checkFn&&$.isFunction(checkFn)){
$("div.ckbox",$this).each(function(){
var ckbox=$(this);
ckbox.click(function(){
var checked=$(ckbox).hasClass("checked");
var items=[];
if(checked){
var tnode=$(ckbox).parent().parent();
var boxes=$("input",tnode);
if(boxes.size()>1){
$(boxes).each(function(){
items[items.length]={name:$(this).attr("name"),value:$(this).val()};});}else{
items={name:boxes.attr("name"),value:boxes.val()};}}
checkFn({checked:checked,items:items});});});}}
$("a",$this).click(function(event){
$("div."+op.selected,$this).removeClass(op.selected);
var parent=$(this).parent().addClass(op.selected);
$(".ckbox",parent).trigger("click");
event.stopPropagation();
$(document).trigger("click");
if(!$(this).attr("target"))return false;});},1);});},
subTree:function(op,level){
return this.each(function(){
$(">li",this).each(function(){
var $this=$(this);
setTimeout(function(){
var isLast=($this.next()[0]?false:true);
$this.genTree({
icon:op.icon,
ckbox:op.ckbox,
exp:isLast?op.options.lastExp:op.options.exp,
coll:isLast?op.options.lastColl:op.options.coll,
options:op.options,
level:level,
space:isLast?null:op.space,
showSub:op.showSub,
isLast:isLast});},1);});});},
genTree:function(options){
var op=$.extend({icon:options.icon,ckbox:options.ckbox,exp:"",coll:"",showSub:false,level:0,options:null,isLast:false},options);
return this.each(function(){
var node=$(this);
var tree=$(">ul",node);
var parent=node.parent().prev();
var checked='unchecked';
if(op.ckbox){
if($(">.checked",parent).size()>0)checked='checked';}
if(tree.size()>0){
node.children(":first").wrap("<div></div>");
$(">div",node).prepend("<div class='"+(op.showSub?op.coll:op.exp)+"'></div>"+(op.ckbox?"<div class='ckbox "+checked+"'></div>":"")+(op.icon?"<div class='"+(op.showSub?op.options.folderColl:op.options.folderExp)+"'></div>":""));
op.showSub?tree.show():tree.hide();
$(">div>div:first,>div>a",node).click(function(){
var $fnode=$(">li:first",tree);
if($fnode.children(":first").isTag('a'))tree.subTree(op,op.level+1);
var $this=$(this);
var isA=$this.isTag('a');
var $this=isA?$(">div>div",node).eq(op.level):$this;
if(!isA||tree.is(":hidden")){
$this.toggleClass(op.exp).toggleClass(op.coll);
if(op.icon){
$(">div>div:last",node).toggleClass(op.options.folderExp).toggleClass(op.options.folderColl);}}(tree.is(":hidden"))?tree.slideDown("fast"):(isA?"":tree.slideUp("fast"));
return false;});
addSpace(op.level,node);
if(op.showSub)tree.subTree(op,op.level+1);}else{
node.children().wrap("<div></div>");
$(">div",node).prepend("<div class='node'></div>"+(op.ckbox?"<div class='ckbox "+checked+"'></div>":"")+(op.icon?"<div class='file'></div>":""));
addSpace(op.level,node);
if(op.isLast)$(node).addClass("last");}
if(op.ckbox)node._check(op);
$(">div",node).mouseover(function(){
$(this).addClass("hover");}).mouseout(function(){
$(this).removeClass("hover");});
if($.browser.msie)
$(">div",node).click(function(){
$("a",this).trigger("click");
return false;});});
function addSpace(level,node){
if(level>0){
var parent=node.parent().parent();
var space=!parent.next()[0]?"indent":"line";
var plist="<div class='"+space+"'></div>";
if(level>1){
var next=$(">div>div",parent).filter(":first");
var prev="";
while(level>1){
prev=prev+"<div class='"+next.attr("class")+"'></div>";
next=next.next();
level--;}
plist=prev+plist;}
$(">div",node).prepend(plist);}}},
_check:function(op){
var node=$(this);
var ckbox=$(">div>.ckbox",node);
var $input=node.find("a");
ckbox.append("<input type='checkbox' name='"+$input.attr("tname")+"' value='"+$input.attr("tvalue")+"' style='display:none;'/>")
.click(function(){
var cked=ckbox.hasClass("checked");
var aClass=cked?"unchecked":"checked";
var rClass=cked?"checked":"unchecked";
ckbox.removeClass(rClass).removeClass(!cked?"indeterminate":"").addClass(aClass);
$("input",ckbox).attr("checked",!cked);
$(">ul",node).find("li").each(function(){
var box=$("div.ckbox",this);
box.removeClass(rClass).removeClass(!cked?"indeterminate":"").addClass(aClass)
.find("input").attr("checked",!cked);});
$(node)._checkParent();
return false;});

var cAttr=$input.attr("checked");
if(cAttr)cAttr=eval(cAttr);
if(cAttr){
ckbox.find("input").attr("checked",true);
ckbox.removeClass("unchecked").addClass("checked");
$(node)._checkParent();}},
_checkParent:function(){
if($(this).parent().hasClass("tree"))return;
var parent=$(this).parent().parent();
var stree=$(">ul",parent);
var ckbox=stree.find(">li>a").size()+stree.find("div.ckbox").size();
var ckboxed=stree.find("div.checked").size();
var aClass=(ckboxed==ckbox?"checked":(ckboxed!=0?"indeterminate":"unchecked"));
var rClass=(ckboxed==ckbox?"indeterminate":(ckboxed!=0?"checked":"indeterminate"));
$(">div>.ckbox",parent).removeClass("unchecked").removeClass(rClass).addClass(aClass);
parent._checkParent();}});})(jQuery);(function($){
var jmenus=new Map();
$.dwz=$.dwz||{};
$(window).resize(function(){
setTimeout(function(){
for(var i=0;i<jmenus.size();i++){
fillSpace(jmenus.element(i).key);}},100);});
$.fn.extend({
accordion:function(options,data){
var args=Array.prototype.slice.call(arguments,1);
return this.each(function(){
if(options.fillSpace)jmenus.put(options.fillSpace,this);
if(typeof options=="string"){
var accordion=$.data(this,"dwz-accordion");
accordion[options].apply(accordion,args);}else if(!$(this).is(".dwz-accordion"))
$.data(this,"dwz-accordion",new $.dwz.accordion(this,options));});},
activate:function(index){
return this.accordion("activate",index);}});
$.dwz.accordion=function(container,options){
this.options=options=$.extend({},$.dwz.accordion.defaults,options);
this.element=container;
$(container).addClass("dwz-accordion");
if(options.navigation){
var current=$(container).find("a").filter(options.navigationFilter);
if(current.length){
if(current.filter(options.header).length){
options.active=current;}else{
options.active=current.parent().parent().prev();
current.addClass("current");}}}
options.headers=$(container).find(options.header);
options.active=findActive(options.headers,options.active);
if(options.fillSpace){
fillSpace(options.fillSpace);}else if(options.autoheight){
var maxHeight=0;
options.headers.next().each(function(){
maxHeight=Math.max(maxHeight,$(this).outerHeight());}).height(maxHeight);}
options.headers
.not(options.active||"")
.next()
.hide();
options.active.find("h2").addClass(options.selectedClass);
if(options.event)
$(container).bind((options.event)+".dwz-accordion",clickHandler);};
$.dwz.accordion.prototype={
activate:function(index){
clickHandler.call(this.element,{
target:findActive(this.options.headers,index)[0]});},
enable:function(){
this.options.disabled=false;},
disable:function(){
this.options.disabled=true;},
destroy:function(){
this.options.headers.next().css("display","");
if(this.options.fillSpace||this.options.autoheight){
this.options.headers.next().css("height","");}
$.removeData(this.element,"dwz-accordion");
$(this.element).removeClass("dwz-accordion").unbind(".dwz-accordion");}}
function scopeCallback(callback,scope){
return function(){
return callback.apply(scope,arguments);};}
function completed(cancel){
if(!$.data(this,"dwz-accordion"))
return;
var instance=$.data(this,"dwz-accordion");
var options=instance.options;
options.running=cancel?0:--options.running;
if(options.running)
return;
if(options.clearStyle){
options.toShow.add(options.toHide).css({
height:"",
overflow:""});}
$(this).triggerHandler("change.dwz-accordion",[options.data],options.change);}
function fillSpace(key){
var obj=jmenus.get(key);
if(!obj)return;
var parent=$(obj).parent();
var height=parent.height()-(($(".accordionHeader",obj).size())*($(".accordionHeader:first-child",obj).outerHeight()))-2;
var os=parent.children().not(obj);
$.each(os,function(i){
height-=$(os[i]).outerHeight();});
$(".accordionContent",obj).height(height);}
function toggle(toShow,toHide,data,clickedActive,down){
var options=$.data(this,"dwz-accordion").options;
options.toShow=toShow;
options.toHide=toHide;
options.data=data;
var complete=scopeCallback(completed,this);
options.running=toHide.size()==0?toShow.size():toHide.size();
if(options.animated){
if(!options.alwaysOpen&&clickedActive){
$.dwz.accordion.animations[options.animated]({
toShow:jQuery([]),
toHide:toHide,
complete:complete,
down:down,
autoheight:options.autoheight});}else{
$.dwz.accordion.animations[options.animated]({
toShow:toShow,
toHide:toHide,
complete:complete,
down:down,
autoheight:options.autoheight});}}else{
if(!options.alwaysOpen&&clickedActive){
toShow.toggle();}else{
toHide.hide();
toShow.show();}
complete(true);}}
function clickHandler(event){
var options=$.data(this,"dwz-accordion").options;
if(options.disabled)
return false;
if(!event.target&&!options.alwaysOpen){
options.active.find("h2").toggleClass(options.selectedClass);
var toHide=options.active.next(),
data={
instance:this,
options:options,
newHeader:jQuery([]),
oldHeader:options.active,
newContent:jQuery([]),
oldContent:toHide},
toShow=options.active=$([]);
toggle.call(this,toShow,toHide,data);
return false;}
var clicked=$(event.target);
if(clicked.parents(options.header).length)
while(!clicked.is(options.header))
clicked=clicked.parent();
var clickedActive=clicked[0]==options.active[0];
if(options.running||(options.alwaysOpen&&clickedActive))
return false;
if(!clicked.is(options.header))
return;
options.active.find("h2").toggleClass(options.selectedClass);
if(!clickedActive){
clicked.find("h2").addClass(options.selectedClass);}
var toShow=clicked.next(),
toHide=options.active.next(),
data={
instance:this,
options:options,
newHeader:clicked,
oldHeader:options.active,
newContent:toShow,
oldContent:toHide},
down=options.headers.index(options.active[0])>options.headers.index(clicked[0]);
options.active=clickedActive?$([]):clicked;
toggle.call(this,toShow,toHide,data,clickedActive,down);
return false;};
function findActive(headers,selector){
return selector!=undefined?typeof selector=="number"?headers.filter(":eq("+selector+")"):headers.not(headers.not(selector)):selector===false?$([]):headers.filter(":eq(0)");}
$.extend($.dwz.accordion,{
defaults:{
selectedClass:"collapsable",
alwaysOpen:true,
animated:'slide',
event:"click",
header:".accordionHeader",
autoheight:true,
running:0,
navigationFilter:function(){
return this.href.toLowerCase()==location.href.toLowerCase();}},
animations:{
slide:function(options,additions){
options=$.extend({
easing:"swing",
duration:300},options,additions);
if(!options.toHide.size()){
options.toShow.animate({height:"show"},options);
return;}
var hideHeight=options.toHide.height(),
showHeight=options.toShow.height(),
difference=showHeight/hideHeight;
options.toShow.css({height:0}).show();
options.toHide.filter(":hidden").each(options.complete).end().filter(":visible").animate({height:"hide"},{
step:function(now){
var current=(hideHeight-now)*difference;
if($.browser.msie||$.browser.opera){
current=Math.ceil(current);}
options.toShow.height(current);},
duration:options.duration,
easing:options.easing,
complete:function(){
if(!options.autoheight){
options.toShow.css("height","auto");}
options.complete();}});},
bounceslide:function(options){
this.slide(options,{
easing:options.down?"bounceout":"swing",
duration:options.down?1000:200});},
easeslide:function(options){
this.slide(options,{
easing:"easeinout",
duration:700})}}});})(jQuery);
function initEnv(){
if($.browser.msie&&/6.0/.test(navigator.userAgent)){
try{
document.execCommand("BackgroundImageCache",false,1);}catch(e){}}
initLayout();
$(window).resize(function(){
initLayout();
$(this).trigger("resizeGrid");});
$("#leftside").jBar({minW:150,maxW:700});
if($.taskBar)$.taskBar.init();
if(navTab)navTab.init();
$("#switchEnvBox").switchEnv();
initUI();
$("#taskbar li").hoverClass("hover");
$("#taskbar li.selected").hoverClass("selectedHover");
$("#taskbar .close").hoverClass("closeHover");
$("#taskbar .restore").hoverClass("restoreHover");
$("#taskbar .minimize").hoverClass("minimizeHover");
$("#taskbar .taskbarLeft").hoverClass("taskbarLeftHover");
$("#taskbar .taskbarRight").hoverClass("taskbarRightHover");
var jTabsPH=$("div.tabsPageHeader");
jTabsPH.find(".tabsLeft").hoverClass("tabsLeftHover");
jTabsPH.find(".tabsRight").hoverClass("tabsRightHover");
jTabsPH.find(".tabsMore").hoverClass("tabsMoreHover");
setTimeout(function(){
var ajaxbg=$("#background,#progressBar");
ajaxbg.hide();
$(document).ajaxStart(function(){
ajaxbg.show();}).ajaxStop(function(){
ajaxbg.hide();});},500);}
function initLayout(){
var iContentW=$(window).width()-(DWZ.ui.sbar?$("#sidebar").width()+10:34)-5;
var iContentH=$(window).height()-$("#header").height()-34;
$("#container").width(iContentW);
$("#container .tabsPageContent").height(iContentH-34).find("[layoutH]").layoutH();
$("#sidebar, #sidebar_s .collapse, #splitBar, #splitBarProxy").height(iContentH-5);$("#sidebar_s .collapse").height(iContentH-7);
$("#taskbar").css({top:iContentH+$("#header").height()+5,width:$(window).width()});}
function initUI(_box){
var jParent=$(_box||document);
$("table.table",jParent).jTable();
$('table.list').cssTable();
$("div.tabs",jParent).each(function(){
var $this=$(this);
var options={};
options.currentIndex=$this.attr("currentIndex")||0;
options.eventType=$this.attr("eventType")||"click";
$this.tabs(options);});
$("ul.tree",jParent).jTree();
$('div.accordion',jParent).each(function(){
var $this=$(this);
$this.accordion({fillSpace:$this.attr("fillSpace"),alwaysOpen:true,active:0});});
$(":button.checkboxCtrl, :checkbox.checkboxCtrl").checkboxCtrl(jParent);
$("select.combox",jParent).combox();
if($.fn.xheditor){
$("textarea.editor",jParent).each(function(){
var $this=$(this);
$this.xheditor({skin:'vista',tools:$this.attr("tools")||'full',upLinkUrl:APP+"/article/upload/",upLinkExt:"zip,rar,txt",upImgUrl:APP+"/article/upload/",upImgExt:"jpg,jpeg,gif,png",upFlashUrl:APP+"/article/upload/",upFlashExt:"swf",upMediaUrl:"upload.php",upMediaExt:"avi"});});}
if($.fn.uploadify){
$(":file[uploader]",jParent).each(function(){
var $this=$(this);
var options={
uploader:$this.attr("uploader"),
script:$this.attr("script"),
cancelImg:$this.attr("cancelImg"),
queueID:$this.attr("fileQueue")||"fileQueue",
fileDesc:"*.jpg;*.jpeg;*.gif;*.png;*.pdf",
fileExt:"*.jpg;*.jpeg;*.gif;*.png;*.pdf",
folder:$this.attr("folder"),
auto:true,
multi:true,
onError:uploadifyError,
onComplete:uploadifyComplete,
onAllComplete:uploadifyAllComplete};
if($this.attr("onComplete")){
options.onComplete=DWZ.jsonEval($this.attr("onComplete"));}
if($this.attr("onAllComplete")){
options.onAllComplete=DWZ.jsonEval($this.attr("onAllComplete"));}
if($this.attr("scriptData")){
options.scriptData=DWZ.jsonEval($this.attr("scriptData"));}
$this.uploadify(options);});}
$("input[type=text], input[type=password], textarea",jParent).addClass("textInput").focusClass("focus");
$("input[readonly], textarea[readonly]",jParent).addClass("readonly");
$("input[disabled=true], textarea[disabled=true]",jParent).addClass("disabled");
$("input[type=text]",jParent).not("div.tabs input[type=text]",jParent).filter("[alt]").inputAlert();
$("div.panelBar li, div.panelBar",jParent).hoverClass("hover");
$("div.button",jParent).hoverClass("buttonHover");
$("div.buttonActive",jParent).hoverClass("buttonActiveHover");
$("div.tabsHeader li, div.tabsPageHeader li, div.accordionHeader, div.accordion",jParent).hoverClass("hover");
$("div.panel",jParent).jPanel();
$("form.required-validate",jParent).each(function(){
$(this).validate({
focusInvalid:false,
focusCleanup:true,
errorElement:"span",
ignore:".ignore",
invalidHandler:function(form,validator){
var errors=validator.numberOfInvalids();
if(errors){
var message=DWZ.msg("validateFormError",[errors]);
alertMsg.error(message);}}});});
if($.fn.datepicker){
$('input.date',jParent).each(function(){
var $this=$(this);
var opts={};
if($this.attr("pattern"))opts.pattern=$this.attr("pattern");
if($this.attr("yearstart"))opts.yearstart=$this.attr("yearstart");
if($this.attr("yearend"))opts.yearend=$this.attr("yearend");
$this.datepicker(opts);});}
$("a[target=navTab]",jParent).each(function(){
$(this).click(function(event){
var $this=$(this);
var title=$this.attr("title")||$this.text();
var tabid=$this.attr("rel")||"_blank";
var flesh=eval($this.attr("flesh")||"true");
var url=unescape($this.attr("href")).replaceTmById(jParent);
DWZ.debug(url);
if(!url.isFinishedTm()){
alertMsg.error($this.attr("warn")||DWZ.msg("alertSelectMsg"));
return false;}
navTab.openTab(tabid,url,{title:title,flesh:flesh});
event.preventDefault();});});
$("a[target=navTabTodo]",jParent).each(function(){
$(this).click(function(event){
var $this=$(this);
var url=unescape($this.attr("href")).replaceTmById(jParent);
DWZ.debug(url);
if(!url.isFinishedTm()){
alertMsg.error($this.attr("warn")||DWZ.msg("alertSelectMsg"));
return false;}
var title=$this.attr("title");
if(title){
alertMsg.confirm(title,{
okCall:function(){
navTabTodo(url,$this.attr("callback"));}});}else{
navTabTodo(url,$this.attr("callback"));}
event.preventDefault();});});
$("a[target=dialog]",jParent).each(function(){
$(this).click(function(event){
var $this=$(this);
var title=$this.attr("title")||$this.text();
var rel=$this.attr("rel")||"_blank";
var options={};
var w=$this.attr("width");
var h=$this.attr("height");
if(w)options.width=w;
if(h)options.height=h;
options.max=eval($this.attr("max")||"false");
options.mask=eval($this.attr("mask")||"false");
options.maxable=eval($this.attr("maxable")||"true");
options.minable=eval($this.attr("minable")||"true");
options.flesh=eval($this.attr("flesh")||"true");
options.resizable=eval($this.attr("resizable")||"true");
options.drawable=eval($this.attr("drawable")||"true");
options.close=eval($this.attr("close")||"");
options.param=$this.attr("param")||"";
var url=unescape($this.attr("href")).replaceTmById(jParent);
DWZ.debug(url);
if(!url.isFinishedTm()){
alertMsg.error($this.attr("warn")||DWZ.msg("alertSelectMsg"));
return false;}
$.pdialog.open(url,rel,title,options);
return false;});});
$("a[target=ajax]",jParent).each(function(){
$(this).click(function(event){
var $this=$(this);
var rel=$this.attr("rel");
if(rel)$("#"+rel).loadUrl($this.attr("href"));
event.preventDefault();});});
$("div.pagination",jParent).each(function(){
var $this=$(this);
$this.pagination({
targetType:$this.attr("targetType"),
totalCount:$this.attr("totalCount"),
numPerPage:$this.attr("numPerPage"),
pageNumShown:$this.attr("pageNumShown"),
currentPage:$this.attr("currentPage")});});}
function closedialog(param){
alert(param.msg);
return true;}(function($){
$.fn.extend({
theme:function(options){
var op=$.extend({themeBase:"themes"},options);
var _themeHref=op.themeBase+"/#theme#/style.css";
return this.each(function(){
var jThemeLi=$(this).find(">li[theme]");
var setTheme=function(themeName){
$("head").find("link[href$=style.css]").attr("href",_themeHref.replace("#theme#",themeName));
jThemeLi.find(">div").removeClass("selected");
jThemeLi.filter("[theme="+themeName+"]").find(">div").addClass("selected");
if($.isFunction($.cookie))$.cookie("dwz_theme",themeName);}
jThemeLi.each(function(index){
var $this=$(this);
var themeName=$this.attr("theme");
$this.addClass(themeName).click(function(){
setTheme(themeName);});});
if($.isFunction($.cookie)){
var themeName=$.cookie("dwz_theme");
if(themeName){
setTheme(themeName);}}});}});})(jQuery);(function($){
$.fn.switchEnv=function(){
var op={cities$:">ul>li",boxTitle$:">a>span"};
return this.each(function(){
var $this=$(this);
$this.click(function(){
if($this.hasClass("selected")){
_hide($this);}else{
_show($this);}
return false;});
$this.find(op.cities$).click(function(){
var $li=$(this);
var $sidebar=$("#sidebar");
$.post($li.find(">a").attr("href"),{},function(html){
_hide($this);
$this.find(op.boxTitle$).html($li.find(">a").html());
navTab.closeAllTab();
$sidebar.find(".accordion").remove()
$sidebar.append(html).initUI();});
return false;});});}
function _show($box){
$box.addClass("selected");
$(document).bind("click",{box:$box},_handler);}
function _hide($box){
$box.removeClass("selected");
$(document).unbind("click",_handler);}
function _handler(event){
_hide(event.data.box);}})(jQuery);
$.setRegional("alertMsg",{
title:{error:"Error",info:"Information",warn:"Warning",correct:"Successful",confirm:"Confirmation"},
butMsg:{ok:"OK",yes:"Yes",no:"No",cancel:"Cancel"}});
var alertMsg={
_boxId:"#alertMsgBox",
_bgId:"#alertBackground",
_closeTimer:null,
_types:{error:"error",info:"info",warn:"warn",correct:"correct",confirm:"confirm"},
_getTitle:function(key){
return $.regional.alertMsg.title[key];},
_open:function(type,msg,buttons){
$(this._boxId).remove();
var butsHtml="";
if(buttons){
for(var i=0;i<buttons.length;i++){
var sRel=buttons[i].call?"callback":"";
butsHtml+=DWZ.frag["alertButFrag"].replace("#butMsg#",buttons[i].name).replace("#callback#",sRel);}}
var boxHtml=DWZ.frag["alertBoxFrag"].replace("#type#",type).replace("#title#",this._getTitle(type)).replace("#message#",msg).replace("#butFragment#",butsHtml);
$(boxHtml).appendTo("body").css({top:-$(this._boxId).height()+"px"}).animate({top:"0px"},500);
if(this._closeTimer){
clearTimeout(this._closeTimer);
this._closeTimer=null;}
if(this._types.info==type||this._types.correct==type){
this._closeTimer=setTimeout(function(){alertMsg.close()},3500);}else{
$(this._bgId).show();}
var jCallButs=$(this._boxId).find("[rel=callback]");
for(var i=0;i<buttons.length;i++){
if(buttons[i].call)jCallButs.eq(i).click(buttons[i].call);}},
close:function(){
$(this._boxId).animate({top:-$(this._boxId).height()},500,function(){
$(this).remove();});
$(this._bgId).hide();},
error:function(msg,options){
this._alert(this._types.error,msg,options);},
info:function(msg,options){
this._alert(this._types.info,msg,options);},
warn:function(msg,options){
this._alert(this._types.warn,msg,options);},
correct:function(msg,options){
this._alert(this._types.correct,msg,options);},
_alert:function(type,msg,options){
var op={okName:$.regional.alertMsg.butMsg.ok,okCall:null};
$.extend(op,options);
var buttons=[{name:op.okName,call:op.okCall}];
this._open(type,msg,buttons);},
confirm:function(msg,options){
var op={okName:$.regional.alertMsg.butMsg.ok,okCall:null,cancelName:$.regional.alertMsg.butMsg.cancel,cancelCall:null};
$.extend(op,options);
var buttons=[{name:op.okName,call:op.okCall},{name:op.cancelName,call:op.cancelCall}];
this._open(this._types.confirm,msg,buttons);}};(function($){
var menu,shadow,hash;
$.fn.extend({
contextMenu:function(id,options){
var op=$.extend({
shadow:true,
bindings:{},
ctrSub:null},options);
if(!menu){
menu=$('<div id="contextmenu"></div>').appendTo('body').hide();}
if(!shadow){
shadow=$('<div id="contextmenuShadow"></div>').appendTo('body').hide();}
hash=hash||[];
hash.push({
id:id,
shadow:op.shadow,
bindings:op.bindings||{},
ctrSub:op.ctrSub});
var index=hash.length-1;
$(this).bind('contextmenu',function(e){
display(index,this,e,op);
return false;});
return this;}});
function display(index,trigger,e,options){
var cur=hash[index];
var content=$(DWZ.frag[cur.id]);
content.find('li').hoverClass();
menu.html(content);
$.each(cur.bindings,function(id,func){
$("[rel='"+id+"']",menu).bind('click',function(e){
hide();
func($(trigger),$("#"+cur.id));});});
var posX=e.pageX;
var posY=e.pageY;
if($(window).width()<posX+menu.width())posX-=menu.width();
if($(window).height()<posY+menu.height())posY-=menu.height();
menu.css({'left':posX,'top':posY}).show();
if(cur.shadow)shadow.css({width:menu.width(),height:menu.height(),left:posX+3,top:posY+3}).show();
$(document).one('click',hide);
if($.isFunction(cur.ctrSub)){cur.ctrSub($(trigger),$("#"+cur.id));}}
function hide(){
menu.hide();
shadow.hide();}})(jQuery);
var navTab={
componentBox:null,
_tabBox:null,
_prevBut:null,
_nextBut:null,
_panelBox:null,
_moreBut:null,
_moreBox:null,
_currentIndex:0,
_op:{id:"navTab",stTabBox:".navTab-tab",stPanelBox:".navTab-panel",mainTabId:"main",close$:"a.close",prevClass:"tabsLeft",nextClass:"tabsRight",stMore:".tabsMore",stMoreLi:"ul.tabsMoreList"},
init:function(options){
if($.History)$.History.init("#container");
var $this=this;
$.extend(this._op,options);
this.componentBox=$("#"+this._op.id);
this._tabBox=this.componentBox.find(this._op.stTabBox);
this._panelBox=this.componentBox.find(this._op.stPanelBox);
this._prevBut=this.componentBox.find("."+this._op.prevClass);
this._nextBut=this.componentBox.find("."+this._op.nextClass);
this._moreBut=this.componentBox.find(this._op.stMore);
this._moreBox=this.componentBox.find(this._op.stMoreLi);
this._prevBut.click(function(event){$this._scrollPrev()});
this._nextBut.click(function(event){$this._scrollNext()});
this._moreBut.click(function(){
$this._moreBox.show();
return false;});
$(document).click(function(){$this._moreBox.hide()});
this._contextmenu(this._tabBox);
this._contextmenu(this._getTabs());
this._init();
this._ctrlScrollBut();},
_init:function(){
var $this=this;
this._getTabs().each(function(iTabIndex){
$(this).unbind("click").click(function(event){
$this._switchTab(iTabIndex);});
$(this).find(navTab._op.close$).unbind("click").click(function(){
$this._closeTab(iTabIndex);});});
this._getMoreLi().each(function(iTabIndex){
$(this).find(">a").unbind("click").click(function(event){
$this._switchTab(iTabIndex);});});
this._switchTab(this._currentIndex);},
_contextmenu:function($obj){
var $this=this;
$obj.contextMenu('navTabCM',{
bindings:{
reload:function(t,m){
$this._reload(t,true);},
closeCurrent:function(t,m){
var tabId=t.attr("tabid");
if(tabId)$this.closeTab(tabId);
else $this.closeCurrentTab();},
closeOther:function(t,m){
var index=$this._indexTabId(t.attr("tabid"));
$this._closeOtherTab(index>0?index:$this._currentIndex);},
closeAll:function(t,m){
$this.closeAllTab();}},
ctrSub:function(t,m){
var mReload=m.find("[rel='reload']");
var mCur=m.find("[rel='closeCurrent']");
var mOther=m.find("[rel='closeOther']");
var mAll=m.find("[rel='closeAll']");
var $tabLi=$this._getTabs();
if($tabLi.size()<2){
mCur.addClass("disabled");
mOther.addClass("disabled");
mAll.addClass("disabled");}
if($this._currentIndex==0||t.attr("tabid")==$this._op.mainTabId){
mCur.addClass("disabled");
mReload.addClass("disabled");}else if($tabLi.size()==2){
mOther.addClass("disabled");}}});},
_getTabs:function(){
return this._tabBox.find("> li");},
_getPanels:function(){
return this._panelBox.find("> div");},
_getMoreLi:function(){
return this._moreBox.find("> li");},
_getTab:function(tabid){
var index=this._indexTabId(tabid);
if(index>=0)return this._getTabs().eq(index);},
_getPanel:function(tabid){
var index=this._indexTabId(tabid);
if(index>=0)return this._getPanels().eq(index);},
_getTabsW:function(iStart,iEnd){
return this._tabsW(this._getTabs().slice(iStart,iEnd));},
_tabsW:function($tabs){
var iW=0;
$tabs.each(function(){
iW+=$(this).outerWidth(true);});
return iW;},
_indexTabId:function(tabid){
if(!tabid)return -1;
var iOpenIndex=-1;
this._getTabs().each(function(index){
if($(this).attr("tabid")==tabid){iOpenIndex=index;return;}});
return iOpenIndex;},
_getLeft:function(){
return this._tabBox.position().left;},
_getScrollBarW:function(){
return this.componentBox.width()-55;},
_visibleStart:function(){
var iLeft=this._getLeft(),iW=0;
var $tabs=this._getTabs();
for(var i=0;i<$tabs.size();i++){
if(iW+iLeft>=0)return i;
iW+=$tabs.eq(i).outerWidth(true);}
return 0;},
_visibleEnd:function(){
var iLeft=this._getLeft(),iW=0;
var $tabs=this._getTabs();
for(var i=0;i<$tabs.size();i++){
iW+=$tabs.eq(i).outerWidth(true);
if(iW+iLeft>this._getScrollBarW())return i;}
return $tabs.size();},
_scrollPrev:function(){
var iStart=this._visibleStart();
if(iStart>0){
this._scrollTab(-this._getTabsW(0,iStart-1));}},
_scrollNext:function(){
var iEnd=this._visibleEnd();
if(iEnd<this._getTabs().size()){
this._scrollTab(-this._getTabsW(0,iEnd+1)+this._getScrollBarW());}},
_scrollTab:function(iLeft,isNext){
var $this=this;
this._tabBox.animate({left:iLeft+'px'},200,function(){$this._ctrlScrollBut();});},
_scrollCurrent:function(){
var iW=this._tabsW(this._getTabs());
if(iW<=this._getScrollBarW()){

this._scrollTab(0);}else if(this._getLeft()<this._getScrollBarW()-iW){
this._scrollTab(this._getScrollBarW()-iW);}else if(this._currentIndex<this._visibleStart()){
this._scrollTab(-this._getTabsW(0,this._currentIndex));}else if(this._currentIndex>=this._visibleEnd()){
this._scrollTab(this._getScrollBarW()-this._getTabs().eq(this._currentIndex).outerWidth(true)-this._getTabsW(0,this._currentIndex));}},
_ctrlScrollBut:function(){
var iW=this._tabsW(this._getTabs());
if(this._getScrollBarW()>iW){
this._prevBut.hide();
this._nextBut.hide();
this._tabBox.parent().removeClass("tabsPageHeaderMargin");}else{
this._prevBut.show().removeClass("tabsLeftDisabled");
this._nextBut.show().removeClass("tabsRightDisabled");
this._tabBox.parent().addClass("tabsPageHeaderMargin");
if(this._getLeft()>=0){
this._prevBut.addClass("tabsLeftDisabled");}else if(this._getLeft()<=this._getScrollBarW()-iW){
this._nextBut.addClass("tabsRightDisabled");}}},
_switchTab:function(iTabIndex){
var $tab=this._getTabs().removeClass("selected").eq(iTabIndex).addClass("selected");
this._getPanels().hide().eq(iTabIndex).show();
this._getMoreLi().removeClass("selected").eq(iTabIndex).addClass("selected");
this._currentIndex=iTabIndex;
this._scrollCurrent();
this._reload($tab);},
_closeTab:function(index){
this._getTabs().eq(index).remove();
this._getPanels().eq(index).remove();
this._getMoreLi().eq(index).remove();
if(this._currentIndex>=index)this._currentIndex--;
this._init();
this._scrollCurrent();
this._reload(this._getTabs().eq(this._currentIndex));},
closeTab:function(tabid){
var index=this._indexTabId(tabid);
if(index>0){this._closeTab(index);}},
closeCurrentTab:function(){
if(this._currentIndex>0){this._closeTab(this._currentIndex);}},
closeAllTab:function(){
this._getTabs().filter(":gt(0)").remove();
this._getPanels().filter(":gt(0)").remove();
this._getMoreLi().filter(":gt(0)").remove();
this._currentIndex=0;
this._init();
this._scrollCurrent();},
_closeOtherTab:function(index){
index=index||this._currentIndex;
if(index>0){
var str$=":eq("+index+")"
this._getTabs().not(str$).filter(":gt(0)").remove();
this._getPanels().not(str$).filter(":gt(0)").remove();
this._getMoreLi().not(str$).filter(":gt(0)").remove();
this._currentIndex=1;
this._init();
this._scrollCurrent();}else{
this.closeAllTab();}},
_loadUrlCallback:function($panel){
$panel.find("[layoutH]").layoutH();
$panel.find(":button.close").click(function(){
navTab.closeCurrentTab();});},
_reload:function($tab,flag){
flag=flag||$tab.data("reloadFlag");
var url=$tab.data("url");
if(flag&&url){
$tab.data("reloadFlag",null);
var $panel=this._getPanel($tab.data("tabid"));
if($panel)$panel.loadUrl(url,{},function(){
navTab._loadUrlCallback($panel);});}},
reloadFlag:function(tabid){
var $tab=this._getTab(tabid);
if($tab){
if(this._indexTabId(tabid)==this._currentIndex)this._reload($tab,true);
else $tab.data("reloadFlag",1);}},
reload:function(url,data,tabid){
var $panel=tabid?this._getPanel(tabid):this._getPanels().eq(this._currentIndex);
if($panel){
if(!url){
var $tab=tabid?this._getTab(tabid):this._getTabs().eq(this._currentIndex);
url=$tab.data("url");}
if(url){
$panel.loadUrl(url,data,function(){
navTab._loadUrlCallback($panel);});}}},
getCurrentPanel:function(){
return this._getPanels().eq(this._currentIndex);},
openTab:function(tabid,url,options){
var op=$.extend({title:"New Tab",data:{},flesh:true},options);
function openExternal($panel){
var h=navTab._panelBox.height();
$panel.html(DWZ.frag["externalFrag"].replaceAll("{url}",url).replaceAll("{height}",h+"px"));}
var iOpenIndex=this._indexTabId(tabid);
if(iOpenIndex>=0){
var $tab=this._getTabs().eq(iOpenIndex);
var stSpan=$tab.attr("tabid")==this._op.mainTabId?"> a > span > span":"> a > span";
$tab.find(stSpan).text(op.title);
var $panel=this._getPanels().eq(iOpenIndex);
if(op.flesh||$tab.data("url")!=url){
$tab.data("url",url);
if(url.isExternalUrl()){
openExternal($panel);}else{
$panel.loadUrl(url,op.data,function(){
navTab._loadUrlCallback($panel);});}}
this._currentIndex=iOpenIndex;}else{
var tabFrag='<li tabid="#tabid#"><a href="javascript:" title="#title#"><span>#title#</span></a><a href="javascript:void(0)" class="close">close</a></li>';
this._tabBox.append(tabFrag.replace("#tabid#",tabid).replaceAll("#title#",op.title));
this._panelBox.append('<div></div>');
this._moreBox.append('<li><a href="javascript:" title="#title#">#title#</a></li>'.replaceAll("#title#",op.title));
var $tabs=this._getTabs();
var $panel=this._getPanels().filter(":last");
if(url.isExternalUrl()){
openExternal($panel);}else{
$panel.loadUrl(url,op.data,function(){
navTab._loadUrlCallback($panel);
if($.History){
$.History.addHistory(tabid,function(tabid){
var i=navTab._indexTabId(tabid);
if(i>=0)navTab._switchTab(i);},tabid);}});}
this._currentIndex=$tabs.size()-1;
this._contextmenu($tabs.filter(":last").hoverClass("hover"));}
this._init();
this._scrollCurrent();
this._getTabs().eq(this._currentIndex).data("url",url).data("tabid",tabid);}};(function($){
$.fn.extend({
tabs:function(options){
var op=$.extend({reverse:false,eventType:"click",currentIndex:0,stTabHeader:"> .tabsHeader",stTab:">.tabsHeaderContent>ul",stTabPanel:"> .tabsContent",ajaxClass:"j-ajax",closeClass:"close",prevClass:"tabsLeft",nextClass:"tabsRight"},options);
return this.each(function(){
initTab($(this));});
function initTab(jT){
var jSelector=jT.add($("> *",jT));
var jTabHeader=$(op.stTabHeader,jSelector);
var jTabs=$(op.stTab+" li",jTabHeader);
var jGroups=$(op.stTabPanel+" > *",jSelector);
jTabs.unbind().find("a").unbind();
jTabHeader.find("."+op.prevClass).unbind();
jTabHeader.find("."+op.nextClass).unbind();
jTabs.each(function(iTabIndex){
if(op.currentIndex==iTabIndex)$(this).addClass("selected");
else $(this).removeClass("selected");
if(op.eventType=="hover")$(this).hover(function(event){switchTab(jT,iTabIndex)});
else $(this).click(function(event){switchTab(jT,iTabIndex)});
$("a",this).each(function(){
if($(this).hasClass(op.ajaxClass)){
$(this).click(function(event){
var jGroup=jGroups.eq(iTabIndex);
if(this.href)jGroup.loadUrl(this.href,{},function(){
jGroup.find("[layoutH]").layoutH();});
event.preventDefault();});
if(op.currentIndex==iTabIndex){$(this).trigger("click");}}else if($(this).hasClass(op.closeClass)){
$(this).click(function(event){
jTabs.eq(iTabIndex).remove();
jGroups.eq(iTabIndex).remove();
if(iTabIndex==op.currentIndex){
op.currentIndex=(iTabIndex+1<jTabs.size())?iTabIndex:iTabIndex-1;}else if(iTabIndex<op.currentIndex){
op.currentIndex=iTabIndex;}
initTab(jT);
return false;});}});});
switchTab(jT,op.currentIndex);}
function switchTab(jT,iTabIndex){
var jSelector=jT.add($("> *",jT));
var jTabHeader=$(op.stTabHeader,jSelector);
var jTabs=$(op.stTab+" li",jTabHeader);
var jGroups=$(op.stTabPanel+" > *",jSelector);
var jTab=jTabs.eq(iTabIndex);
var jGroup=jGroups.eq(iTabIndex);
if(op.reverse&&(jTab.hasClass("selected"))){
jTabs.removeClass("selected");
jGroups.hide();}else{
op.currentIndex=iTabIndex;
jTabs.removeClass("selected");
jTab.addClass("selected");
jGroups.hide().eq(op.currentIndex).show();}
if(!jGroup.attr("inited")){
jGroup.attr("inited",1000).find("input[type=text]").filter("[alt]").inputAlert();}}}});})(jQuery);(function($){
$.fn.extend({jresize:function(options){
if(typeof options=='string'){
if(options=='destroy')
return this.each(function(){
var dialog=this;
$("div[class^='resizable']",dialog).each(function(){
$(this).hide();});});}
return this.each(function(){
var dialog=$(this);
var resizable=$(".resizable");
$("div[class^='resizable']",dialog).each(function(){
var bar=this;
$(bar).mousedown(function(event){
$.pdialog.switchDialog(dialog);
$.resizeTool.start(resizable,dialog,event,$(bar).attr("tar"));
return false;}).show();});});}});
$.resizeTool={
start:function(resizable,dialog,e,target){
$.pdialog.initResize(resizable,dialog,target);
$.data(resizable[0],'layer-drag',{
options:$.extend($.pdialog._op,{target:target,dialog:dialog,stop:$.resizeTool.stop})});
$.layerdrag.start(resizable[0],e,$.pdialog._op);},
stop:function(){
var data=$.data(arguments[0],'layer-drag');
$.pdialog.resizeDialog(arguments[0],data.options.dialog,data.options.target);
$("body").css("cursor","");
$(arguments[0]).hide();}};
$.layerdrag={
start:function(obj,e,options){
if(!$.layerdrag.current){
$.layerdrag.current={
el:obj,
oleft:parseInt(obj.style.left)||0,
owidth:parseInt(obj.style.width)||0,
otop:parseInt(obj.style.top)||0,
oheight:parseInt(obj.style.height)||0,
ox:e.pageX||e.screenX,
oy:e.pageY||e.clientY};
$(document).bind('mouseup',$.layerdrag.stop);
$(document).bind('mousemove',$.layerdrag.drag);}
return $.layerdrag.preventEvent(e);},
drag:function(e){
if(!e)var e=window.event;
var current=$.layerdrag.current;
var data=$.data(current.el,'layer-drag');
var lmove=(e.pageX||e.screenX)-current.ox;
var tmove=(e.pageY||e.clientY)-current.oy;
if((e.pageY||e.clientY)<=0||(e.pageY||e.clientY)>=($(window).height()-$(".dialogHeader",$(data.options.dialog)).outerHeight()))return false;
var target=data.options.target;
var width=current.owidth;
var height=current.oheight;
if(target!="n"&&target!="s"){
width+=(target.indexOf("w")>=0)?-lmove:lmove;}
if(width>=$.pdialog._op.minW){
if(target.indexOf("w")>=0){
current.el.style.left=(current.oleft+lmove)+'px';}
if(target!="n"&&target!="s"){
current.el.style.width=width+'px';}}
if(target!="w"&&target!="e"){
height+=(target.indexOf("n")>=0)?-tmove:tmove;}
if(height>=$.pdialog._op.minH){
if(target.indexOf("n")>=0){
current.el.style.top=(current.otop+tmove)+'px';}
if(target!="w"&&target!="e"){
current.el.style.height=height+'px';}}
return $.layerdrag.preventEvent(e);},
stop:function(e){
var current=$.layerdrag.current;
var data=$.data(current.el,'layer-drag');
$(document).unbind('mousemove',$.layerdrag.drag);
$(document).unbind('mouseup',$.layerdrag.stop);
if(data.options.stop){
data.options.stop.apply(current.el,[current.el]);}
$.layerdrag.current=null;
return $.layerdrag.preventEvent(e);},
preventEvent:function(e){
if(e.stopPropagation)e.stopPropagation();
if(e.preventDefault)e.preventDefault();
return false;}};})(jQuery);(function($){
$.pdialog={
_op:{height:300,width:500,minH:40,minW:50,total:20,max:false,mask:false,resizable:true,drawable:true,maxable:true,minable:true,flesh:true},
_current:null,
_zIndex:42,
getCurrent:function(){
return this._current;},
reload:function(url,data,dlgid){
var dialog=(dlgid&&$("body").data(dlgid))||this._current;
if(dialog){
var jDContent=dialog.find(".dialogContent");
jDContent.loadUrl(url,data,function(){
jDContent.find("[layoutH]").layoutH(jDContent);
$(".pageContent",dialog).width($(dialog).width()-14);
$(":button.close",dialog).click(function(){
$.pdialog.close(dialog);
return false;});});}},
open:function(url,dlgid,title,options){
var op=$.extend({},$.pdialog._op,options);
var dialog=$("body").data(dlgid);
if(dialog){
if(dialog.is(":hidden")){
dialog.show();}
if(op.flesh||url!=$(dialog).data("url")){
dialog.data("url",url);
dialog.find(".dialogHeader").find("h1").html(title);
this.switchDialog(dialog);
var jDContent=dialog.find(".dialogContent");
jDContent.loadUrl(url,{},function(){
jDContent.find("[layoutH]").layoutH(jDContent);
$(".pageContent",dialog).width($(dialog).width()-14);
$("button.close").click(function(){
$.pdialog.close(dialog);
return false;});});}}else{
$("body").append(DWZ.frag["dialogFrag"]);
dialog=$(">.dialog:last-child","body");
dialog.data("id",dlgid);
dialog.data("url",url);
if(options.close)dialog.data("close",options.close);
if(options.param)dialog.data("param",options.param);($.fn.bgiframe&&dialog.bgiframe());
dialog.find(".dialogHeader").find("h1").html(title);
$(dialog).css("zIndex",($.pdialog._zIndex+=2));
$("div.shadow").css("zIndex",$.pdialog._zIndex-3).show();
$.pdialog._init(dialog,options);
$(dialog).click(function(){
$.pdialog.switchDialog(dialog);});
if(op.resizable)
dialog.jresize();
if(op.drawable)
dialog.dialogDrag();
$("a.close",dialog).click(function(event){
$.pdialog.close(dialog);
return false;});
if(op.maxable){
$("a.maximize",dialog).show().click(function(event){
$.pdialog.switchDialog(dialog);
$.pdialog.maxsize(dialog);
dialog.jresize("destroy").dialogDrag("destroy");
return false;});}else{
$("a.maximize",dialog).hide();}
$("a.restore",dialog).click(function(event){
$.pdialog.restore(dialog);
dialog.jresize().dialogDrag();
return false;});
if(op.minable){
$("a.minimize",dialog).show().click(function(event){
$.pdialog.minimize(dialog);
return false;});}else{
$("a.minimize",dialog).hide();}
$("div.dialogHeader a",dialog).mousedown(function(){
return false;});
$("div.dialogHeader",dialog).dblclick(function(){
if($("a.restore",dialog).is(":hidden"))
$("a.maximize",dialog).trigger("click");
else
$("a.restore",dialog).trigger("click");});
if(op.max){
$.pdialog.switchDialog(dialog);
$.pdialog.maxsize(dialog);
dialog.jresize("destroy").dialogDrag("destroy");}
$("body").data(dlgid,dialog);
$.pdialog._current=dialog;
$.pdialog.attachShadow(dialog);
var jDContent=$(".dialogContent",dialog);
jDContent.loadUrl(url,{},function(){
jDContent.find("[layoutH]").layoutH(jDContent);
$(".pageContent",dialog).width($(dialog).width()-14);
$("button.close").click(function(){
$.pdialog.close(dialog);
return false;});});}
if(op.mask){
$(dialog).css("zIndex",1000);
$("a.minimize",dialog).hide();
$(dialog).data("mask",true);
$("#dialogBackground").show();}else{
if(op.minable)$.taskBar.addDialog(dlgid,title);}},
switchDialog:function(dialog){
var index=$(dialog).css("zIndex");
$.pdialog.attachShadow(dialog);
if($.pdialog._current){
var cindex=$($.pdialog._current).css("zIndex");
$($.pdialog._current).css("zIndex",index);
$(dialog).css("zIndex",cindex);
$("div.shadow").css("zIndex",cindex-1);
$.pdialog._current=dialog;}
$.taskBar.switchTask(dialog.data("id"));},
attachShadow:function(dialog){
var shadow=$("div.shadow");
if(shadow.is(":hidden"))shadow.show();
shadow.css({
top:parseInt($(dialog)[0].style.top)-2,
left:parseInt($(dialog)[0].style.left)-4,
height:parseInt($(dialog).height())+8,
width:parseInt($(dialog).width())+8,
zIndex:parseInt($(dialog).css("zIndex"))-1});
$(".shadow_c",shadow).children().andSelf().each(function(){
$(this).css("height",$(dialog).outerHeight()-4);});},
_init:function(dialog,options){
var op=$.extend({},this._op,options);
var height=op.height>op.minH?op.height:op.minH;
var width=op.width>op.minW?op.width:op.minW;
if(isNaN(dialog.height())||dialog.height()<height){
$(dialog).height(height+"px");
$(".dialogContent",dialog).height(height-$(".dialogHeader",dialog).outerHeight()-$(".dialogFooter",dialog).outerHeight()-6);}
if(isNaN(dialog.css("width"))||dialog.width()<width){
$(dialog).width(width+"px");}
var iTop=($(window).height()-dialog.height())/2;
dialog.css({
left:($(window).width()-dialog.width())/2,
top:iTop>0?iTop:0});},
initResize:function(resizable,dialog,target){
$("body").css("cursor",target+"-resize");
resizable.css({
top:$(dialog).css("top"),
left:$(dialog).css("left"),
height:$(dialog).css("height"),
width:$(dialog).css("width")});
resizable.show();},
repaint:function(target,options){
var shadow=$("div.shadow");
if(target!="w"&&target!="e"){
shadow.css("height",shadow.outerHeight()+options.tmove);
$(".shadow_c",shadow).children().andSelf().each(function(){
$(this).css("height",$(this).outerHeight()+options.tmove);});}
if(target=="n"||target=="nw"||target=="ne"){
shadow.css("top",options.otop-2);}
if(options.owidth&&(target!="n"||target!="s")){
shadow.css("width",options.owidth+8);}
if(target.indexOf("w")>=0){
shadow.css("left",options.oleft-4);}},
resizeTool:function(target,tmove,dialog){
$("div[class^='resizable']",dialog).filter(function(){
return $(this).attr("tar")=='w'||$(this).attr("tar")=='e';}).each(function(){
$(this).css("height",$(this).outerHeight()+tmove);});},
resizeDialog:function(obj,dialog,target){
var oleft=parseInt(obj.style.left);
var otop=parseInt(obj.style.top);
var height=parseInt(obj.style.height);
var width=parseInt(obj.style.width);
if(target=="n"||target=="nw"){
tmove=parseInt($(dialog).css("top"))-otop;}else{
tmove=height-parseInt($(dialog).css("height"));}
$(dialog).css({left:oleft,width:width,top:otop,height:height});
$(".dialogContent",dialog).css("width",(width-12)+"px");
$(".pageContent",dialog).css("width",(width-14)+"px");
if(target!="w"&&target!="e"){
var content=$(".dialogContent",dialog);
content.css({height:height-$(".dialogHeader",dialog).outerHeight()-$(".dialogFooter",dialog).outerHeight()-6});
content.find("[layoutH]").layoutH(content);
$.pdialog.resizeTool(target,tmove,dialog);}
$.pdialog.repaint(target,{oleft:oleft,otop:otop,tmove:tmove,owidth:width});
$(window).trigger("resizeGrid");},
close:function(dialog){
if(typeof dialog=='string')dialog=$("body").data(dialog);
var close=dialog.data("close");
var go=true;
if(close&&$.isFunction(close)){
var param=dialog.data("param");
if(param&&param!=""){
param=DWZ.jsonEval(param);
go=close(param);}else{
go=close();}
if(!go)return;}
if($.fn.xheditor){
$("textarea.editor",dialog).xheditor(false);}
$(dialog).unbind("click").hide();
$("div.dialogContent",dialog).html("");
$("div.shadow").hide();
if($(dialog).data("mask")){
$("#dialogBackground").hide();}else{
$.taskBar.closeDialog($(dialog).data("id"));}
$("body").removeData($(dialog).data("id"));
$(dialog).remove();},
closeCurrent:function(){
this.close($.pdialog._current);},
maxsize:function(dialog){
$(dialog).data("original",{
top:$(dialog).css("top"),
left:$(dialog).css("left"),
width:$(dialog).css("width"),
height:$(dialog).css("height")});
$("a.maximize",dialog).hide();
$("a.restore",dialog).show();
var iContentW=$(window).width();
var iContentH=$(window).height()-34;
$(dialog).css({top:"0px",left:"0px",width:iContentW+"px",height:iContentH+"px"});
$.pdialog._resizeContent(dialog,iContentW,iContentH);},
restore:function(dialog){
var original=$(dialog).data("original");
var dwidth=parseInt(original.width);
var dheight=parseInt(original.height);
$(dialog).css({
top:original.top,
left:original.left,
width:dwidth,
height:dheight});
$.pdialog._resizeContent(dialog,dwidth,dheight);
$("a.maximize",dialog).show();
$("a.restore",dialog).hide();
$.pdialog.attachShadow(dialog);},
minimize:function(dialog){
$(dialog).hide();
$("div.shadow").hide();
var task=$.taskBar.getTask($(dialog).data("id"));
$(".resizable").css({
top:$(dialog).css("top"),
left:$(dialog).css("left"),
height:$(dialog).css("height"),
width:$(dialog).css("width")}).show().animate({top:$(window).height()-60,left:task.position().left,width:task.outerWidth(),height:task.outerHeight()},250,function(){
$(this).hide();
$.taskBar.inactive($(dialog).data("id"));});},
_resizeContent:function(dialog,width,height){
var content=$(".dialogContent",dialog);
content.css({width:(width-12)+"px",height:height-$(".dialogHeader",dialog).outerHeight()-$(".dialogFooter",dialog).outerHeight()-6});
content.find("[layoutH]").layoutH(content);
$(".pageContent",dialog).css("width",(width-14)+"px");
$(window).trigger("resizeGrid");}};})(jQuery);(function($){
$.fn.dialogDrag=function(options){
if(typeof options=='string'){
if(options=='destroy')
return this.each(function(){
var dialog=this;
$("div.dialogHeader",dialog).unbind("mousedown");});}
return this.each(function(){
var dialog=$(this);
$("div.dialogHeader",dialog).mousedown(function(e){
$.pdialog.switchDialog(dialog);
dialog.data("task",true);
setTimeout(function(){
if(dialog.data("task"))$.dialogDrag.start(dialog,e);},100);
return false;}).mouseup(function(e){
dialog.data("task",false);
return false;});});};
$.dialogDrag={
currId:null,
_init:function(dialog){
this.currId=new Date().getTime();
var shadow=$("#dialogProxy");
if(!shadow.size()){
shadow=$(DWZ.frag["dialogProxy"]);
$("body").append(shadow);}
$("h1",shadow).html($(".dialogHeader h1",dialog).text());},
start:function(dialog,event){
this._init(dialog);
var sh=$("#dialogProxy");
sh.css({
left:dialog.css("left"),
top:dialog.css("top"),
height:dialog.css("height"),
width:dialog.css("width"),
zIndex:parseInt(dialog.css("zIndex"))+1}).show();
$("div.dialogContent",sh).css("height",$("div.dialogContent",dialog).css("height"));
sh.data("dialog",dialog);
dialog.css({left:"-10000px",top:"-10000px"});
$(".shadow").hide();
$(sh).jDrag({
selector:".dialogHeader",
stop:this.stop,
event:event});
return false;},
stop:function(){
var sh=$(arguments[0]);
var dialog=sh.data("dialog");
$(dialog).css({left:$(sh).css("left"),top:$(sh).css("top")});
$.pdialog.attachShadow(dialog);
$(sh).hide();}}})(jQuery);(function($){
$.fn.extend({
cssTable:function(options){
var op=$.extend({scrollBox:"tableList"},options);
return this.each(function(){
var $this=$(this);
var $trs=$this.find('tbody>tr');
if(!$this.parent().hasClass(op.scrollBox)){
var lh=$this.attr('layoutH');
$this.removeAttr('layoutH');
$this.wrap('<div class="'+op.scrollBox+'"'+(lh?' layoutH="'+lh+'"':'')+'></div>');}
var $grid=$this.parent();
$trs.hover(function(){
$(this).addClass('hover');},function(){
$(this).removeClass('hover');}).each(function(index){
var $tr=$(this);
if(index%2==1)$tr.addClass("trbg");
$tr.click(function(){
$trs.filter(".selected").removeClass("selected");
$tr.addClass("selected");
var sTarget=$tr.attr("target");
if(sTarget){
if($("#"+sTarget,$grid).size()==0){
$grid.prepend('<input id="'+sTarget+'" type="hidden" />');}
$("#"+sTarget,$grid).val($tr.attr("rel"));}});});});}});})(jQuery);(function($){
$.fn.extend({jTable:function(options){
return this.each(function(){
var table=this;
var tlength=$(table).width();
var aStyles=[];
var $tc=$(table).parent();
var layoutH=$(this).attr("layoutH");
var flength=$tc.innerWidth();
var padleft=parseInt($tc.css("padding-left"));
var padright=parseInt($tc.css("padding-right"));
var brwidth=parseInt($tc.css("border-right-width"));
if(isNaN(brwidth))brwidth=0;
var blwidth=parseInt($tc.css("border-left-width"));
if(isNaN(blwidth))blwidth=0;
var oldThs=$(table).find("thead>tr:last-child").find("th");
for(var i=0,l=oldThs.size();i<l;i++){
var $th=$(oldThs[i]);
var style=[];
style[0]=parseInt($th.width()*(flength-34)/tlength)-10;
style[1]=$th.attr("align");
aStyles[aStyles.length]=style;}
$(this).wrap("<div class='grid'></div>");
var $grid=$(table).parent();
$grid.html($(table).html());
var thead=$grid.find("thead");
thead.wrap("<div class='gridHeader'><div class='gridThead'><table style='width:"+(flength-34)+"px;'></table></div></div>");
var lastH=$(">tr:last-child",thead);
var ths=$(">th",lastH);
$("th",thead).each(function(){
$(this).html("<div class='gridCol'>"+$(this).html()+"</div>");});
for(var i=0,l=ths.size();i<l;i++){
var $th=$(ths[i]);
$th.html("<div class='gridCol' title='"+$th.text()+"'>"+$th.html()+"</div>");}
setTimeout(function(){
ths.each(function(i){
var $th=$(this);
var style=aStyles[i];
$th.addClass(style[1])
.removeAttr("align")
.hoverClass("hover")
.removeAttr("width")
.width(style[0]);});},1);
var tbody=$grid.find(">tbody");
var layoutStr=layoutH?" layoutH='"+layoutH+"'":"";
tbody.wrap("<div class='gridScroller'"+layoutStr+" style='width:"+(flength-(padleft+padright)-(brwidth+blwidth))+"px;'><div class='gridTbody'><table style='width:"+(flength-34)+"px;'></table></div></div>");
var ftr=$(">tr:first-child",tbody);
var $trs=tbody.find('>tr');
$trs.hoverClass().each(function(){
var $tr=$(this);
var $ftds=$(">td",this);
var i=0;
for(var i=0;i<$ftds.size();i++){
var $ftd=$($ftds[i]);
$ftd.html("<div>"+$ftd.html()+"</div>");
$ftd.addClass(aStyles[i][1]);}
$tr.click(function(){
$trs.filter(".selected").removeClass("selected");
$tr.addClass("selected");
var sTarget=$tr.attr("target");
if(sTarget){
if($("#"+sTarget,$grid).size()==0){
$grid.prepend('<input id="'+sTarget+'" type="hidden" />');}
$("#"+sTarget,$grid).val($tr.attr("rel"));}});});
$(">td",ftr).each(function(i){
$(this).width(aStyles[i][0]);});
$grid.append("<div class='resizeMarker' style='height:300px; left:57px;display:none;'></div><div class='resizeProxy' style='height:300px; left:377px;display:none;'></div>");
setTimeout(function(){
var scroller=$(".gridScroller",$grid);
scroller.scroll(function(event){
var header=$(".gridThead",$grid);
if(scroller.scrollLeft()>0){
header.css("position","relative");
var scroll=scroller.scrollLeft();
header.css("left",scroller.cssv("left")-scroll);}
if(scroller.scrollLeft()==0){
header.css("position","relative");
header.css("left","0px");}
return false;});},1);
setTimeout(function(){
$(">tr",thead).each(function(){
var tr=this;
var subTitle=$(tr).next();
$(">th",this).each(function(i){
var th=this;
$(th).mouseover(function(event){
var offset=$.jTableTool.getOffset(th,event).offsetX;
if($(th).outerWidth()-offset<5){
$(th).css("cursor","col-resize")
.mousedown(function(event){
$(".resizeProxy",$grid).show().css({
left:$.jTableTool.getRight(th)-$(".gridScroller",$grid).scrollLeft(),
top:$.jTableTool.getTop(th),
height:$.jTableTool.getHeight(th,$grid),
cursor:"col-resize"});
$(".resizeMarker",$grid).show().css({
left:$.jTableTool.getLeft(th)+1-$(".gridScroller",$grid).scrollLeft(),
top:$.jTableTool.getTop(th),
height:$.jTableTool.getHeight(th,$grid)});
$(".resizeProxy",$grid).jDrag($.extend(options,{scop:true,cellMinW:20,relObj:$(".resizeMarker",$grid)[0],
move:"horizontal",
event:event,
stop:function(){
var pleft=$(".resizeProxy",$grid).position().left;
var mleft=$(".resizeMarker",$grid).position().left;
var move=pleft-mleft-$(th).outerWidth()-9;
var tbparent=$("table",$grid);
var cols=$.jTableTool.getColspan($(th));
var cellNum=$.jTableTool.getCellNum($(th));
var start=$.jTableTool.getStart($(th));
var totalW=0;
var cellW=[];
if(subTitle[0]){
var $ths=$(">th",subTitle);
for(var i=start-1,j=0;j<cols;j++){
var wd=$(">div",$ths.eq(i+j)).outerWidth();
cellW[cellW.length]=wd;
totalW+=wd;}
for(var i=start-1,j=0;j<cols;j++){
$ths.eq(i+j).css("width",cellW[j]+parseInt((cellW[j]*move/totalW).toFixed(0)));}}else{
$(th).width($(th).width()+move);}
var $lastH=$(">tr:last-child",thead);
var tds=$(">td",ftr);
var $dcell=$(tds).eq(cellNum-1);
$dcell.css("width",$(">th",$lastH).eq(cellNum-1).css("width"));
$(".resizeMarker,.resizeProxy",$grid).hide();}}));});}else{
$(th).css("cursor","default");
$(th).unbind("mousedown");}
return false;});});});},1);
$(window).bind("resizeGrid",function(){
var flength=$tc.innerWidth();
var brwidth=parseInt($tc.css("border-right-width"));
if(isNaN(brwidth))brwidth=0;
var blwidth=parseInt($tc.css("border-left-width"));
if(isNaN(blwidth))blwidth=0;
$grid.width(flength-(brwidth+blwidth));
$(".gridScroller",$grid).width(flength-(brwidth+blwidth));});});}});
$.jTableTool={
getLeft:function(obj){
var width=0;
$(obj).prevAll().each(function(){
width+=$(this).outerWidth();});
return width-1;},
getRight:function(obj){
var width=0;
$(obj).prevAll().andSelf().each(function(){
width+=$(this).outerWidth();});
return width-1;},
getTop:function(obj){
var height=0;
$(obj).parent().prevAll().each(function(){
height+=$(this).outerHeight();});
return height;},
getHeight:function(obj,parent){
var height=0;
var head=$(obj).parent();
head.nextAll().andSelf().each(function(){
height+=$(this).outerHeight();});
$(".gridTbody",parent).children().each(function(){
height+=$(this).outerHeight();});
return height;},
getCellNum:function(obj){
return $(obj).prevAll().andSelf().size();},
getColspan:function(obj){
return $(obj).attr("colspan")||1;},
getStart:function(obj){
var start=1;
$(obj).prevAll().each(function(){
start+=parseInt($(this).attr("colspan")||1);});
return start;},
getPageCoord:function(element){
var coord={x:0,y:0};
while(element){
coord.x+=element.offsetLeft;
coord.y+=element.offsetTop;
element=element.offsetParent;}
return coord;},
getOffset:function(obj,evt){
if($.browser.msie){
var objset=$(obj).offset();
var evtset={
offsetX:evt.pageX||evt.screenX,
offsetY:evt.pageY||evt.screenY};
var offset={
offsetX:evtset.offsetX-objset.left,
offsetY:evtset.offsetY-objset.top};
return offset;}
var target=evt.target;
if(target.offsetLeft==undefined){
target=target.parentNode;}
var pageCoord=$.jTableTool.getPageCoord(target);
var eventCoord={
x:window.pageXOffset+evt.clientX,
y:window.pageYOffset+evt.clientY};
var offset={
offsetX:eventCoord.x-pageCoord.x,
offsetY:eventCoord.y-pageCoord.y};
return offset;}}})(jQuery);(function($){
$.fn.extend({jTask:function(options){
return this.each(function(){
var task=this;
var id=$(task).attr("id");
$(task).click(function(e){
var dialog=$("body").data(id);
if($(task).hasClass("selected")){
$("a.minimize",dialog).trigger("click");}else{
if(dialog.is(":hidden")){
$.taskBar.restoreDialog(dialog);}else
$(dialog).trigger("click");}
$.taskBar.scrollCurrent($(this));
return false;});
$("div.close",task).click(function(e){
$.pdialog.close(id)
return false;}).hoverClass("closeHover");});}});
$.taskBar={
_taskBar:null,
_taskBox:null,
_prevBut:null,
_nextBut:null,
_op:{id:"taskbar",taskBox:"div.taskbarContent",prevBut:".taskbarLeft",prevDis:"taskbarLeftDisabled",nextBut:".taskbarRight",nextDis:"taskbarRightDisabled",selected:"selected",boxMargin:"taskbarMargin"},
init:function(options){
var $this=this;
$.extend(this._op,options);
this._taskBar=$("#"+this._op.id);
this._taskBox=this._taskBar.find(this._op.taskBox);
this._taskList=this._taskBox.find(">ul");
this._prevBut=this._taskBar.find(this._op.prevBut);
this._nextBut=this._taskBar.find(this._op.nextBut);
this._prevBut.click(function(e){$this.scrollLeft()});
this._nextBut.click(function(e){$this.scrollRight()});
this._contextmenu(this._taskBox);},
_contextmenu:function(obj){
$(obj).contextMenu('dialogCM',{
bindings:{
closeCurrent:function(t,m){
var obj=t.isTag("li")?t:$.taskBar._getCurrent();
$("div.close",obj).trigger("click");},
closeOther:function(t,m){
var selector=t.isTag("li")?("#"+t.attr("id")):".selected";
var tasks=$.taskBar._taskList.find(">li:not(:"+selector+")");
tasks.each(function(i){
$("div.close",tasks[i]).trigger("click");});},
closeAll:function(t,m){
var tasks=$.taskBar._getTasks();
tasks.each(function(i){
$("div.close",tasks[i]).trigger("click");});}},
ctrSub:function(t,m){
var mCur=m.find("[rel='closeCurrent']");
var mOther=m.find("[rel='closeOther']");
if(!$.taskBar._getCurrent()[0]){
mCur.addClass("disabled");
mOther.addClass("disabled");}else{
if($.taskBar._getTasks().size()==1)
mOther.addClass("disabled");}}});},
_scrollCurrent:function(){
var iW=this._tasksW(this._getTasks());
if(iW>this._getTaskBarW()){
var $this=this;
var lTask=$(">li:last-child",this._taskList);
var left=this._getTaskBarW()-lTask.position().left-lTask.outerWidth(true);
this._taskList.animate({
left:left+'px'},200,function(){
$this._ctrlScrollBut();});}else{
this._ctrlScrollBut();}},
_getTaskBarW:function(){
return this._taskBox.width()-(this._prevBut.is(":hidden")?this._prevBut.width()+2:0)-(this._nextBut.is(":hidden")?this._nextBut.width()+2:0);},
_scrollTask:function(task){
var $this=this;
if(task.position().left+this._getLeft()+task.outerWidth()>this._getBarWidth()){
var left=this._getTaskBarW()-task.position().left-task.outerWidth(true)-2;
this._taskList.animate({left:left+'px'},200,function(){
$this._ctrlScrollBut();});}else if(task.position().left+this._getLeft()<0){
var left=this._getLeft()-(task.position().left+this._getLeft());
this._taskList.animate({left:left+'px'},200,function(){
$this._ctrlScrollBut();});}},
_ctrlScrollBut:function(){
var iW=this._tasksW(this._getTasks());
if(this._getTaskBarW()>iW){
this._taskBox.removeClass(this._op.boxMargin);
this._nextBut.hide();
this._prevBut.hide();
if(this._getTasks().eq(0)[0])this._scrollTask(this._getTasks().eq(0));}else{
this._taskBox.addClass(this._op.boxMargin);
this._nextBut.show().removeClass(this._op.nextDis);
this._prevBut.show().removeClass(this._op.prevDis);
if(this._getLeft()>=0){
this._prevBut.addClass(this._op.prevDis);}
if(this._getLeft()<=this._getTaskBarW()-iW){
this._nextBut.addClass(this._op.nextDis);}}},
_getLeft:function(){
return this._taskList.position().left;},
_visibleStart:function(){
var iLeft=this._getLeft();
var jTasks=this._getTasks();
for(var i=0;i<jTasks.size();i++){
if(jTasks.eq(i).position().left+jTasks.eq(i).outerWidth(true)+iLeft>=0)return jTasks.eq(i);}
return jTasks.eq(0);},
_visibleEnd:function(){
var iLeft=this._getLeft();
var jTasks=this._getTasks();
for(var i=0;i<jTasks.size();i++){
if(jTasks.eq(i).position().left+jTasks.eq(i).outerWidth(true)+iLeft>this._getBarWidth())return jTasks.eq(i);}
return jTasks.eq(jTasks.size()-1);},
_getTasks:function(){
return this._taskList.find(">li");},
_tasksW:function(jTasks){
var iW=0;
jTasks.each(function(){
iW+=$(this).outerWidth(true);});
return iW;},
_getBarWidth:function(){
return this._taskBar.innerWidth(true);},
addDialog:function(id,title){
this.show();
var task=$("#"+id,this._taskList);
if(!task[0]){
var taskFrag='<li id="#taskid#"><div class="taskbutton"><span>#title#</span></div><div class="close">Close</div></li>';
this._taskList.append(taskFrag.replace("#taskid#",id).replace("#title#",title));
task=$("#"+id,this._taskList);
task.jTask();}else{
$(">div>span",task).text(title);}
this._contextmenu(task);
this.switchTask(id);
this._scrollTask(task);},
closeDialog:function(obj){
var task=(typeof obj=='string')?$("#"+obj,this._taskList):obj;
task.remove();
if(this._getTasks().size()==0){
this.hide();}
this._scrollCurrent();},
restoreDialog:function(obj){
var dialog=(typeof obj=='string')?$("body").data(obj):obj;
var id=(typeof obj=='string')?obj:dialog.data("id");
var task=$.taskBar.getTask(id);
$(".resizable").css({top:$(window).height()-60,left:$(task).position().left,height:$(task).outerHeight(),width:$(task).outerWidth()}).show().animate({top:$(dialog).css("top"),left:$(dialog).css("left"),width:$(dialog).css("width"),height:$(dialog).css("height")},250,function(){
$(this).hide();
$(dialog).show();
$.pdialog.attachShadow(dialog);});
$.taskBar.switchTask(id);},
inactive:function(id){
$("#"+id,this._taskList).removeClass("selected");},
scrollLeft:function(){
var task=this._visibleStart();
this._scrollTask(task);},
scrollRight:function(){
var task=this._visibleEnd();
this._scrollTask(task);},
scrollCurrent:function(task){
this._scrollTask(task);},
switchTask:function(id){
this._getCurrent().removeClass("selected");
this.getTask(id).addClass("selected");},
_getCurrent:function(){
return this._taskList.find(">.selected");},
getTask:function(id){
return $("#"+id,this._taskList);},
show:function(){
if(this._taskBar.is(":hidden")){
this._taskBar.css("top",$(window).height()-34+this._taskBar.outerHeight()).show();
this._taskBar.animate({
top:$(window).height()-this._taskBar.outerHeight()},500);}},
hide:function(){
this._taskBar.animate({
top:$(window).height()-29+this._taskBar.outerHeight(true)},500,function(){
$.taskBar._taskBar.hide();});}}})(jQuery);
function validateCallback(form,callback){
var $form=$(form);
if(!$form.valid()){
return false;}
$.ajax({
type:form.method||'POST',
url:$form.attr("action"),
data:$form.serializeArray(),
dataType:"json",
cache:false,
success:callback||DWZ.ajaxDone,
error:DWZ.ajaxError});
return false;}
function iframeCallback(form,callback){
if(!$(form).valid()){return false;}
window.donecallback=callback||DWZ.ajaxDone;
if($("#callbackframe").size()==0){
$("<iframe id='callbackframe' name='callbackframe' src='about:blank' style='display:none'></iframe>").appendTo("body");}
if(!form.ajax){
$(form).append('<input type="hidden" name="ajax" value="1" />');}
form.target="callbackframe";}
function navTabAjaxDone(json){
DWZ.ajaxDone(json);
if(json.statusCode==DWZ.statusCode.ok){
if(json.navTabId){
navTab.reloadFlag(json.navTabId);}else{
navTabPageBreak();}
if("closeCurrent"==json.callbackType){
setTimeout(function(){navTab.closeCurrentTab();},100);}else if("forward"==json.callbackType){
navTab.reload(json.forwardUrl);}}}
function dialogAjaxDone(json){
DWZ.ajaxDone(json);
if(json.statusCode==DWZ.statusCode.ok){
if(json.navTabId){
navTab.reload(json.forwardUrl,{},json.navTabId);}
$.pdialog.closeCurrent();}}
function navTabSearch(form,navTabId){
navTab.reload(form.action,$(form).serializeArray(),navTabId);
return false;}
function dialogSearch(form){
$.pdialog.reload(form.action,$(form).serializeArray());
return false;}
function _getPagerForm($parent,args){
var form=$("#pagerForm",$parent).get(0);
if(form){
args=args||{};
if(args["pageNum"])form.pageNum.value=args["pageNum"];
if(args["numPerPage"])form.numPerPage.value=args["numPerPage"];
if(args["orderField"])form.orderField.value=args["orderField"];}
return form;}
function navTabPageBreak(args){
var form=_getPagerForm(navTab.getCurrentPanel(),args);
if(form)navTab.reload(form.action,$(form).serializeArray());}
function dialogPageBreak(args){
var form=_getPagerForm($.pdialog.getCurrent(),args);
if(form)$.pdialog.reload(form.action,$(form).serializeArray());}
function navTabTodo(url,callback){
var $callback=callback||navTabAjaxDone;
if(!$.isFunction($callback))$callback=eval('('+callback+')');
$.ajax({
type:'POST',
url:url,
dataType:"json",
cache:false,
success:$callback,
error:DWZ.ajaxError});}
function uploadifyAllComplete(event,data){
if(data.errors){
var msg="The total number of files uploaded: "+data.filesUploaded+"\n"+"The total number of errors while uploading: "+data.errors+"\n"+"The total number of bytes uploaded: "+data.allBytesLoaded+"\n"+"The average speed of all uploaded files: "+data.speed;
alert("event:"+event+"\n"+msg);}}
function uploadifyComplete(event,queueId,fileObj,response,data){
DWZ.ajaxDone(DWZ.jsonEval(response));}
function uploadifyError(event,queueId,fileObj,errorObj){
alert("event:"+event+"\nqueueId:"+queueId+"\nfileObj.name:"+fileObj.name+"\nerrorObj.type:"+errorObj.type+"\nerrorObj.info:"+errorObj.info);}(function($){
$.fn.pagination=function(opts){
var setting={
first$:"li.j-first",prev$:"li.j-prev",next$:"li.j-next",last$:"li.j-last",nums$:"li.j-num>a",jumpto$:"li.jumpto",
pageNumFrag:'<li class="#liClass#"><a href="#">#pageNum#</a></li>'};
return this.each(function(){
var $this=$(this);
var pc=new Pagination(opts);
var interval=pc.getInterval();
var pageNumFrag='';
for(var i=interval.start;i<interval.end;i++){
pageNumFrag+=setting.pageNumFrag.replaceAll("#pageNum#",i).replaceAll("#liClass#",i==pc.getCurrentPage()?'selected j-num':'j-num');}
$this.html(DWZ.frag["pagination"].replaceAll("#pageNumFrag#",pageNumFrag).replaceAll("#currentPage#",pc.getCurrentPage())).find("li").hoverClass();
var $first=$this.find(setting.first$);
var $prev=$this.find(setting.prev$);
var $next=$this.find(setting.next$);
var $last=$this.find(setting.last$);
if(pc.hasPrev()){
$first.add($prev).find(">span").hide();
_bindEvent($prev,pc.getCurrentPage()-1,pc.targetType());
_bindEvent($first,1,pc.targetType());}else{
$first.add($prev).addClass("disabled").find(">a").hide();}
if(pc.hasNext()){
$next.add($last).find(">span").hide();
_bindEvent($next,pc.getCurrentPage()+1,pc.targetType());
_bindEvent($last,pc.numPages(),pc.targetType());}else{
$next.add($last).addClass("disabled").find(">a").hide();}
$this.find(setting.nums$).each(function(i){
_bindEvent($(this),i+interval.start,pc.targetType());});
$this.find(setting.jumpto$).each(function(){
var $this=$(this);
var $inputBox=$this.find(":text");
var $button=$this.find(":button");
$button.click(function(event){
var pageNum=$inputBox.val();
if(pageNum&&pageNum.isPositiveInteger()){
if(pc.targetType()=="dialog"){
dialogPageBreak({pageNum:pageNum});}else{
navTabPageBreak({pageNum:pageNum});}}});
$inputBox.keyup(function(event){
if(event.keyCode==DWZ.keyCode.ENTER)$button.click();});});});
function _bindEvent(jTarget,pageNum,targetType){
jTarget.bind("click",{pageNum:pageNum},function(event){
if(targetType=="dialog"){
dialogPageBreak({pageNum:event.data.pageNum});}else{
navTabPageBreak({pageNum:event.data.pageNum});}
event.preventDefault();});}}
var Pagination=function(opts){
this.opts=$.extend({
targetType:"navTab",
totalCount:0,
numPerPage:10,
pageNumShown:10,
currentPage:1,
callback:function(){return false;}},opts);}
$.extend(Pagination.prototype,{
targetType:function(){return this.opts.targetType},
numPages:function(){
return Math.ceil(this.opts.totalCount/this.opts.numPerPage);},
getInterval:function(){
var ne_half=Math.ceil(this.opts.pageNumShown/2);
var np=this.numPages();
var upper_limit=np-this.opts.pageNumShown;
var start=this.getCurrentPage()>ne_half?Math.max(Math.min(this.getCurrentPage()-ne_half,upper_limit),0):0;
var end=this.getCurrentPage()>ne_half?Math.min(this.getCurrentPage()+ne_half,np):Math.min(this.opts.pageNumShown,np);
return{start:start+1,end:end+1};},
getCurrentPage:function(){
var currentPage=parseInt(this.opts.currentPage);
if(isNaN(currentPage))return 1;
return currentPage;},
hasPrev:function(){
return this.getCurrentPage()>1;},
hasNext:function(){
return this.getCurrentPage()<this.numPages();}});})(jQuery);(function($){
$.setRegional("datepicker",{
dayNames:['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
monthNames:['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']});
$.fn.datepicker=function(opts){
var setting={
box$:"#calendar",
year$:"#calendar [name=year]",month$:"#calendar [name=month]",
tmInputs$:"#calendar .time :text",hour$:"#calendar .time .hh",minute$:"#calendar .time .mm",second$:"#calendar .time .ss",
tmBox$:"#calendar .tm",tmUp$:"#calendar .time .up",tmDown$:"#calendar .time .down",
close$:"#calendar .close",calIcon$:"a.inputDateButton",
days$:"#calendar .days",dayNames$:"#calendar .dayNames",
clearBut$:"#calendar .clearBut",okBut$:"#calendar .okBut"};
function changeTmMenu(sltClass){
var $tm=$(setting.tmBox$);
$tm.removeClass("hh").removeClass("mm").removeClass("ss");
if(sltClass){
$tm.addClass(sltClass);
$(setting.tmInputs$).removeClass("slt").filter("."+sltClass).addClass("slt");}}
function clickTmMenu($input,type){
$(setting.tmBox$).find("."+type+" li").each(function(){
var $li=$(this);
$li.click(function(){
$input.val($li.text());});});}
function keydownInt(e){
if(!((e.keyCode>=48&&e.keyCode<=57)||(e.keyCode==DWZ.keyCode.DELETE||e.keyCode==DWZ.keyCode.BACKSPACE))){return false;}}
function changeTm($input,type){
var ivalue=parseInt($input.val()),istart=parseInt($input.attr("start")),iend=parseInt($input.attr("end"));
if(type==1){
if(ivalue<iend){$input.val(ivalue+1);}}else if(type==-1){
if(ivalue>istart){$input.val(ivalue-1);}}else if(ivalue>iend){
$input.val(iend);}else if(ivalue<istart){
$input.val(istart);}}
return this.each(function(){
var $this=$(this);
var dp=new Datepicker($this.val(),opts);
function generateCalendar(dp){
var dw=dp.getDateWrap();
var monthStart=new Date(dw.year,dw.month-1,1);
var startDay=monthStart.getDay();
var dayStr="";
if(startDay>0){
monthStart.setMonth(monthStart.getMonth-1);
var prevDateWrap=dp.getDateWrap(monthStart);
for(var t=prevDateWrap.days-startDay;t<prevDateWrap.days;t++){
dayStr+='<dd class="other" chMonth="-1" day="'+t+'">'+t+'</dd>';}}
for(var t=1;t<=dw.days;t++){
if(t==dw.day){
dayStr+='<dd class="slt" day="'+t+'">'+t+'</dd>';}else{
dayStr+='<dd day="'+t+'">'+t+'</dd>';}}
for(var t=1;t<=42-startDay-dw.days;t++){
dayStr+='<dd class="other" chMonth="1" day="'+t+'">'+t+'</dd>';}
var $days=$(setting.days$).html(dayStr).find("dd");
$days.click(function(){
var $day=$(this);
$this.val(dp.formatDate(dp.changeDay($day.attr("day"),$day.attr("chMonth"))));
if(!dp.hasTime()){closeCalendar();}
else{
$days.removeClass("slt");
$day.addClass("slt");}});
if(dp.hasTime()){
$("#calendar .time").show();
var $hour=$(setting.hour$).val(dw.hour).focus(function(){
changeTmMenu("hh");});
var $minute=$(setting.minute$).val(dw.minute).focus(function(){
changeTmMenu("mm");});
var $second=$(setting.second$).val(dw.second).focus(function(){
changeTmMenu("ss");});
$hour.add($minute).add($second).click(function(){return false});
clickTmMenu($hour,"hh");
clickTmMenu($minute,"mm");
clickTmMenu($second,"ss");
$(setting.box$).click(function(){
changeTmMenu();});
var $inputs=$(setting.tmInputs$);
$inputs.keydown(keydownInt).each(function(){
var $input=$(this);
$input.keyup(function(){
changeTm($input,0);});});
$(setting.tmUp$).click(function(){
$inputs.filter(".slt").each(function(){
changeTm($(this),1);});});
$(setting.tmDown$).click(function(){
$inputs.filter(".slt").each(function(){
changeTm($(this),-1);});});
if(!dp.hasHour())$hour.attr("disabled",true);
if(!dp.hasMinute())$minute.attr("disabled",true);
if(!dp.hasSecond())$second.attr("disabled",true);}}
function closeCalendar(){
$(setting.box$).remove();
$(document).unbind("click",closeCalendar);}
$this.click(function(event){
closeCalendar();
var dp=new Datepicker($this.val(),opts);
var offset=$this.offset();
var iTop=offset.top+this.offsetHeight;
$(DWZ.frag['calendarFrag']).appendTo("body").css({
left:offset.left+'px',
top:iTop+'px'}).show().click(function(event){
event.stopPropagation();});($.fn.bgiframe&&$(setting.box$).bgiframe());
var dayNames="";
$.each($.regional.datepicker.dayNames,function(i,v){
dayNames+="<dt>"+v+"</dt>"});
$(setting.dayNames$).html(dayNames);
var dw=dp.getDateWrap();
var $year=$(setting.year$);
var yearstart=dw.year+parseInt(dp.get("yearstart"));
var yearend=dw.year+parseInt(dp.get("yearend"));
for(y=yearstart;y<=yearend;y++){
$year.append('<option value="'+y+'"'+(dw.year==y?'selected="selected"':'')+'>'+y+'</option>');}
var $month=$(setting.month$);
$.each($.regional.datepicker.monthNames,function(i,v){
var m=i+1;
$month.append('<option value="'+m+'"'+(dw.month==m?'selected="selected"':'')+'>'+v+'</option>');});
generateCalendar(dp);
$year.add($month).change(function(){
dp.changeDate($year.val(),$month.val());
generateCalendar(dp);});
var iBoxH=$(setting.box$).height();
if(iTop>iBoxH&&iTop>$(window).height()-iBoxH){
$(setting.box$).css("top",offset.top-iBoxH);}
$(setting.close$).click(function(){
closeCalendar();});
$(setting.clearBut$).click(function(){
$this.val("");
closeCalendar();});
$(setting.okBut$).click(function(){
var $dd=$(setting.days$).find("dd.slt");
var date=dp.changeDay($dd.attr("day"),$dd.attr("chMonth"));
if(dp.hasTime()){
date.setHours(parseInt($(setting.hour$).val()));
date.setMinutes(parseInt($(setting.minute$).val()));
date.setSeconds(parseInt($(setting.second$).val()));}
$this.val(dp.formatDate(date));
closeCalendar();});
$(document).bind("click",closeCalendar);
return false;});
$this.parent().find(setting.calIcon$).click(function(){
$this.trigger("click");
return false;});});}
var Datepicker=function(sDate,opts){
this.opts=$.extend({
pattern:'yyyy-MM-dd',
yearstart:-10,
yearend:10},opts);
this.sDate=sDate.trim();}
$.extend(Datepicker.prototype,{
get:function(name){
return this.opts[name];},
_getDays:function(y,m){
return m==2?(y%4||!(y%100)&&y%400?28:29):(/4|6|9|11/.test(m)?30:31);},
getDateWrap:function(date){
if(!date)date=this.sDate?this.parseDate(this.sDate):new Date();
var y=date.getFullYear();
var m=date.getMonth()+1;
var days=this._getDays(y,m);
return{
year:y,month:m,day:date.getDate(),
hour:date.getHours(),minute:date.getMinutes(),second:date.getSeconds(),
days:days,date:date}},
changeDate:function(y,m,d){
var date=new Date(y,m-1,d||1);
this.sDate=this.formatDate(date);
return date;},
changeDay:function(day,chMonth){
if(!chMonth)chMonth=0;
var dw=this.getDateWrap();
return this.changeDate(dw.year,dw.month+parseInt(chMonth),day);},
parseDate:function(sDate){
return sDate.parseDate(this.opts.pattern);},
formatDate:function(date){
return date.formatDate(this.opts.pattern);},
hasHour:function(){
return this.opts.pattern.indexOf("H")!=-1;},
hasMinute:function(){
return this.opts.pattern.indexOf("m")!=-1;},
hasSecond:function(){
return this.opts.pattern.indexOf("s")!=-1;},
hasTime:function(){
return this.hasHour()||this.hasMinute()||this.hasSecond();}});})(jQuery);(function($){
$.extend($.fn,{
jBlindUp:function(options){
var op=$.extend({duration:500,easing:"swing",call:function(){}},options);
return this.each(function(){
var $this=$(this);
$(this).animate({height:0},{
step:function(){},
duration:op.duration,
easing:op.easing,
complete:function(){
$this.css({display:"none"});
op.call();}});});},
jBlindDown:function(options){
var op=$.extend({to:0,duration:500,easing:"swing",call:function(){}},options);
return this.each(function(){
var $this=$(this);
var	fixedPanelHeight=(op.to>0)?op.to:$.effect.getDimensions($this[0]).height;
$this.animate({height:fixedPanelHeight},{
step:function(){},
duration:op.duration,
easing:op.easing,
complete:function(){
$this.css({display:""});
op.call();}});});},
jSlideUp:function(options){
var op=$.extend({to:0,duration:500,easing:"swing",call:function(){}},options);
return this.each(function(){
var $this=$(this);
$this.wrapInner("<div></div>");
var	fixedHeight=(op.to>0)?op.to:$.effect.getDimensions($(">div",$this)[0]).height;
$this.css({overflow:"visible",position:"relative"});
$(">div",$this).css({position:"relative"}).animate({top:-fixedHeight},{
easing:op.easing,
duration:op.duration,
complete:function(){$this.html($(this).html());}});
$this.animate({height:0},{
duration:op.duration,
easing:op.easing,
complete:function(){$this.css({display:"none",height:""});op.call();}});});},
jSlideDown:function(options){
var op=$.extend({to:0,duration:500,easing:"swing",call:function(){}},options);
return this.each(function(){
var $this=$(this);
var	fixedHeight=(op.to>0)?op.to:$.effect.getDimensions($this[0]).height;
$this.wrapInner("<div style=\"top:-" + fixedHeight + "px;\"></div>");
$this.css({overflow:"visible",position:"relative",height:"0px"})
.animate({height:fixedHeight},{
duration:op.duration,
easing:op.easing,
complete:function(){$this.css({display:"",overflow:""});op.call();}});
$(">div",$this).css({position:"relative"}).animate({top:0},{
easing:op.easing,
duration:op.duration,
complete:function(){$this.html($(this).html());}});});}});
$.effect={
getDimensions:function(element,displayElement){
var dimensions=new $.effect.Rectangle;
var displayOrig=$(element).css('display');
var visibilityOrig=$(element).css('visibility');
var isZero=$(element).height()==0?true:false;
if($(element).is(":hidden")){
$(element).css({visibility:'hidden',display:'block'});
if(isZero)$(element).css("height","");
if($.browser.opera)
refElement.focus();}
dimensions.height=$(element).height();
dimensions.width=$(element).width();
if(displayOrig=='none'){
$(element).css({visibility:visibilityOrig,display:'none'});
if(isZero)if(isZero)$(element).css("height","0px");}
return dimensions;}}
$.effect.Rectangle=function(){
this.width=0;
this.height=0;
this.unit="px";}})(jQuery);(function($){
$.extend($.fn,{
jPanel:function(options){
var op=$.extend({header:"panelHeader",headerC:"panelHeaderContent",content:"panelContent",coll:"collapsable",exp:"expandable",footer:"panelFooter",footerC:"panelFooterContent"},options);
return this.each(function(){
var $panel=$(this);
var close=$panel.hasClass("close");
var collapse=$panel.hasClass("collapse");
var $content=$(">div",$panel).addClass(op.content);
var title=$(">h1",$panel).wrap("<div><div></div></div>");
if(collapse)$("<a href=\"\"></a>").addClass(close?op.exp:op.coll).insertAfter(title);
var header=$(">div:first",$panel).addClass(op.header);
$(">div",header).addClass(op.headerC);
var footer=$("<div><div></div></div>").appendTo($panel).addClass(op.footer);
$(">div",footer).addClass(op.footerC);
var defaultH=$panel.attr("defH")?$panel.attr("defH"):0;
var minH=$panel.attr("minH")?$panel.attr("minH"):0;
if(close)
$content.css({
height:"0px",
display:"none"});
else{
$content.css("height","auto");
if(defaultH>0)
$content.height(defaultH+"px");
else if(minH>0){
$content.css("minHeight",minH+"px");}}
if(!collapse)return;
var $pucker=$("a",header);
var inH=$content.innerHeight()-6;
if(minH>0&&minH>=inH)defaultH=minH;
else defaultH=inH;
$pucker.click(function(){
if($pucker.hasClass(op.exp)){
$content.jBlindDown({to:defaultH,call:function(){
$pucker.removeClass(op.exp).addClass(op.coll);
if(minH>0)$content.css("minHeight",minH+"px");}});}else{
if(minH>0)$content.css("minHeight","");
if(minH>=inH)$content.css("height",minH+"px");
$content.jBlindUp({call:function(){
$pucker.removeClass(op.coll).addClass(op.exp);}});}
return false;});});}});})(jQuery);(function($){
$.fn.extend({
checkboxCtrl:function(parent){
return this.each(function(){
var $trigger=$(this);
$trigger.click(function(){
var group=$trigger.attr("group");
if($trigger.is(":checkbox")){
var type=$trigger.is(":checked")?"all":"none";
if(group)$.checkbox.select(group,type,parent);}else{
if(group)$.checkbox.select(group,$trigger.attr("selectType")||"all",parent);}});});}});
$.checkbox={
selectAll:function(_name,_parent){
this.select(_name,"all",_parent);},
unSelectAll:function(_name,_parent){
this.select(_name,"none",_parent);},
selectInvert:function(_name,_parent){
this.select(_name,"invert",_parent);},
select:function(_name,_type,_parent){
$parent=$(_parent||document);
$checkboxLi=$parent.find(":checkbox[name='"+_name+"']");
switch(_type){
case "invert":
$checkboxLi.each(function(){
$checkbox=$(this);
$checkbox.attr('checked',!$checkbox.is(":checked"));});
break;
case "none":
$checkboxLi.attr('checked',false);
break;
default:
$checkboxLi.attr('checked',true);
break;}}};})(jQuery);(function($){
var allSelectBox=[];
$.extend($.fn,{
comboxSelect:function($box,options){
var op=$.extend({selector:">a"},options);
var killAllBox=function(bid){
$.each(allSelectBox,function(i){
if(allSelectBox[i]!=bid){
if(!$("#"+allSelectBox[i])[0]){
$("#op"+allSelectBox[i]).remove();}else
$("#op"+allSelectBox[i]).css({
height:"",
width:""}).hide();}});}
return this.each(function(){
var box=$(this);
var selector=$(op.selector,box);
box.append("<input type='hidden' name='"+selector.attr("name")+"' value='"+selector.attr("value")+"'/>")
.data("title",selector.text());
allSelectBox.push(box.attr("id"));
$(op.selector,box).click(function(){
var options=$("#op"+box.attr("id"));
if(options.is(":hidden")){
if(options.height()>300){
options.css({height:"300px",overflow:"scroll"});
options.css("width",options.width()+20);}
var top=box.offset().top+box[0].offsetHeight-50;
if(top+options.height()>$(window).height()-20){
top=$(window).height()-20-options.height();}
options.css({top:top,left:box.offset().left}).show();
killAllBox(box.attr("id"));
$(document).click(killAllBox);}else{
$(document).unbind("click",killAllBox);
killAllBox();}
return false;});
$("#op"+box.attr("id")).find(">li").comboxOption(selector,box);});},
comboxOption:function(selector,box){
selector.text(box.data("title"));
$("input[name="+selector.attr("name")+"]",box).attr("value","");
return this.each(function(){
$(">a",this).click(function(){
var $this=$(this);
$this.parent().parent().find(".selected").removeClass("selected");
$this.addClass("selected");
selector.text($this.text());
var property=$("input[name="+selector.attr("name")+"]",box);
if(property.val()!=$this.attr("value")){
var change=eval(selector.attr("change"));
if($.isFunction(change)){
var param=box.attr("param");
var rel=box.attr("rel");
var args=(!rel&&param)?DWZ.jsonEval("{"+param+":"+$this.attr("value")+"}"):$this.attr("value");
var options=change(args);
if(rel){
var html="";
for(var i=0;i<options.length;i++){
html+="<li><a href=\"#\" value=\"" + options[i][0] + "\">"+options[i][1]+"</a></li>";}
var relObj=$(".combox").find(">div[name="+box.attr("rel")+"]");
options=$("#op"+relObj.attr("id")).html(html);
$(">li",options).comboxOption($(">a",relObj),relObj);}}}
property.attr("value",$this.attr("value"));});});
box.removeData("title");},
combox:function(){
return this.each(function(){
var $this=$(this);
var name=$this.attr("name");
var value=$this.attr("value");
var label=$("option[value="+value+"]",$this).text();
var ref=$this.attr("ref");
var param=$this.attr("param");
var cid=new Date().getTime();
var select="<div class=\"combox\"><div id=\""+ cid +"\" class=\"select\""+(ref?" rel=\"" + ref:"") + "\" name=\"" + name + "\""+(param?" param=\"" + param:"") + "\">";
select+="<a href=\"#\" name=\"" + name +"\" value=\"" + value + "\" change=\"" + ($this.attr("change")?$this.attr("change"):"")+ "\">"+label+"</a></div></div>";
var options="<ul class=\"comboxop\" id=\"op"+ cid +"\">";
$("option",$this).each(function(){
var option=$(this);
options+="<li><a class=\""+ (value==option[0].value?"selected":"") +"\" href=\"#\" value=\"" + option[0].value + "\">"+option[0].text+"</a></li>";});
options+="</ul>";
$("body").append(options);
$this.after(select);
$("div.select",$this.next()).comboxSelect();
$this.remove();});}});})(jQuery);(function($){
$.extend({
History:{
_hash:new Array(),
_cont:undefined,
_currentHash:"",
_callback:undefined,
init:function(cont,callback){
$.History._cont=cont;
$.History._callback=callback;
var current_hash=location.hash.replace(/\?.*$/,'');
$.History._currentHash=current_hash;
if($.browser.msie){
if($.History._currentHash==''){
$.History._currentHash='#';}
$("body").prepend('<iframe id="jQuery_history" style="display: none;"></iframe>');
var ihistory=$("#jQuery_history")[0];
var iframe=ihistory.contentDocument||ihistory.contentWindow.document;
iframe.open();
iframe.close();
iframe.location.hash=current_hash;}
if($.isFunction(this._callback))
$.History._callback(current_hash.skipChar("#"));
setInterval($.History._historyCheck,100);},
_historyCheck:function(){
var current_hash="";
if($.browser.msie){
var ihistory=$("#jQuery_history")[0];
var iframe=ihistory.contentWindow;
current_hash=iframe.location.hash.skipChar("#").replace(/\?.*$/,'');}else{
current_hash=location.hash.skipChar('#').replace(/\?.*$/,'');}
if(current_hash!=$.History._currentHash){
$.History._currentHash=current_hash;
$.History.loadHistory(current_hash);}},
addHistory:function(hash,fun,args){
$.History._currentHash=hash;
var history=[hash,fun,args];
$.History._hash.push(history);
if($.browser.msie){
var ihistory=$("#jQuery_history")[0];
var iframe=ihistory.contentDocument||ihistory.contentWindow.document;
iframe.open();
iframe.close();
iframe.location.hash=hash.replace(/\?.*$/,'');
location.hash=hash.replace(/\?.*$/,'');}else{
location.hash=hash.replace(/\?.*$/,'');}},
loadHistory:function(hash){
if($.browser.msie){
location.hash=hash;}
for(var i=0;i<$.History._hash.length;i+=1){
if($.History._hash[i][0]==hash){
$.History._hash[i][1]($.History._hash[i][2]);
return;}}}}});})(jQuery);

