/*link-hover*/
$(function(){
	/*新品上线*/
	$(".wd-5 li").hover(function() {
		$(this).addClass("link-hover");
		$(this).siblings().removeClass("link-hover");
	});
});	