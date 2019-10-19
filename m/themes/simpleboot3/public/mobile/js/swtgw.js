 
 // 中间漂浮内容区
document.writeln("<div id=\'dingwei\' style=\'width:232px; height:207px; position:fixed; left:50%; top:50%; margin:-103px 0 0 -116px; z-index:10008;\'><img src=\'http://m.ucc.cn/images/zixun1.gif\' width=\'232\' height=\'207\' border=\'0\'/><a href=\"javascript:void(0)\" onclick=\"online()\" style=\"position:absolute; left:6px; top:6px; width:220px; height:200px; display:block;\"></a><a href=\"tel://4008315772\"  style=\"position:absolute; left:6px; top:162px; width:110px; height:44px; display:block; \"></a><a href=\"javascript:void(0)\" onclick=\"yicang()\" style=\"position:absolute; right:0; top:0; width:28px; height:28px; display:block;-moz-border-radius:50%; -webkit-border-radius:50%; border-radius:50%;\"></a></div>");

document.writeln("<style>");
document.writeln("#LRdiv1,#LRfloater1,#LRdiv0,#LRfloater0{display:none;}");
document.writeln("*html{background-attachment:fixed;}");
document.writeln("@media only screen and (min-width: 480px){");
document.writeln("	#boxa{width:318px; height:190px; background-size:318px 190px; margin-left: -140px;}");
document.writeln("  .swt_left a{width:45px; height:45px;}");
document.writeln("}");
document.writeln("@media only screen and (min-width: 360px) and (max-width: 479px){");
document.writeln("	#boxa{width:285px; height:170px; background-size:285px 170px; margin-left: -145px;}");
document.writeln("  .swt_left a{width:35px; height:35px;}");
document.writeln("}");
document.writeln("@media only screen and (max-width: 359px){");
document.writeln("	#boxa{width:210px; height:125px; background-size:210px 125px; margin-left: -100px;}");
document.writeln("  .swt_left a{width:25px; height:25px;}");
document.writeln("}");
document.writeln("#boxa{background-image:url(http://www.5988.com/public/images/swt_x.gif); position:fixed; top:50%; left:50%; margin-top: -90px; display:none;  z-index:9999; font-family:\'微软雅黑\';}");
document.writeln(".swt_left{float:right; }");
document.writeln(".swt_left a{float:right;}");
document.writeln(".swt_right{float:left; width:100%; height:75%;}");
document.writeln(".swt_right a{ float:left; width:50%; height:100%;}");
document.writeln(".msbox{ display:none;}");
document.writeln("#footbox{width:100%; padding:0.8em 0; background-color:#3e93d4; position:fixed; bottom:0%; z-index:9999;}");
document.writeln(".footbox{ width:100%; margin:0 auto; padding:0;}");
document.writeln("@media only screen and (min-width: 640px){.footbox{width:640px;}}");
document.writeln(".footbox a{float:left; width:32.8%; color:#fff; text-decoration:none; border-left:#2d80c0 solid 1px; border-right:#6bb5ed solid 1px;}");
document.writeln(".footbox a:nth-child(1){ border-left:0;}");
document.writeln(".footbox a:nth-child(3){ border-right:0;}");
document.writeln(".footbox a img{ float:left; width:31.3%; margin-left:7%; margin-right:3%;}");
document.writeln(".footbox a p{float:left; width:50%; padding:4% 0; margin:0;}");
document.writeln(".footbox a p span{ color:#e9a951;}");
document.writeln("</style>");
document.writeln("");
document.writeln("<div id=\'boxa\'>");
document.writeln("    <div class=\'swt_left\' id=\'swt_left\'>");
document.writeln("        <a href=\'javascript:void(0)\' onclick=\'OnlineOut();return false;\' ></a>");
document.writeln("     </div>");
document.writeln("     <div class=\'swt_right\' id=\'swt_right\'>");
document.writeln("        <a  href=\'javascript:void(0)\' onclick=\'online()\'></a>");
 document.writeln("       <a  href=\'javascript:void(0)\' onclick=\'online()\'></a>");
document.writeln("     </div>");
document.writeln("</div>");


var host=window.location.host;
var setTime=10000;
var setTimeb=1000;
if(host=="m.ucc.cn"){
  setTime=8000;
  setTimeb=8000;
}
var boxa=document.getElementById('boxa');
setTimeout(function(){boxa.style.display='block';}, setTimeb);
function OnlineOut(){
   boxa.style.display='none'; 
   setTimeout(display_swt0,setTime);
   return false;
}
function display_swt0(){
   boxa.style.display='block'; 
}




var LiveAutoInvite0='';
var LiveAutoInvite1='';
var LiveAutoInvite2=1;
var LrinviteTimeout=1;
var LR_next_invite_seconds = 3;




//document.writeln("<script language=\"javascript\" src=\"https://chat16.live800.com/live800/chatClient/monitor.js?jid=4858223628&companyID=666557&configID=149504&codeType=custom&ss=1\"></script>");
function online(){
	//弹窗代码
	var e = 'anniu';
	if(arguments.length == 1){
		e = arguments[0];
	}
	var url = 'https://chat16.live800.com/live800/chatClient/chatbox.jsp?companyID=666557&configID=149510&jid=4858223628&s=1';
	url = url + '&e=' + e + '&p=' + encodeURIComponent(location.href);
	window.open(url, 'news' + Math.round( Math.random() * 1000000 ));


	return false;
}

if (top.location != self.location)top.location=self.location;





