/*
    http://mywebsql.net/license
*/
function optionsSave(){for(i=0;i<arguments.length;i++)if(arg=arguments[i],obj=document.getElementById(arg))val="checkbox"==obj.type?obj.checked?"on":"":obj.value,$.cookies.set("prf_"+arg,val,{path:EXTERNAL_PATH,hoursToLive:COOKIE_LIFETIME});jAlert(__("New settings saved and applied."))}function optionsLoad(){}function optionsConfirm(a,b,c){ask=$.cookies.get("prf_cnf_"+b);return"no"==ask?c(!0,"",!1):jConfirm(a,__("Confirm Action"),c,b)}
function optionsConfirmSave(a){$.cookies.set("prf_cnf_"+a,"no",{path:EXTERNAL_PATH,hoursToLive:COOKIE_LIFETIME})};
