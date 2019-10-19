$(function(){
//向上滚动的十大品牌
$(".txtMarquee-top").slide({
	mainCell: ".bd ul",
	autoPlay: true,
	effect: "topLoop",
	interTime: 3000
});
//优惠公告
$(".youhui").slide({
	mainCell: "ul",
	autoPlay: true,
	effect: "topLoop",
	vis: 5,
	interTime: 5000,
});
//友情链接滚动
var $swapF = $('.friendUl');
var movetotopF;
$swapF.hover(function() {
	clearInterval(movetotopF);
}, function() {
	movetotopF = setInterval(function() {
		var li_height = $swapF.find('ul').height();
		$swapF.find('ul:first').animate({
			marginTop: -li_height + 'px'
		}, 600, function() {
			$swapF.find('ul:first').css('marginTop', 0).appendTo($swapF);
		});
	}, 5000);
}).trigger('mouseleave');
//甜点加盟
$(".picScroll-left").slide({
	titCell: ".hd ul",
	mainCell: ".bd ul",
	autoPage: true,
	effect: "left",
	scroll: 10,
	vis: 10
});

//tab栏的切换
$("#ranking .newest .tab-pai ul li").eq(0).addClass("on").siblings().removeClass("on");
$("#ranking .newest .tab-pai .content-tab").eq(0).addClass("on").siblings().removeClass("on");
$("#ranking .newest .tab-pai ul li").mouseover(function() {	
		var i = $(this).index();
		console.log(i);
		$(this).addClass("on").siblings().removeClass("on");
		$("#ranking .newest .tab-pai .content-tab").eq(i).addClass("on").siblings().removeClass("on");
});

});