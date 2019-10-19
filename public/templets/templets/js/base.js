$(function(){

	//页面滚动时，导航固定
	$(window).scroll(
			function(){
				var top=$(window).scrollTop();
				//document.title=top;
				if(top>160)
					{
						$(".navbg").addClass("navfixed");
					}
					else{
							$(".navbg").removeClass("navfixed")
						}				
				});
	// 轮播图
	$('#home_slider').flexslider({
                        animation: 'slide',
                        controlNav: true,
                        directionNav: true,/*banner左右箭头*/
                        animationLoop: true,
                        slideshow: true,
                        useCSS: true
                    });
	
	//当前导航样式
	var myNav = $(".nav a");
	for(var i=0;i<myNav.length;i++){
    var links = myNav[i].getAttribute("href");
    var myURL = document.location.href;
    if(myURL.indexOf(links) != -1){
        myNav[i].className="on";
    	}
	}

    //关于u88当前导航样式
    var myNav = $(".sidebar a");
    for(var i=0;i<myNav.length;i++){
    var links = myNav[i].getAttribute("href");
    var myURL = document.location.href;
    if(myURL.indexOf(links) != -1){
        myNav[i].className="on";
        }
    }
	

	//返回顶部
	$(".fixed").hide();
	$(window).scroll(
		function(){
			var top=$(window).scrollTop();
			if(top>200)
				{
					$(".fixed").show();
				}
				else{
						$(".fixed").hide();
					}				
			});
	
	//加盟列表页根据内容高度显示展开
	$('.filter-item').each(function(i, v) {
        if ($(this).find('ul').height()-16 > $(this).height()) {
            $(this).siblings('.filter-more').css('visibility', 'visible');
        } else {
            $(this).siblings('.filter-more').css('visibility', 'hidden');
        }
        if ($(this).find('ul li a.on').length == 1) {
            var hoverTop = $(this).find('ul li').has('a.on').position().top;
            var firstTop = $(this).find('ul li:first').position().top;
            if (hoverTop > firstTop && $(this).hasClass('filter-more-click')) {
                $(this).toggleClass('filter-more-click');
            }
        }
    });
    //加盟列表页点击展开收起
    $('.filter-more').click(function(){
        // not animated
        //$(this).siblings('.filter-item').toggleClass('filter-more-click');
        // animated
        var $filterItem = $(this).siblings('.filter-item');
        var origHeight = $filterItem.find('ul').height();
        var hiddenHeight = $filterItem.find('ul li').height();
        if ($filterItem.height() <= hiddenHeight) {
        	$(this).html("收起▲")
            $filterItem.animate({
                height : origHeight
            }, 'fast');
        } else {
        	$(this).html("展开▼")
            $filterItem.animate({
                height : hiddenHeight
            }, 'fast');
        }
    });

    // 加盟列表页 人气评论显示
    var n=0;
    $("#paihang .title ul li").eq(n).addClass("on");
    $("#paihang .paihang ul").eq(n).show().siblings().hide();
    $("#paihang .title ul li").click(function(){
    	n=$(this).index();
    	$(this).addClass("on").siblings().removeClass("on");
    	$("#paihang .paihang ul").eq(n).show().siblings().hide();
    });

    $("#article .title ul li").eq(n).addClass("on");
    $("#article .paihang ul").eq(n).show().siblings().hide();
    $("#article .title ul li").click(function(){
    	n=$(this).index();
    	$(this).addClass("on").siblings().removeClass("on");
    	$("#article .paihang ul").eq(n).show().siblings().hide();
    });

    //排行榜页面项目导航
    var x=0;
    $(".paihang-content ul").first().show().siblings().hide();
    $(".paihang-nav li").mouseover(function(){
    	x=$(this).index()-1;
    	$(".paihang-content ul").eq(x).show().siblings().hide();
    });
    


	//旧版留言板验证 
	// $("#msgform").submit(function(){ 
	//     var username=$("#user").val(); 
	//     var tel=$("#tel").val(); 
	//     var liuyan=$("#liuyan").val();
	//     var reg = /^(13[0-9]|15[012356789]|18[0-9]|17[678]|14[57])[0-9]{8}$/;
	//     var re = /^[0-9]+.?[0-9]*$/;
	//     	if(username=="") 
	//         { 
	// 	        alert("请输入您的姓名！"); 
	// 	        $("#user").focus(); 
	// 	        return false; 
	//         }          
	//         if (tel=="" || (tel != "" && !reg.test(tel))) { 
	//             alert("请输入正确有效的手机号码！"); 
	//             $("#tel").focus(); 
	//             return false; 
	//         }                       
	//         if(liuyan==""){ 
	//             alert("请输入您的需求或问题！"); 
	//             $("#liuyan").focus(); 
	//             return false; 
	//         }	                
	// }); 	
    //留言框信息 
                var msgs=$("#quickMessage li");
                for (i = 0; i < msgs.length; i++) {
                    msgs[i].onclick = function () {
                        document.getElementById('Message').value = this.innerHTML;
                    }
                }

    //新版留言板验证 
    $("#msgform").submit(function(){ 
        var username=$("#user").val(); 
        var tel=$("#tel").val(); 
        var liuyan=$("#liuyan").val();
        var reg = /^(13[0-9]|15[012356789]|18[0-9]|17[678]|14[57])[0-9]{8}$/;
        var re = /^[0-9]+.?[0-9]*$/;
            if(username=="") 
            { 
                alert("请输入您的姓名！"); 
                $("#user").focus(); 
                return false; 
            }          
            if (tel=="" || (tel != "" && !reg.test(tel))) { 
                alert("请输入正确有效的手机号码！"); 
                $("#tel").focus(); 
                return false; 
            }                       
            if(liuyan==""){ 
                alert("请输入您的需求或问题！"); 
                $("#liuyan").focus(); 
                return false; 
            }                   
    });
    function reg(){

        var phone=$('Free_phone_text_1').val();
        if (phone=="" || (phone != "" && !reg.test(phone))) { 
                alert("请输入正确有效的手机号码！"); 
                $("#phone").focus(); 
                return false; 
            }  
    }
    $('#Free_phone_btn_1').click(function(){

        
    })
	

});
