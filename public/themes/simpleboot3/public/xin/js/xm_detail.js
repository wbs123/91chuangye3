'use strict';


//图片懒加载
$(function() {
  $("img.lazy").lazyload({
		effect: "fadeIn",
		threshold: 200,
		skip_invisible: false
	});
});


/*项目轮播-plug*/
$.fn.banqh = function(can){
	can = $.extend({
		box:null,//总框架
		pic:null,//大图框架
		pnum:null,//小图框架
		prev_btn:null,//小图左箭头
		next_btn:null,//小图右箭头
		autoplay:false,//是否自动播放
		interTime:5000,//图片自动切换间隔
		delayTime:800,//切换一张图片时间
		order:0,//当前显示的图片（从0开始）
		picdire:true,//大图滚动方向（true水平方向滚动）
		mindire:true,//小图滚动方向（true水平方向滚动）
		min_picnum:null,//小图显示数量
	}, can || {});
	var picnum = $(can.pic).find('ul li').length;
	var picw = $(can.pic).find('ul li').outerWidth(true);
	var pich = $(can.pic).find('ul li').outerHeight(true);
	var poppicw = $(can.pop_pic).find('ul li').outerWidth(true);
	var picminnum = $(can.pnum).find('ul li').length;
	var picpopnum = $(can.pop_pic).find('ul li').length;
	var picminw = $(can.pnum).find('ul li').outerWidth(true);
	var picminh = $(can.pnum).find('ul li').outerHeight(true);
	var pictime;
	var tpqhnum=0;
	var xtqhnum=0;
	var popnum=0;
	$(can.pic).find('ul').width(picnum*picw);
	$(can.pnum).find('ul').width(picminnum*picminw);
	$(can.pop_pic).find('ul').width(picpopnum*poppicw);
	
	//点击小图切换大图
	$(can.pnum).find('li').click(function () {
		tpqhnum = xtqhnum = $(can.pnum).find('li').index(this);
		show(tpqhnum);
		minshow(xtqhnum);
	}).eq(can.order).trigger("click");

	//自动轮播
	if(can.autoplay==true){
		//自动播放
		pictime = setInterval(function(){
			show(tpqhnum);
			minshow(tpqhnum)
			tpqhnum++;
			xtqhnum++;
			if(tpqhnum==picnum){tpqhnum=0};	
			if(xtqhnum==picminnum){xtqhnum=0};
					
		},can.interTime);	
		
		//鼠标经过停止播放
		$(can.box).hover(function(){
			clearInterval(pictime);
		},function(){
			pictime = setInterval(function(){
				show(tpqhnum);
				minshow(tpqhnum)
				tpqhnum++;
				xtqhnum++;
				if(tpqhnum==picnum){tpqhnum=0};	
				if(xtqhnum==picminnum){xtqhnum=0};		
				},can.interTime);			
			});
	}
	
	//小图左右切换			
	$(can.prev_btn).click(function(){
		if(tpqhnum==0){tpqhnum=picnum};
		if(xtqhnum==0){xtqhnum=picnum};
		xtqhnum--;
		tpqhnum--;
		show(tpqhnum);
		minshow(xtqhnum);	
		})
	$(can.next_btn).click(function(){
		if(tpqhnum==picnum-1){tpqhnum=-1};
		if(xtqhnum==picminnum-1){xtqhnum=-1};
		xtqhnum++;
		minshow(xtqhnum)
		tpqhnum++;
		show(tpqhnum);
		})	
		
	//小图切换过程
	function minshow(xtqhnum){
		var mingdjl_num =xtqhnum-can.min_picnum+2
		var mingdjl_w=-mingdjl_num*picminw;
		var mingdjl_h=-mingdjl_num*picminh;
		
		if(can.mindire==true){
			$(can.pnum).find('ul li').css('float','left');
			if(picminnum>can.min_picnum){
				if(xtqhnum<3){mingdjl_w=0;}
				if(xtqhnum==picminnum-1){mingdjl_w=-(mingdjl_num-1)*picminw;}
				$(can.pnum).find('ul').stop().animate({'left':mingdjl_w},can.delayTime);
				}
				
		}else{
			$(can.pnum).find('ul li').css('float','none');
			if(picminnum>can.min_picnum){
				if(xtqhnum<3){mingdjl_h=0;}
				if(xtqhnum==picminnum-1){mingdjl_h=-(mingdjl_num-1)*picminh;}
				$(can.pnum).find('ul').stop().animate({'top':mingdjl_h},can.delayTime);
				}
			}
		
	}
	
	//大图切换过程
	function show(tpqhnum){
		var gdjl_w=-tpqhnum*picw;
		var gdjl_h=-tpqhnum*pich;
		if(can.picdire==true){
			$(can.pic).find('ul li').css('float','left');
			$(can.pic).find('ul').stop().animate({'left':gdjl_w},can.delayTime);
			}else{
		$(can.pic).find('ul').stop().animate({'top':gdjl_h},can.delayTime);
		}//滚动
		//$(can.pic).find('ul li').eq(tpqhnum).fadeIn(can.delayTime).siblings('li').fadeOut(can.delayTime);//淡入淡出
		$(can.pnum).find('li').eq(tpqhnum).addClass("on").siblings(this).removeClass("on");
	};
	
}
$('.xm-pic').banqh({
	box:".xm-pic",//总框架
	pic:".bigimgbox",//大图框架
	pnum:".midbox",//小图框架
	prev_btn:".prevbtn",//小图左箭头
	next_btn:".nextbtn",//小图右箭头
	autoplay:true,//是否自动播放
	interTime:5000,//图片自动切换间隔
	delayTime:400,//切换一张图片时间
	order:0,//当前显示的图片（从0开始）
	picdire:true,//大图滚动方向（true为水平方向滚动）
	mindire:true,//小图滚动方向（true为水平方向滚动）
	min_picnum:4,//小图显示数量
})

