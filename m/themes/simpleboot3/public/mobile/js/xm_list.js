$(function(){
	/*fenlei_chose*/
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
  
  
	
	$(".show_more").click(function(){
		if($(this).hasClass("open")){
			$(this).removeClass("open");
			$(this).prev().removeClass("open");
			$(this).find("em").html("加载更多")
		}else{
			$(this).addClass("open");
			$(this).prev().addClass("open");
			$(this).find("em").html("收起")
		}
	})
	
	// $(".jm_tab_item").eq(0).show();
	$(".jminfo_tab li").eq(0).addClass("on").siblings().removeClass('on');
	$(".jminfo_tab li").click(function() {
		$(".jminfo_tab li").eq($(this).index()).addClass("on").siblings().removeClass('on');
		// $(".jm_tab_item").hide().eq($(this).index()).show();
	});
	
	
	/*search_alert*/
	$('.sear').click(function () {
		$('.sear_box').addClass('show');
	});
	$('.sear_box').find('.back').click(function () {
		$('.sear_box').removeClass('show');
	});

	

	
	$(".item_paihang_bar li").click(function(){
		$(".item_paihang_bar li").eq($(this).index()).addClass("on").siblings().removeClass('on');
	})
	
	/*returnTop*/
	$("#returnTop").click(function(){
	    $('html,body').animate({
	        scrollTop: '0px'
	    }, 800);
	 });
	 

	
	//banner
	TouchSlide({
		slideCell:"#focus",
		titCell:".hd ul", 
		mainCell:".bd ul",
		effect:"left",
		autoPlay:true,
		autoPage:true
	});


	//index_nav
	TouchSlide({
		slideCell:"#index_nav",
		titCell:".hd ul", 
		mainCell:".bd ul",
		effect:"left",
		autoPlay:false,
		autoPage:true
	});
	
	// nav
	var swiper = new Swiper('.swiper-container', {
      slidesPerView: 1,
      spaceBetween: 30,
      freeMode: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
    });


	
	//scrollDiv1
	// $("#scrollDiv1").slide({mainCell:".bd ul",effect:"topLoop",autoPlay:true});

	//lazyload
	// $("img.lazy").lazyload({
	// 	effect: "fadeIn",
	// 	threshold : 200,
	// 	skip_invisible : false
	// });

	// 首页加盟列表
	$(".lmflBoxtitS1 li").eq(0).addClass("on");
	$(".lmflBoxtitS2 li").eq(0).addClass("on");
	$(".lmflBoxtitS3 li").eq(0).addClass("on");
	$(".lmflBoxListsBox1 .mHotAttctLists").eq(0).addClass("on");
	$(".lmflBoxListsBox2 .mHotAttctLists").eq(0).addClass("on");
	$(".lmflBoxListsBox3 .mHotAttctLists").eq(0).addClass("on");
	$(".lmflBoxtitS1 li").click(function(){
		var i=$(this).index();
		$(this).addClass("on").siblings().removeClass("on");
		$(".lmflBoxListsBox1 .mHotAttctLists").eq(i).addClass("on").siblings().removeClass("on");
	});
	$(".lmflBoxtitS2 li").click(function(){
		var i=$(this).index();
		$(this).addClass("on").siblings().removeClass("on");
		$(".lmflBoxListsBox2 .mHotAttctLists").eq(i).addClass("on").siblings().removeClass("on");
	});
	$(".lmflBoxtitS3 li").click(function(){
		var i=$(this).index();
		$(this).addClass("on").siblings().removeClass("on");
		$(".lmflBoxListsBox3 .mHotAttctLists").eq(i).addClass("on").siblings().removeClass("on");
	});

	//首页加盟资讯
	$(".index_news .lmflBoxtit li").eq(0).addClass("on");
	$(".index_news .Headlines").eq(0).addClass("on");
	$(".index_news .lmflBoxtit li").click(function(){
		var i=$(this).index();
		$(this).addClass("on").siblings().removeClass("on");
		$(".index_news .Headlines").eq(i).addClass("on").siblings().removeClass("on");
	});

	//首页加盟导航，项目推荐
	$("#tuijian .lmflBoxtit li").eq(0).addClass("on");
	$("#tuijian .Headlines").eq(0).addClass("on");
	$("#tuijian .lmflBoxtit li").click(function(){
		var i=$(this).index();
		$(this).addClass("on").siblings().removeClass("on");
		$("#tuijian .Headlines").eq(i).addClass("on").siblings().removeClass("on");
	});

	

	$('.js_join_1').click(function(){
		var sTop=$('#js_join_1').offset().top-100;
		$('html,body').animate({scrollTop:sTop+"px"},500);
	});
	$('.js_join_2').click(function(){
		$(".jm_box,.show_more").addClass("open");
		$(".show_more").find("em").html("收起");
		var sTop=$('#js_join_2').offset().top-100;
		$('html,body').animate({scrollTop:sTop+"px"},500);
	});
	$('.js_join_3').click(function(){
		$(".jm_box,.show_more").addClass("open");
		$(".show_more").find("em").html("收起");
		var sTop=$('#js_join_3').offset().top-100;
		$('html,body').animate({scrollTop:sTop+"px"},500);
	});
	$('.js_join_4').click(function(){
		$(".jm_box,.show_more").addClass("open");
		$(".show_more").find("em").html("收起");
		var sTop=$('#js_join_4').offset().top-100;
		$('html,body').animate({scrollTop:sTop+"px"},500);
	});

	$(".chaaaa").click(function(){
		$("#mes_div_unlogin_UNM").hide();
	});

	// top_fenlei
	// $('.ic-drop').on('click',drop);
    // function drop() {
     //    $('.drop-down-ctn').toggleClass('up');
     //    $('.shade').toggle();
    // }



	// returnTop
	$(window).scroll(function(){
        var height=$(document).scrollTop();
        var height1=$(window).height();
        var short1=height-height1;
        if(short1>0){
            $("#returnTop").fadeIn();
        }else{
            $("#returnTop").fadeOut();
        }
    });
	
});


