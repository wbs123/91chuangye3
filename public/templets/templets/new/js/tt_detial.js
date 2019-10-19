

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
	$(".content1 .text a").on("click",function(){
		$("body,html").stop().animate({
    		scrollTop:$("#item6").offset().top
    	},500)
    	return false;
	});
	$(".item4boxtitle ul li").eq(0).addClass("on");
	$(".item4boxcontent .contentlist").eq(0).show();
	$(".item4boxtitle ul li").mouseover(function(){
		$(this).addClass("on").siblings().removeClass("on")
		$(".item4boxcontent .contentlist").eq($(this).index()).show().siblings().hide()
	});	

    //gotop
    $(".gotop").click(function(){
        $("body,html").animate({
          scrollTop:0
        },500)
    })
	
	$(".hotzixun .contentlist").eq(0).show()
	$(".hotzhuanti .contentlist").eq(0).show()
	$(".hotzhuanti .title li").hover(function(){
		$(this).addClass("on").siblings().removeClass("on")
		$(this).closest(".hotzhuanti").find(".contentlist").hide();
		$(this).closest(".hotzhuanti").find(".contentlist").eq($(this).index()).show()
	},function(){})
	$(".hotzixun .title li").hover(function(){
		$(this).addClass("on").siblings().removeClass("on")
		$(this).closest(".hotzixun").find(".contentlist").hide();
		$(this).closest(".hotzixun").find(".contentlist").eq($(this).index()).show()
	},function(){})
	
	$(".bigimgbox img").attr("src",$(".smallimgbox ul li").eq(0).find("img").attr("src"));
	$(".smallimgbox ul li").eq(0).addClass("on");
	$(".smallimgbox ul li").on("click",function(){
		changeimg($(this))
		btnindex=$(".smallimgbox ul li.on").index()
	})
	function changeimg(obj){
		obj.addClass("on").siblings().removeClass("on")
		var index=obj.index();
		var smallsrc=obj.find("img").attr("src");
		obj.closest(".left").find(".bigimgbox img").attr("src",smallsrc);
	}
	var btnindex=0;
	var marginLeft=0;
	var before,after;
	$(".prevbtn").on("click",function(){
		changepos('left')
		before=$(".smallimgbox ul li").last().remove()
		$(".smallimgbox ul li").first().before(before)
	})
	$(".nextbtn").on("click",function(){
		changepos('right')
		after=$(".smallimgbox ul li").first().remove()
		$(".smallimgbox ul li").last().after(after)
	})
	function changepos(string){
		btnindex=$(".smallimgbox ul li.on").index()
		switch(string){
			case 'left':
				btnindex--;
				if(btnindex==-1){ btnindex=0; }
				break;
			case 'right':
				btnindex++;
				if(btnindex==$(".smallimgbox ul li").length){
					btnindex=$(".smallimgbox ul li").length-1;
				}
				break;
		}
		$(".smallimgbox ul li").eq(btnindex).addClass("on").siblings().removeClass("on")
		var src=$(".smallimgbox ul li.on img").attr("src")
		$(".left .bigimgbox img").attr("src",src)
	}
	
	/*img有无缩略图做判断*/
	var bigImg = $("#item2 .bigimgbox");
	var smallImg = $("#item2 .smallimgbox .midbox li");
	var findsmallImg = $("#item2 .smallimgbox .midbox ul");
	if(smallImg.length == 1 && smallImg.find("img").length==0){
		smallImg.append('<img src="http://www.91chuangye.com/images/defaultpic.gif">');
		bigImg.append('<img src="http://www.91chuangye.com/images/defaultpic.gif">');
	}
	
	if(findsmallImg.find("li").length == 0){
		findsmallImg.append('<li class="on"><img src="http://www.91chuangye.com/images/defaultpic.gif"></li>');
		bigImg.find("img").attr("src","http://www.91chuangye.com/images/defaultpic.gif");
	}
	
	$("#item2 .smallimgbox .midbox li").each(function(){
		if($(this).find("img").length==0){
			$(this).hide()
		}
	});
	
	
	
	//切换首页，项目详情页和资讯页
	var index=0;
	$(".boxleft1").hide();
	$(".boxright").show();
	$("#item3").hide();
	$("#item2 .item2box .boxleft .bottom").show();
	$(".item1boxmiddle a").eq(1).find(".imgbox").addClass("on");
	$("#item2 .item2box .boxleft .top").hide().eq(0).show();
	gundong();
	if(index==0||index==2){$("#item6").hide();}else{$("#item6").show();}
	//切换选项卡实现切换模块显示隐藏的状态
	$(".item1boxmiddle a").on("click",function(){
		$(this).find(".imgbox").addClass("on").end().siblings().find(".imgbox").removeClass("on")
		$("#item2 .item2box .boxleft .top").hide().eq($(this).index()).show();
		index=$(this).index();
		// console.log(index);
		if(index==0){
			$(".boxright").hide();
			$(".boxleft1").show();
			$("#item3").show();			
			$("#item2 .item2box .boxlefttop").hide();
			$("#item2 .item2box .boxleft .top").hide();
			$("#item2 .item2box .boxleft .bottom").hide();
		}else{
			$("#item2 .item2box .boxlefttop").show();
			$(".boxleft1").hide();
			$(".boxright").show();
			$("#item3").hide();
			$("#item2 .item2box .boxleft .bottom").show();
		}
		if(index==1){
			$("#item6").show();
			$("#item2 .item2box .boxleft .top").eq(0).show();
			$("#item2 .item2box .boxleft .top").eq(1).hide();
//			scrollchange()
			$("#item1").removeClass("on")
			boxleftbottomT();
			gundong();
			
		}else{
			$("#item6").hide();
//			item1T(index)
		}
		if(index==2){
			$("#item2 .item2box .boxleft .top").eq(0).hide()
			$("#item2 .item2box .boxleft .top").eq(1).show()
		}
	})
	
	
	//多余字体用省略号代替
	$.each($(".contentlist .rightcontent .text p"), function() {
		var texts=$(this).text()
		if(texts.length>130){
			var text1=texts.substring(0,130)+"..."
			$(this).text(text1)
		}
	});
	//此处判断是否有品牌资讯，没有则显示相应无咨询页面
	if($("#item2 .item2box .boxleft .boxleftbox .boxleftcentent .contentlist").length==0){
		$(".nocontentlist").show()
	}else{ $(".nocontentlist").hide() }
	//顶部下拉框的效果
	$(".page-inner .div-right .link-nav").on("mouseover",function(){
		if($(".div-cat-popup").is(":visible")){}else{
			$(".div-cat-popup").hide().stop().slideDown()
		}
	})
	$(".page-inner").hover(function(){},function(){
		$(".div-cat-popup").stop().slideUp()
	})
	function item1T(index){
		var item1T=$("#item1").offset().top
		$(window).scroll(function(){
			if(index!=1){
				if($(document).scrollTop()>item1T){
					$('#item1').addClass("on")
			    }else{
			    	$('#item1').removeClass("on")
			    }
			}else{
				$('#item1').removeClass("on")
			}
			
		})
	}
	function boxleftbottomT(){
		var a=$("#item2 .item2box .boxleft .bottom").offset().top;
		$("#freebtn").on("click",function(){
			$("body,html").stop().animate({
	    		scrollTop:a
	    	},500)
		})
	}
	//滚动到一定高度导航变成滚动跟随状态
	function scrollchange(){
		var titletop=$(".boxleftbottom").offset().top
		var titleH=$(".boxleftbottom").height()
		var arr=nav();
		$(window).scroll(function(){
			if($(document).scrollTop()>titletop&&$(document).scrollTop()<titletop+titleH){
				$(".boxleftbottom .bottomtitle").addClass("on")
				for(var i=0;i<5;i++){
					if($(document).scrollTop()>arr[i]-170&&$(document).scrollTop()<arr[i+1]-170){
						$(".boxleftbottom .bottomtitle ul li").eq(i).addClass("on").siblings().removeClass("on")
					}
					if(i==4){
						if($(document).scrollTop()>arr[4]-170){
							$(".boxleftbottom .bottomtitle ul li").eq(4).addClass("on").siblings().removeClass("on")
						}
					}
				}
		    }else{
				$(".boxleftbottom .bottomtitle").removeClass("on")
			}
		})
	}

	function nav(){
		var arr=[];
		var a=0;
		$.each($(".boxleftbottom .bottomtitle ul li"),function(){
			arr[a]=$(this).closest(".boxleftbottom").find(".contentlist").eq(a).offset().top;
			a++;
		})
		return arr;
	}
	//点击导航定位到相应高度
