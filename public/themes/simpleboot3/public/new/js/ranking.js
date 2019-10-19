$('.zong_paiB ul li:last-child').css('border-bottom','none');
 $('.ten_rankingList .multipleLine22 .tenBig dd:last-child').css('border-bottom','none');
 $('.ranking_ziX ul:last-child').css('margin-right','0');
$('#nav_select .zong_nav_bg .zong_nav .li1').mouseover(function(){
		$('.down_nav').show();
		$(this).addClass('sj_selected').siblings('.sj_selected').removeClass('sj_selected');
		$('#nav_select .down_nav .down_zi p').eq($(this).index()).show().siblings().hide();
	})
	$('#nav_select .down_zi p').mouseover(function(){
		$('.down_nav').show();	
		$('#nav_select .zong_nav_bg .zong_nav .li1').eq($(this).index()-1).addClass('sj_selected');
	})
	$('#nav_select').mouseout(function(){
		$('.down_nav').hide();
		$('#nav_select .zong_nav_bg .zong_nav .li1').removeClass('sj_selected');
	})
	$('#nav_select .li_more').hover(function(){
		$('.moreUl').show();
		$('.i12').hide();
		$('.more_j').show();
		$('#nav_select .down_nav').hide();
	},function(){
		$('.moreUl').hide();
		$('.i12').show();
		$('.more_j').hide();
	})
	//网站导航
$('.ttbar_navs').hover(function(){
		$(this).css('background','#fff');
		$(this).css('border','1px solid #eeeeee');
		$('.i6_2').show();
		$('.i6').hide();
		$('.dorpdown_layer').show();
	},function(){
		$(this).css('background','#fafafa');
		//$(this).css({'border':'1px solid #fafafa','border-top:none','border-bottom:none'});
		$(this).css('border','1px solid #fafafa');
		$(this).css('border-top','none');
		$(this).css('border-bottom','none');
		$('.i6_2').hide();
		$('.i6').show();
		$('.dorpdown_layer').hide();
	})
$('.li_phone').hover(function(){
		$('.pic_phone').show();
	},function(){
		$('.pic_phone').hide();
	});
	$('.li_mobile').hover(function(){
		$('.pic_mobile').show();
		$('.li_mobile a').hover(function(){
			$('.li_mobile a').css('color','#df0303');
		},function(){
			$('.li_mobile a').css('color','#999');
		});
	},function(){
		$('.pic_mobile').hide();
	})
	//微信
	$('.li_weiXin').hover(function(){
		$('.pic_two_wei1').show();
		$('.li_weiXin a').addClass('doingW');
	},function(){
		$('.pic_two_wei1').hide();
		$('.li_weiXin a').removeClass('doingW');
	})
	//微博
	$('.li_weibo').hover(function(){
		$('.pic_two_wei2').show();
		$('.li_weibo a').addClass('doingW');
	},function(){
		$('.pic_two_wei2').hide();
		$('.li_weibo a').removeClass('doingW');
	})
	//下拉行业
	//$('.logo_select').click(function(){
		//$('.xiala_box').show();
	//})
	//$('body').click(function(){
		//$('.xiala_box').hide();
	//})
     $(".logo_select").click(function (e) {
       $(".xiala_box").toggle();
       e = window.event || e;
       if (e.stopPropagation) {
         e.stopPropagation();
       } else {
         e.cancelBubble = true;
       }

     });
     $(".xiala_box").click(function (e) {
       e = window.event || e;
       if (e.stopPropagation) {
         e.stopPropagation();
       } else {
         e.cancelBubble = true;
       }
     });
     $(document).click(function () {
       $(".xiala_box").hide();
     });
	//排行榜
	$('.ul_top li:last-child').css('border-bottom','1px solid #dedede');
	$('.banner_bottom a:first').css('margin-left','0');
	$('.many_jiaM .tit_con li:nth-child(4n)').css('margin-right','0');
	$('.tuijian_dl dl:nth-child(4n)').css('margin-right','0');
	$('.zong_nav_zi p:nth-child(2) a').css('margin-left','13px');
	$('.zong_nav_zi p:nth-child(2) a').css('font-size','12px');
	//查看更多
	$('.banner_bottom a').hover(function(){
		$(this).children('dl').children('dd').children('.p_look').css('background','#ffaa99');
	},function(){
		$(this).children('dl').children('dd').children('.p_look').css('background','#dbdbdb');
	});
