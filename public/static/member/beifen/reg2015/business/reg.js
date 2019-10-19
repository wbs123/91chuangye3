// JavaScript Document
$(function(){
	
	//通用按钮Hover状态
	var hoverObj=$(".calendarSelect,input[type='submit'],input[type='button']");
		hoverObj.hover(
		function(){
			$(this).stop().animate({"opacity":0.7},500);
		}, 
		function(){
			$(this).stop().animate({"opacity":1},500);
	});
	
		
   $('.selectReg li').hover(
		function(){
			$(this).addClass('selected').siblings('li').stop().animate({"opacity":0.5},500);
		}, 
		function(){
			$(this).removeClass('selected').siblings('li').stop().animate({"opacity":1},500);

	});
		
});