//	var bottomtitle=$("#item2 .boxleftbottom .bottomtitle").height()
//	$(".boxleftbottom .bottomtitle ul").on("click","li",function(){
//		$(this).addClass("on").siblings().removeClass("on")
//		var topH=$(this).closest(".boxleftbottom").find(".contentlist").eq($(this).index()).offset().top;
//		$("body,html").stop().animate({
//  		scrollTop:topH-bottomtitle
//  	},500)
//  	return false;
//	})
//	//用于替换标题
//	titletextchange()
//	function titletextchange(){
//		var arr=[];
//		var html="";
//		var listlength=$(".bottomcontent .contentlist").length;
//		for(var i=0;i<listlength;i++){
//			arr[i]=$(".bottomcontent .contentlist .title").eq(i).find("o").text();
//			html+="<li>加盟"+arr[i]+"</li>";
//		}
//		$(".bottomtitle ul").html(html);
//	}
	
	
	function gundong(){
		var height=$(".boxleftbottom").height();
		var top=$(".boxleftbottom").offset().top;
		$(window).scroll(function(){
			if($(document).scrollTop()>top&&$(document).scrollTop()<top+height){
				$(".boxleftbottom #bottomtitle").addClass("on")
		    }else{
				$(".boxleftbottom #bottomtitle").removeClass("on")
			}
		})
	}
	
	
	
	
	//用于替换标题
	titletextchange()
	function titletextchange(){
		var arr=[];
		var html="";
		var listlength=$("#bottomcontent o").length;
		var kongarr=[];
		
		$.each($("#bottomcontent o"),function(index,o){
			if($(o).text()){
				var obj = {
					name:$(o).text(),
					val:$(o).offset().top
				};
				$(o).attr('data-index',kongarr.length);
				kongarr.push(obj);
				
			}else{
				
			}
		})
		// $("#bottomtitle ul").empty();
		// $.each(kongarr,function(index,o){
		// 	var html='<li data-scroll-y="'+o.val+'">加盟'+o.name+'</li>';
		// 	$("#bottomtitle ul").append(html);
		// })
		
		$("#bottomtitle ul li").eq(0).addClass('on');
	}
	
	//点击导航定位到相应高度
	var bottomtitle=$("#bottomtitle").height();
	$("#bottomtitle ul").on("click","li",function(){
		$(this).addClass("on").siblings().removeClass("on");
		
		var index= $(this).index();
		var topH=$("#bottomcontent").find("o[data-index="+index+']').offset().top;
		
		$("body,html").stop().animate({
    		scrollTop:topH-bottomtitle-70
    	},500)
    	return false;
    	
    	
    
    	
	})
	
	


	
	//点击右侧快捷留言功能
	$("#quickMessage li a p").on("click",function(){
		var texts=$(this).text()
		$("#Message").text(texts)
	})
	//搜索框功能
	$("#titleselect .inputbox .selectboxdown1 ul li").on("click",function(){
		$("#titleselect .inputbox .selectdropdown p").text($(this).text())
		$("#titleselect .inputbox").find(".selectboxdown1").slideUp()
	})
	$("#titleselect .inputbox .selectdropdown .icon1").show()
	$("#titleselect .inputbox .selectdropdown").on("click",function(){
		if($(this).next().is(":visible")){
			$(this).next().stop().slideUp()
		}else{
			$(this).next().hide().stop().slideDown()
			$(this).find(".icon2").show()
			$(this).find(".icon1").hide()
		}
		if($(this).next().next().is(":visible")){
			$(this).next().next().slideUp()
		}
	})
	
	$("#titleselect .inputbox input").on("click",function(){
		if($(this).parent().find(".selectboxdown1").is(":visible")){
			$(this).parent().find(".selectboxdown1").stop().slideUp()
		}
		if($(this).parent().find(".selectboxdown2").is(":visible")){
			$(this).parent().find(".selectboxdown2").stop().slideUp()
		}else{
			$(this).parent().find(".selectboxdown2").slideDown()
		}
	})
	$(".inputbox").hover(function(){},function(){
		$(this).find(".icon2").hide()
		$(this).find(".icon1").show()
		$(this).find(".selectboxdown1").slideUp()
		$(this).find(".selectboxdown2").slideUp()
	})
	
	$("#titleselect .inputbox .selectboxdown2 .selectlist").on("click",function(){
		$(".inputbox input").val($(this).find("p").text())
		$(this).parent().slideUp()
		$(".inputbox input").focus()
	})
	
	//侧栏悬浮窗
	$("#float .floatlist.zixun").on("click",function(){
		var top=$("#item3").offset().top;
		$("body,html").animate({
			scrollTop:top
		},500)
	})
	$("#float .floatlist.active").hover(function(){
		$("#float .weixinimg").show()
	},function(){
		$("#float .weixinimg").hide()
	})
	$("#float .floatlist1").on("click",function(){
		$("body,html").animate({scrollTop:0},700); 
	})