$("#myform").submit(function(){
  var username=$("#name").val(); 
  var tel=$("#tel").val();  
  var msg=$("#msg").val();
  var cn=/^[\u0391-\uFFE5]+$/;   
  var http=/^http[s]?:\/\/([\w-]+\.)+[\w-]+([\w-./?%&=]*)?$/; 
  var mobile = /^1[3|5|8]\d{9}$/ , phone = /^0\d{2,3}-?\d{7,8}$/; 
  if(username=="" || (username != "" && !cn.test(username))) 
      { 
        alert("请输入您的中文姓名！"); 
        $("#name").focus(); 
        return false; 
      }      
      if(username.length>5){
          alert("请控制在5个字以内！");
          $("#name").focus(); 
          return false; 
      }                            
      if (tel=="" || (tel != "" && !mobile.test(tel) && !phone.test(tel))) {
          alert("请输入正确的联系方式！"); 
          $("#tel").focus(); 
          return false; 
      }
      if((msg != "" && http.test(msg))){ 
            alert("请勿输入链接！"); 
            $("#msg").focus(); 
            return false; 
        } 

});

$("#myform1").submit(function(){
  var username=$("#name1").val(); 
  var tel=$("#tel1").val();  
  var msg=$("#msg1").val();
  var cn=/^[\u0391-\uFFE5]+$/;   
  var http=/^http[s]?:\/\/([\w-]+\.)+[\w-]+([\w-./?%&=]*)?$/; 
  var mobile = /^1[3|5|8]\d{9}$/ , phone = /^0\d{2,3}-?\d{7,8}$/; 
  if(username=="" || (username != "" && !cn.test(username))) 
      { 
        alert("请输入您的中文姓名！"); 
        $("#name1").focus(); 
        return false; 
      }      
      if(username.length>5){
          alert("请控制在5个字以内！");
          $("#name1").focus(); 
          return false; 
      }                            
      if (tel=="" || (tel != "" && !mobile.test(tel) && !phone.test(tel))) {
          alert("请输入正确的联系方式！"); 
          $("#tel1").focus(); 
          return false; 
      }
      if((msg != "" && http.test(msg))){ 
            alert("请勿输入链接！"); 
            $("#msg1").focus(); 
            return false; 
        } 

});

function kd(){
	$("#mes_div_unlogin_UNM").show();
}



