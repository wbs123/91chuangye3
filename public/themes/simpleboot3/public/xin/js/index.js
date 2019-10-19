$(function(){
	//显示网站导航
	$('#topNavRightNav').on('mouseenter', function() {
		$('#netNav').stop(false, true);
		$('#netNav').slideDown();
	});
	$('.h-top').on('mouseleave', function() {
		$('#netNav').stop(false, true);
		$('#netNav').slideUp();
	});
	
	/*hdxw-news-tabs*/
	$(".hdxw .tabs li").eq(0).addClass("on").siblings().removeClass('on');
	$(".hdxw .zixun .cont").eq(0).show().siblings().hide();
	$(".hdxw .tabs li").hover(function() {
		$(".hdxw .tabs li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".hdxw .zixun .cont").hide().eq($(this).index()).show();
	});
	
	/*hdxw-news-tabs*/
	$(".phb-top .tabs li").eq(0).addClass("on").siblings().removeClass('on');
	$(".phb-top .zixun .cont").eq(0).show().siblings().hide();
	$(".phb-top .tabs li").hover(function() {
		$(".phb-top .tabs li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".phb-top .zixun .cont").hide().eq($(this).index()).show();
	});
	
	/*项目推荐*/
	$(".project-tj .tabs a").eq(0).addClass("on").siblings().removeClass('on');
  $(".project-tj .small ul").eq(0).show().siblings().hide();   
	$(".project-tj .tabs a").hover(function() {
		$(".project-tj .tabs a").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".project-tj .small ul").hide().eq($(this).index()).show();
	});
	
	$(".project-news .tabs li").eq(0).addClass("on").siblings().removeClass('on');
	$(".project-news .cont").eq(0).show().siblings().hide();   
	$(".project-news .tabs li").hover(function() {
		$(".project-news .tabs li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".project-news .cont").hide().eq($(this).index()).show();
	});
	

	
	/*项目库-新闻资讯*/
	$(".side-news .tabs li").eq(0).addClass("on").siblings().removeClass('on');
	$(".side-news .cont ul").eq(0).show().siblings().hide();   
	$(".side-news .tabs li").hover(function() {
		$(".side-news .tabs li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".side-news .cont ul").hide().eq($(this).index()).show();
	});
	
	/*项目库-行业推荐*/
	$(".hangye-tj .tabs li").eq(0).addClass("on").siblings().removeClass('on');
	$(".hangye-tj .cont ul").eq(0).show().siblings().hide();   
	$(".hangye-tj .tabs li").hover(function() {
		$(".hangye-tj .tabs li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".hangye-tj .cont ul").hide().eq($(this).index()).show();
	});
	
	
	/*phb-pages*/
	$(".phb-item").each(function(){
		$(this).find(".cont li").first().addClass("link-hover");
	});
	$(".phb-item .cont li").hover(function() {
		$(this).addClass("link-hover");
		$(this).siblings().removeClass("link-hover");
	});
	

});

/*右侧漂浮 start*/
$(function(){
	/*liuyan*/
	$('.right_float .unit2').on('mouseenter', function() {
		$('.item-zx').show();
	});
	$('.right_float .unit2').on('mouseleave', function() {
		$('.item-zx').hide();
	});
	
	/*wx*/
	$('.right_float .unit3').on('mouseenter', function() {
		$('.item-wx').show();
	});
	$('.right_float .unit3').on('mouseleave', function() {
		$('.item-wx').hide();
	});
})

/*function showApp(index){
	index == 1 ?  $("#unit_left_consult1").toggle(): $("#unit_left_consult1").hide() ; 
	index == 3 ?  $("#unit_left_consult3").toggle(): $("#unit_left_consult3").hide() ; 
	index == 1 ? document.getElementsByClassName('float-img')[1].style.display = 'none':document.getElementsByClassName('float-img')[1].style.display = 'block';
	index == 3 ? document.getElementsByClassName('float-img')[3].style.display = 'none':document.getElementsByClassName('float-img')[3].style.display = 'block';
	index == 1 ? document.getElementsByClassName('right_float_info')[1].classList.add('u_active'):document.getElementsByClassName('right_float_info')[1].classList.remove('u_active');
	index == 3 ? document.getElementsByClassName('right_float_info')[3].classList.add('u_active'):document.getElementsByClassName('right_float_info')[3].classList.remove('u_active');
}*/




// toTop
function pageScroll() {
	scrollToptimer = setTimeout(function(){
		var top = document.body.scrollTop || document.documentElement.scrollTop;
		var speed = Math.max(top / 4, 10);
		if (document.body.scrollTop != 0) {
			document.body.scrollTop -= speed;
		} else {
			document.documentElement.scrollTop -= speed;
		}
		if (top <= 0) {
			clearInterval(scrollToptimer);
		} else {
			pageScroll();
		}
	}, 33);
}

//获取浏览器的高度
 function getScrollTop() {
	var scroll_top = 0;
	var Loacurl = window.location.href.toString();
	if (document.documentElement && document.documentElement.scrollTop) {
		scroll_top = document.documentElement.scrollTop;
	
	}
	else if (document.body) {
		scroll_top = document.body.scrollTop;
	}
			if(scroll_top>=263){
				document.getElementById('upTodo').style.visibility = 'visible';
				document.getElementById('upTodo').style.opacity = '1';
			}else{
				document.getElementById('upTodo').style.opacity = '0';
			}
			if (Loacurl.indexOf("project?") >= 0) { //判断url地址中是否包含sjList字符串
				if(scroll_top>=283){
				 document.getElementsByClassName('fix-screen')[0].style.display= 'block'
				}else{
				 document.getElementsByClassName('fix-screen')[0].style.display= 'none'
				}
			}
			if (Loacurl.indexOf("project?") >= 0) { //判断url地址中是否包含sjs字符串
				if(scroll_top>=283){
				 document.getElementsByClassName('fix-screen')[0].style.display= 'block'
				}else{
				 document.getElementsByClassName('fix-screen')[0].style.display= 'none'
				}
			}
			if (Loacurl.indexOf("project/") >= 0) { //判断url地址中是否包含host字符串
				if(scroll_top>=283){
				 document.getElementsByClassName('fix-screen')[0].style.display= 'block'
				}else{
				 document.getElementsByClassName('fix-screen')[0].style.display= 'none'
				}
				if (Loacurl.indexOf("html") >= 0) {
					if(scroll_top>=603){
					 document.getElementById('host_fixTop').style.display= 'block'
					 }else{
					 document.getElementById('host_fixTop').style.display= 'none'
					}
				}   
			}    
		}
//获取浏览器的高度
window.onscroll=function(){
	getScrollTop();
}
/*右侧漂浮 end*/


/*banner*/
$(function(){
    //slideshow
    var el = null;
    var elSize = {
        width: 0,
        height: 0
    };
    var idx = 0;
    var length = 0;
    var timerId = null;
    //动画方向：
    //POS_LTR = 从左到右
    //POS_TTB = 从上到下
    var POS_LTR = 0, POS_TTB = 1;
    var position = POS_LTR;
    
    function slideTo(toIdx){
        idx = toIdx;
        if (position == POS_LTR) {
            el.find(".hdxw-img .images-inner").animate({
                left: (-elSize.width * idx) + "px"
            });
        } else if (position == POS_TTB) {
            el.find(".hdxw-img .images-inner").animate({
                top: (-elSize.height * idx) + "px"
            });
        }
        el.find(".hdxw-dots .dots-inner .dot-icon").eq(idx).addClass("dot-active").removeClass("dot-normal").siblings().addClass("dot-normal").removeClass("dot-active");
        startTimer();
    }
    
    function stopTimer(){
        if (timerId) {
            window.clearTimeout(timerId);
            timerId = null;
        }
    }
    
    function startTimer(){
        stopTimer();
        timerId = window.setTimeout(function(){
            var toIdx = idx + 1;
            if (toIdx > length - 1) {
                toIdx = 0;
            }
            slideTo(toIdx);
        }, 5000);
    }
    
    function initDotsHtml(){
        var html = '';
        for (var i = 0; i < length; i++) {
            html += '<a class="dot-icon ' + (i === 0 ? 'dot-active' : 'dot-normal') + '" data-idx="' + i + '">&nbsp;&nbsp;&nbsp;</a>';
        }
        el.find(".hdxw-dots .dots-inner").html(html);
        
        el.find(".hdxw-dots .dots-inner .dot-icon").click(function(){
            var toIdx = parseFloat($(this).attr("data-idx"));
            slideTo(toIdx);
        });
    }
    
    function hdxw(){
        el = $(".hdxw");
        elSize.width = el.width();
        elSize.height = el.height();
        length = el.find(".hdxw-img .images-inner .link").length;
        $(".hdxw-img .images-inner .link").width(elSize.width);
		
        if (position == POS_LTR) {
            el.find(".hdxw-img .images-inner").width(elSize.width * length).height(elSize.height);
        } else if (position == POS_TTB) {
            el.find(".hdxw-img .images-inner").width(elSize.width).height(elSize.height * length);
        }
        
        initDotsHtml();
        
        startTimer();
    }
    
    hdxw();
    
});



//图片懒加载
$(function() {
  $("img.lazy").lazyload({
			effect: "fadeIn",
			threshold: 200,
			skip_invisible: false
	});
});

// 项目库头部图片加链接
if($('.h-nav-xm dt').length>0){
	$('.h-nav-xm dt').css({
		'cursor':'pointer'
	})
	$('.h-nav-xm dt').click(function(){
		location.href = $(this).find('a').attr('href')
	})
}
