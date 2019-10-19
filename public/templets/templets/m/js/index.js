$(function(){
 
if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89185.html'){
	this.location ="http://m.91chuangye.com/jinrong/89185.html";
}else if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89186.html'){
    this.location ="http://m.91chuangye.com/jinrong/89186.html";
}else if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89187.html'){
    this.location ="http://m.91chuangye.com/jinrong/89187.html";
}else if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89188.html'){
    this.location ="http://m.91chuangye.com/jinrong/89188.html";
}else if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89189.html'){
    this.location ="http://m.91chuangye.com/jinrong/89189.html";
}else if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89190.html'){
    this.location ="http://m.91chuangye.com/jinrong/89190.html";
}else if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89191.html'){
    this.location ="http://m.91chuangye.com/jinrong/89191.html";
}else if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89192.html'){
    this.location ="http://m.91chuangye.com/jinrong/89192.html";
}else if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89193.html'){
    this.location ="http://m.91chuangye.com/jinrong/89193.html";
}else if(window.location.href == 'http://m.91chuangye.com/jinrong/licai/89194.html'){
    this.location ="http://m.91chuangye.com/jinrong/89194.html";
}else if(window.location.href == 'http://www.91chuangye.com/m/'){
		this.location ="http://m.91chuangye.com/";
}
//搜索框
$('input:text').each(function(){  
	var txt = $(this).val();
	$(this).css("color","#ccc");
	$(this).focus(function(){  
		if(txt === $(this).val()){$(this).val(""); $(this).css("color","#333");} 
	}).blur(function(){  
		if($(this).val() === "") {$(this).val(txt); $(this).css("color","#ccc");} 
	});  
});

//焦点图
TouchSlide({
            slideCell:"#focus",
            titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
            mainCell:".bd ul",
            effect:"left",
            autoPlay:true,//自动播放
            autoPage:true,//自动分页
            switchLoad:"_src"//切换加载，真实图片路径为"_src"
        });


//图片导航
TouchSlide({
            slideCell:"#index_nav",
            titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
            mainCell:".bd ul",
            effect:"left",
            autoPlay:false,//自动播放
            autoPage:true,//自动分页
        });


//搜索关键词样式
            var rep =/6/ig;
                $('.search_news li a p').each(function () {
                    $(this).html($(this).text().replace(rep, function (a) {
                        return a.fontcolor('red');
                    }));
                });

//分类展示
$(".item_sub_tit a").click(function () {
	$("#js_sub_cate").slideToggle();
});

$("#js_sub_cate_list li:gt(6)").css("display","none");
if($("#js_sub_cate_list li").length>7){
	$('<li class="js_sub_cate_btn"><a href="#">更多▼</a></li>').appendTo("#js_sub_cate_list ul");
}
$(".js_sub_cate_btn").click(function () {
	$("#js_sub_cate_list li:gt(6)").slideToggle(0);
});

//项目大图
	var nrW = $(".item_info .pic_wrap").width()-20;
	$(".item_info .pic_wrap img").each(function(){
	if($(this).width() > nrW){
		$(this).css("width",nrW);
		$(this).css('height','auto');}
	});
	
	var nrW1 = $(".item_intro .bd").width()-20;
	$(".item_intro .bd img").each(function(){
	if($(this).width() > nrW1){
		$(this).css("width",nrW1);
		$(this).css('height','auto');}
	});
	
	
$("#msg_btn").click(function(){
	var sTop=$('#msg').offset().top-30;
	$('html,body').animate({scrollTop:sTop+"px"},500);
});

//当前导航样式
	var myNav = $(".item_sub_cate .yui3-u a");
	for(var i=0;i<myNav.length;i++){
    var links = myNav[i].getAttribute("href");
    var myURL = document.location.href;
    if(myURL.indexOf(links) != -1){
        myNav[i].className="cur";
    	}
	}

//弹窗显示
var host=window.location.host;
var setTime=10000;
var setTimeb=1000;
if(host=="file:///F:/Jerry/work/u88/m-u88/index.html"){
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
$("#close").click(function(){
   boxa.style.display='none'; 
   setTimeout(display_swt0,setTime);
   return false;
});

function display_swt0(){
   boxa.style.display='block'; 
}

//留言板验证 
	$("#msgform").submit(function(){ 
	    var username=$("#user").val(); 
	    var tel=$("#tel").val(); 
	    var liuyan=$("#liuyan").val();
	    var reg = /^(13[0-9]|15[012356789]|18[0-9]|17[678]|14[57])[0-9]{8}$/;
	    var re = /^[0-9]+.?[0-9]*$/;
	    	if(username=="") 
	        { 
		        alert("请输入您的姓名！"); 
		        $("#user").focus(); 
		        return false; 
	        }          
	        if (tel=="" || (tel != "" && !reg.test(tel))) { 
	            alert("请输入正确有效的手机号码！"); 
	            $("#tel").focus(); 
	            return false; 
	        }                       
	        if(liuyan==""){ 
	            alert("请输入您的需求或问题！"); 
	            $("#liuyan").focus(); 
	            return false; 
	        }	                
	}); 	

});