//优惠
	var $swap = $('.marqueeUP');
	var movetotop;
	$swap.hover(function() {
	clearInterval(movetotop);
	},function() {
	movetotop=setInterval(function() {
	var li_height = $swap.find('p').height();
	$swap.find('p:first').animate({marginTop:-li_height + 'px'},600,function() {
	$swap.find('p:first').css('marginTop',0).appendTo($swap);
	});
	},3000);
	}).trigger('mouseleave');
	
//播报
	var bOdemo = document.getElementById("bOdemo");
	var bOdemo1 = document.getElementById("bOdemo1");
	var bOdemo2 = document.getElementById("bOdemo2");
	bOdemo2.innerHTML=document.getElementById("bOdemo1").innerHTML;
	function Marquee(){
	if(bOdemo.scrollLeft-bOdemo2.offsetWidth>=0){
	 bOdemo.scrollLeft-=bOdemo1.offsetWidth;
	}
	else{
	 bOdemo.scrollLeft++;
	}
	}
	var myvar=setInterval(Marquee,50);
	bOdemo.onmouseout=function (){myvar=setInterval(Marquee,50);}
	bOdemo.onmouseover=function(){clearInterval(myvar);}
//餐饮 酒水 滑过出现	
	$('.hover_crad > .canY_ul > li').mouseover(function(){
		$(this).addClass('a_select').siblings('.a_select').removeClass('a_select');
		$('.hover_crad .canY_con .canY_con1_all').eq($(this).index()).show().siblings().hide();		
	})
