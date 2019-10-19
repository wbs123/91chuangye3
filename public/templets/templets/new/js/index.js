/*subnav-hover*/
$(function(){


     $("#ernav").hover(function(){
        $(".ysubnav").show();
    });
    $(".h-nav").mouseleave(function(){
        $(".ysubnav").hide();
    });

    $("#topNavRightNav").mouseover(function(){
        $("#netNav").show();
    });
    $(".h-top").mouseleave(function(){
        $("#netNav").hide();
    });
    //鼠标经过菜单，改变图标；弹出二级菜单
    function main(){
        $(".b-subnav .m-list>li").hover(function(){
            $(this).addClass("li-hover");
            $(this).find(">.link>.dot-icon").addClass("icon-ic-nav-arrow-white").removeClass("icon-ic-nav-arrow-grey");
            $(".b-subnav .ul-submenu").stop().hide();
            $(".b-subnav .ul-submenu").css("opacity",'0');
            var submenuEl = $(this).find(".ul-submenu");
            if (submenuEl) {
                submenuEl.stop().show();
                submenuEl.css("opacity",'1');
            }
        }, function(){
            $(this).removeClass("li-hover");
            $(this).find(">.link>.dot-icon").addClass("icon-ic-nav-arrow-grey").removeClass("icon-ic-nav-arrow-white");
            $(".b-subnav .ul-submenu").stop().hide();
            $(".b-subnav .ul-submenu").css("opacity",'0');
        });
    }
    
    main();
    
});

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
    
    function main(){
        el = $("#hdxw");
        elSize.width = el.width();
        elSize.height = el.height();
        length = el.find(".hdxw-img .images-inner .link").length;
        
        if (position == POS_LTR) {
            el.find(".hdxw-img .images-inner").width(elSize.width * length).height(elSize.height);
        } else if (position == POS_TTB) {
            el.find(".hdxw-img .images-inner").width(elSize.width).height(elSize.height * length);
        }
        
        initDotsHtml();
        
        startTimer();
    }
    
    main();
    
});
//图片懒加载
// $(function() {

//       $("img.lazy").lazyload({
//         effect: "fadeIn",
//         threshold : 200,
//         skip_invisible : false
//         }); 
// });
/*切换*/
$(function(){
     //行业分类
        // var slideHeight = 84;
        // var defHeight = $('.trade-type .cont .firstdd').height();
        // if(defHeight >= slideHeight){
        //     $('.trade-type .cont .firstdd').css('height' , slideHeight + 'px');
        //     $('.more').append('<em>点击查看更多</em>');
        //     $('.more').click(function(){
        //      var curHeight = $('.trade-type .cont .firstdd').height();
        //      if(curHeight == slideHeight){
        //         $('.trade-type .cont .firstdd').animate({
        //          height: defHeight
        //         }, 300);
                
        //         setTimeout( function(){
        //             $('.more em').html('点击收起');
        //             $('.more').addClass('cur');
        //         },300)
                
        //      }else{
        //         $('.trade-type .cont .firstdd').animate({
        //          height: slideHeight
        //         }, 300);
        //         setTimeout( function(){
        //             $('.more em').html('点击查看更多');
        //             $('.more').removeClass('cur');
        //         },300)
        //      }
        //      return false;
        //     });  
        // }
	/*最新商机&最新资讯*/
	$(".b-search .zixun-title li").hover(function() {
        $(".b-search .zixun-title li").eq($(this).index()).addClass("link-active").siblings().removeClass('link-active');
        $(".zixun-cont ul").hide().eq($(this).index()).show();
    });
	 
	/*热门品牌*/     
    $(".brand-hot .tab li").first().addClass("active").siblings().removeClass('active');
    $(".brand-hot .tab-cont").eq(0).show().siblings().hide();    
    $(".brand-hot .tab li").hover(function() {
        var i=$(this).index();
        $(this).addClass("active").siblings().removeClass('active');
        $(".brand-hot .tab-cont").eq(i).show().siblings().hide();
    });

    /*投资推荐*/
    $(".touzi-tj .tab li").first().addClass("active").siblings().removeClass('active');
    $(".touzi-tj .tab-cont").eq(0).show().siblings().hide();     
    $(".touzi-tj .tab li").hover(function() {
        var i=$(this).index();
        $(this).addClass("active").siblings().removeClass('active');
        $(".touzi-tj .tab-cont").eq(i).show().siblings().hide();
    }); 
    
	/*加盟资讯*/
	$("#join-story1 .tabs li").hover(function() {
		$("#join-story1 .tabs li").eq($(this).index()).addClass("active").siblings().removeClass('active');
		$("#join-story1 .tab-cont").hide().eq($(this).index()).show();
	});	 
    $("#join-story2 .tabs li").hover(function() {
        $("#join-story2 .tabs li").eq($(this).index()).addClass("active").siblings().removeClass('active');
        $("#join-story2 .tab-cont").hide().eq($(this).index()).show();
    }); 
    $("#join-story3 .tabs li").hover(function() {
        $("#join-story3 .tabs li").eq($(this).index()).addClass("active").siblings().removeClass('active');
        $("#join-story3 .tab-cont").hide().eq($(this).index()).show();
    }); 
    $("#join-story4 .tabs li").hover(function() {
        $("#join-story4 .tabs li").eq($(this).index()).addClass("active").siblings().removeClass('active');
        $("#join-story4 .tab-cont").hide().eq($(this).index()).show();
    }); 

    //gotop
    $(".gotop").click(function(){
        $("body,html").animate({
          scrollTop:0
        },500)
    });

})

/*link-hover*/
$(function(){
	/*新品上线*/
	$(".new-pro .cont li").hover(function() {
		$(this).addClass("link-hover");
		$(this).siblings().removeClass("link-hover");
	});
	
	/*热门品牌*/
	$(".brand-hot .tab-cont-news ul").each(function(){
		$(this).find("li").first().addClass("link-hover");
	});
	$(".brand-hot .tab-cont-news ul li").hover(function() {
		$(this).addClass("link-hover");
		$(this).siblings().removeClass("link-hover");
	}); 
	
	/*母婴加盟排行榜*/
	$(".paihang").each(function(){
		$(this).find(".list li").first().addClass("link-hover");
	});
	$(".paihang .list li").hover(function() {
		$(this).addClass("link-hover");
		$(this).siblings().removeClass("link-hover");
	});
})

/*问答滚动*/
window.onload=function(){
  var speed = 40 
  var demo = document.getElementById("quest");
  var demo1 = document.getElementById("demo1");
  var demo2 = document.getElementById("demo2");
  demo2.innerHTML = demo1.innerHTML;
  
  function Marquee() {
    if (demo2.offsetTop - demo.scrollTop <= 0) {
      demo.scrollTop -= demo1.offsetHeight
    }
    else {
      demo.scrollTop++
    }
  }
  
  var MyMar = setInterval(Marquee, speed)
  demo.onmouseover = function () {
    clearInterval(MyMar)
  }
  demo.onmouseout = function () {
    MyMar = setInterval(Marquee, speed)
  }
}