/*icheck-plug*/
$(function() {
	$('.xm-mesg input').iCheck({
    checkboxClass: 'icheckbox_flat-red',
    radioClass: 'iradio_flat-red'
  });
	
	$('.jmzx-cont input').iCheck({
		checkboxClass: 'icheckbox_flat-red',
		radioClass: 'iradio_flat-red'
	});
	
	$('.syzl-cont input').iCheck({
		checkboxClass: 'icheckbox_flat-red',
		radioClass: 'iradio_flat-red'
	});
})

/*select-plug*/
$(function(){
	$('select.select').select();
});

/*layer-plug*/
$(function(){
	
	/*加盟咨询*/
	$('#jmzx').on('click', function () {
		layer.open({
			type: 1,
			area: '900px',
			title: false,
			closeBtn: 1,
			shade: 0.5,
			shadeClose: true,
			content: $('#xm-mesg'),
			end: function () {
				$("input[inputEl='input']").val(''); //清空输入框内容
			}
		});
	});
	
	/*索要创业资料*/
	$('#syzl').on('click', function () {
		layer.open({
			type: 1,
			area: '360px',
			title: false,
			closeBtn: 1,
			shade: 0.5,
			shadeClose: true,
			content: $('#syzl-cont'),
			end: function () {
				$("input[inputEl='input']").val(''); //清空输入框内容
			}
		});
	});
	
	/*点击免费通话*/
	$('#mfth').on('click', function () {
		layer.open({
			type: 1,
			area: '360px',
			title: false,
			closeBtn: 1,
			shade: 0.5,
			shadeClose: true,
			content: $('#mfth-cont'),
			end: function () {
				$("input[inputEl='input']").val(''); //清空输入框内容
			}
		});
	});
  
})


