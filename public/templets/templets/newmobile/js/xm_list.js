$(function(){

	
	
	$(".item_chose_1 li").click(function(){
		$(".shadow").show()
		$(".selected_out_box").show();
		$(".confirm_btn").show();
		$(".item_chose_1 li").eq($(this).index()).addClass("active").siblings().removeClass('active');
		$(".selected_box").hide().eq($(this).index()).show();
	})
	
	
	
	$(".shadow").click(function(){
		$(".item_chose_1 li").removeClass('active');
		$(".selected_box").hide()
		$(".shadow").hide()
		$(".selected_out_box").hide()
	})
	
	$(".confirm_btn").click(function(){
		$(".shadow").hide()
		$(".selected_out_box").hide()
	})
	

	
	$(".selected_box_0 .left li").eq(0).addClass("on");
	$(".selected_box_0 .right ul").eq(0).show();
	$(".selected_box_0 .left li").click(function() {
		$(".selected_box_0 .left li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".selected_box_0 .right ul").hide().eq($(this).index()).show();
		
	});
	
	$(".item_chose_2 li").click(function(){
		$(".item_chose_2 li").eq($(this).index()).addClass("on").siblings().removeClass('on');
	})
	
});


