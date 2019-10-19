$(function(){
	/*餐饮加盟人气榜&&餐饮加盟新品榜*/
	$(".zixun-cont ul").eq(0).show();
	$(".contentbox3 .link-tab").hover(function() {
    $(".contentbox3 .link-tab").eq($(this).index()).addClass("link-active").siblings().removeClass('link-active');
    $(".zixun-cont ul").hide().eq($(this).index()).show();
  });
	
	/*项目推荐*/
	$(".food-6.xmtj .tab-cont").eq(0).show();
	$(".food-6.xmtj .tab li").eq(0).addClass("active");
	$(".food-6.xmtj .tab li").hover(function() {
		$(".food-6.xmtj .tab li").eq($(this).index()).addClass("active").siblings().removeClass('active');
		$(".food-6.xmtj .tab-cont").hide().eq($(this).index()).show();
	});
	$(".food-6.xmtj .tab li:last").addClass("last");
    
    //gotop
    $(".gotop").click(function(){
        $("body,html").animate({
          scrollTop:0
        },500)
    });
	
});  

/*轮播*/
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
            el.find(".images-inner").animate({
                left: (-elSize.width * idx) + "px"
            });
        } else if (position == POS_TTB) {
            el.find(".images-inner").animate({
                top: (-elSize.height * idx) + "px"
            });
        }
        el.find(".dots-inner .dot-icon").eq(idx).addClass("dot-active").removeClass("dot-normal").siblings().addClass("dot-normal").removeClass("dot-active");
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
        el.find(".dots-inner").html(html);
        
        el.find(".dots-inner .dot-icon").click(function(){
            var toIdx = parseFloat($(this).attr("data-idx"));
            slideTo(toIdx);
        });
    }
    
    function main(){
        el = $("#food-slideshow");
        elSize.width = el.width();
        elSize.height = el.height();
        length = el.find(".images-inner .link").length;
        
        if (position == POS_LTR) {
            el.find(".images-inner").width(elSize.width * length).height(elSize.height);
        } else if (position == POS_TTB) {
            el.find(".images-inner").width(elSize.width).height(elSize.height * length);
        }
        
        initDotsHtml();
        
        startTimer();
    }
    
    main();
    
});