//	底部悬浮窗点击事件
	$("#floatbottom .imgbox").on("click",function(){
		$(this).find(".pos").toggleClass("on")
		if($(this).find(".pos").hasClass("on")){
			$("#floatbottom").animate({
				bottom:"-70px"
			},500)
		}else{
			$("#floatbottom").animate({
				bottom:"0"
			},500)
		}
		
	})
	//广告位，友情链接下划线特效
	mainleftnav()
    function mainleftnav() {
        var left = 0, width = 0, obj = $(".item4boxtitle .line");
        $(".item4boxtitle ul").find("li").each(function() {
            if ($(this).hasClass("on")) {
                left = $(this).position().left;
                width = $(this).outerWidth();
                setTimeout(function() {
                    obj.stop(true, false).animate({left: left, width: width});
                }, 500);
            }
            $(this).on("click",function() {
                var l = $(this).position().left,
                w = $(this).outerWidth();
                obj.stop(true, false).animate({left: l, width: w});
            });
        });
    }
    
    $('#area-right-but1').click(function() {
		MS = "XXQ-008"
		key = $('.area-right-tel1').val();
		form(MS)
	})
    function form(MS){
		//console.log(MS)
		  var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
		  var ProjectID = $("#form_contact_us_ProjectID").val();
		  if (reTel.test(key) === false) {
		    alert("请输入正确的电话号码！");
		    return false;
		  }
		 $.ajax({
		      type : "get",  
		      async:false,  
		      url : prefix+"jm/msgsubmit?ProjectID=" + ProjectID  + "&URLTitle="+ document.title +"&Tel=" + key + "&MessageSource=" + MS + "&url=" + window.location.href, 
		      dataType : "jsonp",//数据类型为jsonp  
		      jsonp: "jsonpCallback",//服务端用于接收callback调用的function名的参数  
		      jsonpCallback: "jsonpCallback",
		      success : function(data){  
		    	if(data.status == '1'){
		    		custFeedback();
		    	}
		        alert("呼叫成功,请等候来电");
		      },  
		      error:function(){  
		          alert('fail'); 
		      }  
		});  
	} 
	item2content()
	function item2content(){
		var i=0;
		$("#item2 .item2box .boxright .cainixihuan .content").eq(0).show()
	    $(".cainixihuan .titleright").on("click",function(){
	    	i++;
	    	$("#item2 .item2box .boxright .cainixihuan .content").hide().eq(i).show();
	    	if(i==3){
	    		i=0;
	    		$("#item2 .item2box .boxright .cainixihuan .content").eq(0).show()
	    	}
	    })
	}
	
	
	$(".bottombtnleft").click(function(){
		
		var reTel =/^1[3|4|5|7|8|9]\d{9}$/;
		var telnumber =$("#bottomTel").val();
		var title = document.title;
		var url = window.location.href;
		var data = {
			ProjectID:$("#ProjectID").val(),
			URLTitle:title,
			Tel:telnumber,
			URL:url,
		};
		
		
		if(reTel.test(telnumber) === false) {
			alert("请输入正确的电话号码！");
			return false;
		}
		
		$.ajax({
			type:"get",
			url:prefix+"jm/msgsubmit",
			async:true,
			dataType: "jsonp", //数据类型为jsonp
	          jsonp: "jsonpCallback", //服务端用于接收callback调用的function名的参数
	          jsonpCallback: "jsonpCallback",
			data:data,
			success:function(res){
				alert('留言成功！')
			},
			error:function(){
				alert('fail！')
			}
		});
		
	})
	
	
})