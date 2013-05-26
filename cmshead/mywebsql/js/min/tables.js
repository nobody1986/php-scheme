/*
    http://mywebsql.net/license
*/
var curEditField=null,curEditType=null,fieldInfo=null,editToolbarTimer=null,editOptions={sortable:!0,highlight:!0,selectable:!0,editEvent:"dblclick",editFunc:editTableCell},selectedRow=-1,res_modified=!1,editHorizontal=!1;
function setupTable(a,b){res_modified=!1;b.editEvent||(b.editEvent="dblclick");b.editFunc||(b.editFunc=editTableCell);b.sortable&&(sorttable.DATE_RE=/^(\d\d?)[\/\.-](\d\d?)[\/\.-]((\d\d)?\d\d)$/,table=document.getElementById(a),sorttable.makeSortable(table));b.highlight&&($("#"+a+" tbody tr").live("mouseenter",function(){$(this).addClass("ui-state-hover")}),$("#"+a+" tbody tr").live("mouseleave",function(){$(this).removeClass("ui-state-hover")}));b.selectable&&$("#"+a+" tbody tr").live("click",function(){null!=
selectedRow&&$(selectedRow).removeClass("ui-state-active");$(this).addClass("ui-state-active");selectedRow=this});b.editable&&(editOptions=b,$("#"+a+" td.edit").bind(b.editEvent,b.editFunc),jQuery.support.touch&&$("#"+a+" td.edit").bind("taphold",b.editFunc),$("#inplace-text textarea").unbind("keydown").bind("keydown",checkEditField))}
function editTableCell(){editToolbarTimer&&(window.clearTimeout(editToolbarTimer),editToolbarTimer=null);td=$(this);null!=curEditField&&closeEditor(!0);isBlob=td.find("span.i").length;txt=(isText=td.find("span.d").length)?td.find("span.d").text():isBlob?td.find("span.i").text():td.text();tstyle=td.hasClass("tr")?"right":"left";td.data("defText",txt);curEditField=this;index=td.index()-2;fi=getFieldInfo(index);w=td.width()-(isBlob?22:0);h=td.height();td.attr("width",w);input=createCellEditor(td,fi,
txt,w,h,tstyle);setTimeout(function(){input.focus();td.ensureVisible($("#results-div"),editHorizontal);document.getElementById("editToolbar")&&($("#editToolbar span.fname").text(fi.name),type=fi.autoinc?"Auto Increment":fi.type,$("#editToolbar span.ftype").text(type),$("#editToolbar").show().position({of:td,my:"left bottom",at:"left top",offset:0}))},50)}
function closeEditor(a,b){if(curEditField){obj=$(curEditField);txt="";var c={};if(a){if(1<arguments.length&&null==b?(c.value="NULL",c.setNull=!0):(txt=c.value="simple"==curEditType?obj.find("input").val():$("#inplace-text textarea").val(),c.setNull=!1),c.value!=obj.data("defText")||c.setNull&&!obj.hasClass("tnl")||!c.setNull&&obj.hasClass("tnl"))obj.parent().hasClass("n")||obj.parent().addClass("x"),obj.data("edit",c).addClass("x"),res_modified=!0,"function"==typeof showNavBtn&&showNavBtn("update",
"gensql"),c.setNull?obj.removeClass("tl").addClass("tnl"):obj.removeClass("tnl").addClass("tl"),txt=c.value}else txt=obj.data("defText");"text"==curEditType?(c.setNull?obj.find("span.i").text("NULL").removeClass("tl").addClass("tnl"):obj.find("span.i").text(0==txt.length?"":txt.length<=MAX_TEXT_LENGTH_DISPLAY?txt:"Text Data ["+formatBytes(txt.length)+"]").removeClass("tnl"),obj.find("span.d").text(txt)):0==obj.find("span.i").length?obj.text(txt):obj.find("span.i").text(txt);obj.removeAttr("width");
curEditField=null;"text"==curEditType&&$("#inplace-text").hide();document.getElementById("editToolbar")&&(editToolbarTimer=window.setTimeout(function(){document.getElementById("editToolbar").style.display="none";editToolbarTimer=null},100))}}
function checkEditField(a){editHorizontal=!1;keys="text"==curEditType?[9]:[13,9,38,40];if(-1!=keys.indexOf(a.keyCode)){a.preventDefault();elem=!1;if(9==a.keyCode)elem=a.shiftKey?$(curEditField).prev(".edit"):$(curEditField).next(".edit"),elem.length||(tr=a.shiftKey?$(curEditField).parent().prev():$(curEditField).parent().next(),tr.length&&(elem=a.shiftKey?tr.find("td.edit:last"):tr.find("td.edit:first"))),editHorizontal=!0;else if(38==a.keyCode||40==a.keyCode)tr=38==a.keyCode?$(curEditField).parent().prev():
$(curEditField).parent().next(),tr.length&&(elem=tr.find("td").eq($(curEditField).index()));$("#inplace-text textarea").unbind("blur");closeEditor(!0);elem&&elem.length&&elem.trigger(editOptions.editEvent)}else 27==a.keyCode&&closeEditor(!1)}
function createCellEditor(a,b,c,d,f,e){curEditType="simple";keyEvent="keydown";input=null;code='<form name="cell_editor_form" class="cell_editor_form" action="javascript:void(0);">';if(1==b.blob)"binary"==b.type?(code+='<input type="text" readonly="readonly" name="cell_editor" class="cell_editor" style="text-align:'+e+";width: "+d+'px;" />',code+="</form>",a.find("span.i").html(code),input=a.find("input"),input.val(c).bind(keyEvent,checkEditField).blur(function(){closeEditor(!0)})):(span=$(a).find("span.d"),
c=span.text(),d=a.width()-20,200>d&&(d=200),textarea=$("#inplace-text textarea"),textarea.width(d).val(c),$("#inplace-text").show().position({of:a,my:"left top",at:"left top",offset:0}),$("#inplace-text textarea").blur(function(){closeEditor(!0)}),curEditType="text",input=textarea);else switch(b.type){default:code+='<input type="text" name="cell_editor" class="cell_editor" style="text-align:'+e+";width: "+d+'px;" />',code+="</form>",a.html(code),input=a.find("input"),input.val(c).select().bind(keyEvent,
checkEditField).blur(function(){closeEditor(!0)})}return input}$.fn.ensureVisible=function(a,b){b?(pl=a.prop("scrollLeft"),pw=a.width(),p=this.position(),w=this.width(),pw<p.left+w?a.prop("scrollLeft",p.left+w):0>p.left&&a.prop("scrollLeft",p.left)):(pt=a.prop("scrollTop"),ph=a.height(),p=this.position(),h=this.height(),ph<p.top+h?a.prop("scrollTop",p.top+h):0>p.top&&a.prop("scrollTop",p.top))};
$.fn.setSearchFilter=function(a){""==a?$("tr",this).removeClass("ui-helper-hidden"):(string=a.toUpperCase(),$("tbody tr",this).each(function(){var a=!1;$("td",this).each(function(){if($(this).text().toUpperCase().match(string))return a=!0});a?$(this).removeClass("ui-helper-hidden"):$(this).addClass("ui-helper-hidden")}))};
