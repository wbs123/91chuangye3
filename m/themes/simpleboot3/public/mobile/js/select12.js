$(function(){

	/*下拉选择*/
	var myScroll_1 = new IScroll('.selected_box_0 .left_box', { click: true});
	var myScroll_2 = new IScroll('.selected_box_0 .right_box', { click: true});
   
    var myScroll_box = new IScroll('#wrapper2', {
      mouseWheel: true,
      scrollbars: true
  });
	$(".item_chose_1 li").click(function(){
		$(".shadow").show()
		$(".item_chose_1 li").eq($(this).index()).addClass("active").siblings().removeClass('active');
		$(".selected_box").hide().eq($(this).index()).show();
		var myScroll_1 = new IScroll('.selected_box_0 .left_box', { click: true});
		var myScroll_2 = new IScroll('.selected_box_0 .right_box', { click: true});
		var myScroll_3 = new IScroll('.selected_box_1', { click: true});
		var myScroll_4 = new IScroll('.selected_box_2 .left_box', { click: true});
		var myScroll_5 = new IScroll('.selected_box_2 .right_box', { click: true});
	})
	
	$(".shadow").click(function(){
		$(".item_chose_1 li").removeClass('active');
		$(".selected_box").hide()
		$(".shadow").hide()
	})
	
	$(".selected_box_0 .right ul").eq(0).show();
	$(".selected_box_0 .left li").click(function() {
		$(".selected_box_0 .left li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		$(".selected_box_0 .right ul").hide().eq($(this).index()).show();
		var myScroll_2 = new IScroll('.selected_box_0 .right_box', { click: true});
	});
	
	$(".item_chose_2 li").click(function(){
		$(".item_chose_2 li").eq($(this).index()).addClass("on").siblings().removeClass('on');
	})
	
	

});