/*右侧 栏目切换*/
$(function() {
	/*热门品牌-品牌排行*/
	$(".side-brandphb .tabs li").eq(0).addClass("on").siblings().removeClass('on');
	$(".side-brandphb .tabs-cont .cont").eq(0).show().siblings().hide();   
	$(".side-brandphb .tabs li").hover(function() {
		$(".side-brandphb .tabs li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".side-brandphb .tabs-cont .cont").hide().eq($(this).index()).show();
	});
	
	/*新闻资讯*/
	$(".side-news .tabs li").eq(0).addClass("on").siblings().removeClass('on');
	$(".side-news .cont ul").eq(0).show().siblings().hide();   
	$(".side-news .tabs li").hover(function() {
		$(".side-news .tabs li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".side-news .cont ul").hide().eq($(this).index()).show();
	});
	
	/*排行榜*/
	$(".side-brandphb .cont2 dl").eq(0).addClass("on").siblings().removeClass('on')
	$(".side-brandphb .cont2 dl").hover(function() {
		$(this).addClass("on");
		$(this).siblings().removeClass("on");
	});
	
})

/*头部固定导航平滑滚动*/
$(function(){
	var headerClickBollean = false
  $('a[href*=#]').click(function() {
		if(headerClickBollean == true){return false;}
		headerClickBollean = true
		$(window).off('scroll',addremoveHeader)
		$(".nav-xm-info .tabs li").removeClass("on");
		$(this).parent("li").addClass("on");
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
			var $target = $(this.hash);
			$target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
			if ($target.length) {

				var headerHeight = 100
				if($('.nav-xm-info').css('position') != 'fixed'){
					headerHeight = 200
				}

				var targetOffset = $target.offset().top-headerHeight;
				$('html,body').animate({scrollTop: targetOffset}, 1000,function(){
					$(window).on('scroll',addremoveHeader)
					headerClickBollean = false
				});
				//console.log(targetOffset)
				return false;
			}
			
		}
	});
	
    // 获取offsetTop
     function getOffsetTop(val) {
		if($(val).length > 0){
			return parseInt(val.offset().top);
		}
		return 0
    }
	
	var htmlH = $(window).height();
	var jmxqOffsetTop = getOffsetTop($("#jmxq"))-200;
	//console.log(jmxqOffsetTop);
	
	var jmfyOffsetTop = getOffsetTop($("#jmfy"))-200;
	//console.log(jmfyOffsetTop);
	
	var jmysOffsetTop = getOffsetTop($("#jmys"))-200;
	//console.log(jmysOffsetTop);
	
	var jmlcOffsetTop = getOffsetTop($("#jmlc"))-200;
	//console.log(jmlcOffsetTop);
	
	function addremoveHeader(){
		var scrollTop = $(this).scrollTop()
		if(scrollTop<jmfyOffsetTop){
			$(".nav-xm-info .tabs li").removeClass("on");
			$(".nav-xm-info .tabs li").eq(1).addClass("on");
		}else if(scrollTop<jmysOffsetTop){
			$(".nav-xm-info .tabs li").removeClass("on");
			$(".nav-xm-info .tabs li").eq(2).addClass("on");
		}else if(scrollTop<jmlcOffsetTop){
			$(".nav-xm-info .tabs li").removeClass("on");
			$(".nav-xm-info .tabs li").eq(3).addClass("on");
		}else{
			$(".nav-xm-info .tabs li").removeClass("on");
			$(".nav-xm-info .tabs li").eq(4).addClass("on");
		}
	}

	$(window).scroll(addremoveHeader);
	
});


/*右侧固定-plug*/
$(document).ready(function () {
	$('.main-right').theiaStickySidebar({
			additionalMarginTop: 100,
	});
});


/*顶部导航跟着屏幕滚动*/
$(function() {
	if($(".main-left .content").length>0){
		setTimeout(function(){
			var mainTop = $(".main-left").offset().top;
			var footTop = $(".xm-mesg").offset().top;
			var navXm = $(".nav-xm-info").height() ;
			var zxH = $(".xm-zx").height() ;
			var liuchengH = $(".xm-liucheng").height() ;
			var toTopagentcont = footTop - zxH - liuchengH - navXm-100;
			$(window).scroll(function() {
				if(mainTop < $(document).scrollTop() && $(document).scrollTop() < toTopagentcont) {
					$(".nav-xm-info").css({"position":"fixed","top":"0px","bottom":"auto"});
				}else if($(document).scrollTop() <= mainTop){
					$(".nav-xm-info").css("position","inherit");
				}else{
					var bottom1=window.scrollY;
					var bottom2=document.documentElement.clientHeight;
					var bottom_ath=bottom1-footTop+bottom2;
					var bottom_ath1 = $(".xm-mesg").height() + $(".footer").height() + 20;
					$(".nav-xm-info").css({"position":"fixed","top":"auto","bottom":bottom_ath1+"px"});
				}
			});
		},20)
	}
	
	if($(".main-cont .cont").length>0){
		setTimeout(function(){
			var mainTop = $(".main-cont").offset().top;
			var footTop = $(".xm-mesg").offset().top;
			var navXm = $(".nav-xm-info").height() ;
			var toTopagentcont = footTop - navXm;
			$(window).scroll(function() {
				if(mainTop < $(document).scrollTop() && $(document).scrollTop() < toTopagentcont) {
					$(".nav-xm-info").css({"position":"fixed","top":"0px","bottom":"auto"});
				}else if($(document).scrollTop() <= mainTop){
					$(".nav-xm-info").css("position","inherit");
				}else{
					var bottom1=window.scrollY;
					var bottom2=document.documentElement.clientHeight;
					var bottom_ath=bottom1-footTop+bottom2;
					var bottom_ath1 = $(".xm-mesg").height() + $(".footer").height();
					$(".nav-xm-info").css({"position":"fixed","top":"auto","bottom":bottom_ath1+"px"});
				}
			});
		},20)
	}
	
});	

// 点击文字添加到留言
$(function(){	
	setTimeout(function(){
		$('.form-right ul li').click(function(){
			var text = $(this).text()
			$('.form-center .item textarea').val(text)
		})
	},20)
})