//资讯 选项卡
	$('.ziXun_first:last-child').css('margin-right','0');
	$('.many_jiaM .tit_con ul:last-child').css('margin-right','0');
	
	//品牌大全	
	$('.many_jiaMLbtCon .jsNav_bottomBoss .jsNav_bottom').eq(0).show()
	$('.many_jiaMLbtCon .jiaM_navs .ul_jsNav li').mouseover(function(){
		$(this).addClass('navs_select').siblings('.navs_select').removeClass('navs_select');
		$('.many_jiaMLbtCon .jsNav_bottomBoss .jsNav_bottom').eq($(this).index()).show().siblings().hide();
	})
	$('.many_jiaMLbtCon22 .jiaM_navs .ul_jsNav li').mouseover(function(){
		$(this).addClass('navs_select').siblings('.navs_select').removeClass('navs_select');
		$('.many_jiaMLbtCon22 .jsNav_bottomBoss .jsNav_bottom').eq($(this).index()).show().siblings().hide();
	})
	$('.many_jiaMLbtCon33 .jiaM_navs .ul_jsNav li').mouseover(function(){
		$(this).addClass('navs_select').siblings('.navs_select').removeClass('navs_select');
		$('.many_jiaMLbtCon33 .jsNav_bottomBoss .jsNav_bottom').eq($(this).index()).show().siblings().hide();
	})
	$('.daQuan_JM .hot_JM .hotHover_li').mouseover(function(){
		$(this).addClass('active').siblings('.active').removeClass('active');
		$($('.daQuan_JM .Manycon_JM .many_jiaM')[$(this).index('.daQuan_JM .hot_JM .hotHover_li')]).css('display','block').siblings().hide();
	})
	$('.daQuan_JM .hot_JM .hotHover_li:first').mouseover(function(){
		$(this).removeClass('active');
	})
	
	$('.daQuan_JM .hot_JM .hotHover_li').mouseover(function(){
		$(this).addClass('active').siblings('.active').removeClass('active');
		$($('.daQuan_JM .Manycon_JM .many_jiaM')[$(this).index('.daQuan_JM .hot_JM .hotHover_li')]).css('display','block').siblings().hide();
	})
	$('.daQuan_JM .hot_JM .hotHover_li:first').mouseover(function(){
		$(this).removeClass('active');
	})
	
	
	$('.many_jiaM .many_jiaMLbtCon33').css({'left':'-1200px'})
	$('.many_jiaM .jiaM_navs .next').eq(0).on('click',function(){
		$('.many_jiaM .many_jiaMLbtCon').animate({'left':'-1200px'},'slow',function(){
			$('.many_jiaM .many_jiaMLbtCon').css({'position':'absolute'})
		});
		$('.many_jiaM .many_jiaMLbtCon22').animate({'left':'0px'},'slow',function(){
			$('.many_jiaM .many_jiaMLbtCon22').css({'position':'relative'})});
		$('.many_jiaM .many_jiaMLbtCon33').css({'left':'1200px'})
	})
	$('.many_jiaM .jiaM_navs .next').eq(1).on('click',function(){
		$('.many_jiaM .many_jiaMLbtCon').css({'left':'1200px'})
		$('.many_jiaM .many_jiaMLbtCon22').animate({'left':'-1200px'},'slow',function(){
			$('.many_jiaM .many_jiaMLbtCon22').css({'position':'absolute'})});
		$('.many_jiaM .many_jiaMLbtCon33').animate({'left':'0px'},'slow',function(){
			$('.many_jiaM .many_jiaMLbtCon33').css({'position':'relative'})});
	})	
	$('.many_jiaM .jiaM_navs .next').eq(2).on('click',function(){
		$('.many_jiaM .many_jiaMLbtCon').animate({'left':'0px'},'slow',function(){
		$('.many_jiaM .many_jiaMLbtCon').css({'position':'relative'})});
		$('.many_jiaM .many_jiaMLbtCon22').css({'left':'1200px'})
		$('.many_jiaM .many_jiaMLbtCon33').animate({'left':'-1200px'},'slow',function(){
		$('.many_jiaM .many_jiaMLbtCon33').css({'position':'absolute'})});
	}) 
	$('.many_jiaM .jiaM_navs .prev').eq(0).on('click',function(){
		$('.many_jiaM .many_jiaMLbtCon').animate({'left':'1200px'},'slow',function(){
			$('.many_jiaM .many_jiaMLbtCon').css({'position':'absolute'})
		});
		$('.many_jiaM .many_jiaMLbtCon33').animate({'left':'0px'},'slow',function(){
			$('.many_jiaM .many_jiaMLbtCon33').css({'position':'relative'})});
		$('.many_jiaM .many_jiaMLbtCon22').css({'left':'-1200px'})
	})
	$('.many_jiaM .jiaM_navs .prev').eq(2).on('click',function(){
		$('.many_jiaM .many_jiaMLbtCon').css({'left':'-1200px'});
		$('.many_jiaM .many_jiaMLbtCon33').animate({'left':'1200px'},'slow',function(){
			$('.many_jiaM .many_jiaMLbtCon33').css({'position':'absolute'})});
		$('.many_jiaM .many_jiaMLbtCon22').animate({'left':'0px'},'slow',function(){
			$('.many_jiaM .many_jiaMLbtCon22').css({'position':'relative'})});
	})	
	$('.many_jiaM .jiaM_navs .prev').eq(1).on('click',function(){
		$('.many_jiaM .many_jiaMLbtCon33').css({'left':'-1200px'});
		$('.many_jiaM .many_jiaMLbtCon').animate({'left':'0px'},'slow',function(){
		$('.many_jiaM .many_jiaMLbtCon').css({'position':'relative'})});
		$('.many_jiaM .many_jiaMLbtCon22').animate({'left':'1200px'},'slow',function(){
		$('.many_jiaM .many_jiaMLbtCon22').css({'position':'absolute'})});
	})
	
	
	
	//创业者最新意向品牌
	var speed=50 
	Brand_new();
	function Brand_new(){
	var rankDemo=document.getElementById("rankDemo"); 
	var gundong=document.getElementById("gundong"); 
	var rankDemo2=document.getElementById("rankDemo2"); 
	var rankDemo1=document.getElementById("rankDemo1"); 
	rankDemo2.innerHTML=rankDemo1.innerHTML 
	function Marquee(){ 
	if(rankDemo.scrollTop>=rankDemo1.offsetHeight){
	rankDemo.scrollTop=0; 
	}
	else{ 
	rankDemo.scrollTop=rankDemo.scrollTop+1;
	} 
	} 
	var MyMar=setInterval(Marquee,speed) 
	rankDemo.onmouseover=function(){clearInterval(MyMar)} 
	rankDemo.onmouseout=function(){MyMar=setInterval(Marquee,speed)} 
	}
	//看看谁在找项目
	var speed=50 
	rsee_friendLian();
	function rsee_friendLian(){
	var zrankDemo=document.getElementById("zrankDemo"); 
	var zgundong=document.getElementById("zgundong"); 
	var zrankDemo2=document.getElementById("zrankDemo2"); 
	var zrankDemo1=document.getElementById("zrankDemo1"); 
	zrankDemo2.innerHTML=zrankDemo1.innerHTML 
	function Marquee(){ 
	if(zrankDemo.scrollTop>=zrankDemo1.offsetHeight){
	zrankDemo.scrollTop=0; 
	}
	else{ 
	zrankDemo.scrollTop=zrankDemo.scrollTop+1;
	} 
	} 
	var MyMar=setInterval(Marquee,speed) 
	zrankDemo.onmouseover=function(){clearInterval(MyMar)} 
	zrankDemo.onmouseout=function(){MyMar=setInterval(Marquee,speed)} 
	}
