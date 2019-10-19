//展示400电话 
function showTel(id){
  $(".bgIframe").css({
	  "filter":"alpha(opacity=0)",
	  "opacity":"0"
	  });
  $(".bgDiv").css({
	  "filter":"alpha(opacity=50)",
	  "opacity":"0.5"
	  });
	  
  $(id).fadeIn(100,function(){
		$(".bgIframe,.bgDiv").height($(document.body).height()).fadeIn(200);
	  });
   return false;	  
}

//通用弹出层触发动作 begin
function showPop(id,className){
  $(".bgIframe").css({
	  "filter":"alpha(opacity=0)",
	  "opacity":"0"
	  });
  $(".bgDiv").css({
	  "filter":"alpha(opacity=50)",
	  "opacity":"0.5"
	  });  
  $(id).addClass(className).fadeIn(100,function(){
	  	var	popHeight = $(''+id+'.popWrap,'+id+' .popWrap_sub,'+id+' .popIframe');
		var h=$(''+id+' .popBox').height();//获取高度
		popHeight.height(h+12);
		$(".bgIframe,.bgDiv").height($(document.body).height()).fadeIn(200);
	  });
   return false;
}
	  
$(function(){
	//关闭弹层窗口
	$(".queBtn input,.closeBtn").click(function(){
		  $(".popWrap,.tel400_box,.bgIframe,.bgDiv").fadeOut();
	  });	
	
});

//关闭弹层窗口2
function hidePop(id,className){
	$(''+id+',.bgIframe,.bgDiv').fadeOut(function(){
		   $(this).removeClass(className);
		})
}
function resizeHeight(){
	//三列架构等高值
	var divHeight=$('.sideLeft,.mainBody,.sideRight,.pageBody');
	divHeight.height('');
	divHeight.height($('.container').height()-70);
} 

$(function(){
	//订阅设置
	//全选 
	$("a[name='checkAll']").click(function (){ 
		$(this).parent().parent().find(':checkbox').attr("checked", true);
	});  
	//反选 
	$("a[name='checkReverse']").click(function (){
		$(this).parent().parent().find(':checkbox').each(function (){  
			$(this).attr("checked", !$(this).attr("checked"));  
		});  
	}); 
	
	//三列架构等高值
	var divHeight=$('.sideLeft,.mainBody,.sideRight,.pageBody');
	divHeight.height($('.container').height()-70);
	//按钮hover状态
	var hoverObj=$(".topNav li,.itemList img,.ppLogo,.rssPic a,.ppName img,.globalBtn a,li .userFace,.viewMore,.myRssList img,.calendarSelect,input[type='submit'],input[type='button']");
		hoverObj.hover(
		function(){
			$(this).stop().animate({"opacity":0.7},500);
		}, 
		function(){
			$(this).stop().animate({"opacity":1},500);
	});
		$('.replyCon1').hover(
		function(){
			$(this).stop().animate({"opacity":1},500);
		}, 
		function(){
			$(this).stop().animate({"opacity":0.8},500);
	});
	//下拉展示快捷留言列表
	$(".quickMsg_box").hover(
		function(){
			$(this).find(".quickMsg_list").stop().slideDown(300);
		}, 
		function(){
			$(this).find(".quickMsg_list").stop().slideUp(300,function(){$(".quickMsg_list").width("");});
	});
	
	//介绍页加盟资讯
	$(".jmzx li").hover(
		function(){
			$(this).find("a:first").stop().animate({"margin-top":-130},350);
		},
		function(){
			$(this).find("a:first").stop().animate({"margin-top":0},350);
	})
	
	//表格偶数行样式
	$('.tableData tbody tr:odd').addClass("bgColor");
	$('.tableData tbody tr').hover(
		function(){
			$(this).css('background','#ffffe3');
		},
		function(){
			$(this).css('background','');
		});
	//左侧菜单
	$('.sideMenu li').hover(
		function(){
			$(this).addClass('hover');
		},
		function(){
			$(this).removeClass('hover');
		});
	//关闭系统消息
	$('.tipsTit a.close').click(function(){
		$(".systemMsg").fadeOut();
		
		});
	//显示服务条款		
	$(".treatyLink a").click(function(){
		 $(".treatyBox").slideToggle();
		});
	//创业风向标
	var scrtime;
	 $("#windVane_list").hover(function(){
		clearInterval(scrtime);       
	},
	function(){
		scrtime = setInterval(function(){
			var news = $("#windVane_list ul");
			var liHeight = news.find("li:last").height();
			news.animate({marginTop : liHeight+ 10 +"px"},800,function(){
			news.find("li:last").prependTo(news)
			news.find("li:first").hide();
			news.css({marginTop:0});
			news.find("li:first").fadeIn(500);
		});       
		},3000);       
	}).trigger("mouseleave"); 
	});
//列表鼠标经过背景色
	$(".proList").mouseover(function() {
		$(this).addClass("curr");
	});
	$(".proList").mouseout(function() {
		$(this).removeClass("curr");
	});
//选项卡
function TabMove(name,cursel,n)
{
	n = n || 50;
	for (i=1;i<=n;i++)
	{
		var menu=document.getElementById(name+"Tab"+i);	
		var con=document.getElementById(name+"_"+i);
		menu.className=i==cursel?"current":"";
		con.style.display=i==cursel?"block":"none";
		resizeHeight();
	}
	 
}
