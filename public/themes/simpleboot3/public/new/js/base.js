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
    
    //Is the fold visible
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
    //Fold
    $('.filter-more').click(function(){

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

    // xm-list showInfo
    $(".m-company a.text").hover(function(){
        $(this).siblings(".showInfo").addClass("show");
    },function(){
        $(this).siblings(".showInfo").removeClass("show");
    });
    

    //xm-list xmtj
    $(".xmtj .tab-cont").first().show().siblings().hide();
    $(".xmtj .tab li").first().addClass("active").siblings().removeClass("active");
    // $(".xmtj .tab-cont dd a:nth-last-child(1)").addClass("bdrnone");
    // $(".xmtj .tab-cont dd:nth-last-child(1) a").addClass("bdbnone");
    $(".xmtj .tab li").mouseover(function(){
        var x=$(this).index();        
        $(this).addClass("active").siblings().removeClass("active");
        $(".xmtj .tab-cont").eq(x).show().siblings().hide();
    });

    $("#Free_phone_btn_1").click(function() {
          var reTel = /^1[3|4|5|7|8|9]\d{9}$/;
          if(reTel.test($("#Free_phone_text_1").val()) === false) {
            alert("请输入正确的电话号码！");
            $("#Free_phone_text_1").focus().select();
            return false;
          }
    });

    //gotop
    $(".gotop").click(function(){
        $("body,html").animate({
          scrollTop:0
        },500)
    });

    //关于我们当前导航样式
    var myNav = $(".sidebar a");
    for(var i=0;i<myNav.length;i++){
    var links = myNav[i].getAttribute("href");
    var myURL = document.location.href;
    if(myURL.indexOf(links) != -1){
        myNav[i].className="on";
        }
    }

    // 热门榜单滚动 start
    var H = $(".main-today-box a").height();
    var listScrollTime = window.setInterval(listScroll, 2500);
    $(".main-today-box").hover(function () {
        clearInterval(listScrollTime);
    }, function () {
        listScrollTime = window.setInterval(listScroll, 2500);
    });
    function listScroll() {
        $(".main-today-box").animate({"marginTop": -H + "px"}, 300, function () {
            $(this).find("a:lt(4)").appendTo(this);
            $(this).css({"marginTop": 0 + "px"});
        });
    }

    // 排行榜最近加盟项目
    jQuery(".foucebox").slide({ 
        effect:"fold",
        autoPlay:true, 
        delayTime:300, 
        startFun:function(i){
            jQuery(".foucebox .showDiv").eq(i).find("h2").css({display:"none",bottom:0}).animate({opacity:"show",bottom:"60px"},300);
            jQuery(".foucebox .showDiv").eq(i).find("p").css({display:"none",bottom:0}).animate({opacity:"show",bottom:"0px"},300);}
        });

    // hot news
    $('#select_btn li:first').css('border','none');
    if ($('#zSlider').length) {
        zSlider();
        $('#h_sns').find('img').hover(function(){
            $(this).fadeTo(200,0.5);
        }, function(){
            $(this).fadeTo(100,1);
        });
    }
    function zSlider(ID, delay){
        var ID=ID?ID:'#zSlider';
        var delay=delay?delay:5000;
        var currentEQ=0, picnum=$('#picshow_img li').size(), autoScrollFUN;
        $('#select_btn li').eq(currentEQ).addClass('current');
        $('#picshow_img li').eq(currentEQ).show();
        $('#picshow_tx li').eq(currentEQ).show();
        autoScrollFUN=setTimeout(autoScroll, delay);
        function autoScroll(){
            clearTimeout(autoScrollFUN);
            currentEQ++;
            if (currentEQ>picnum-1) currentEQ=0;
            $('#select_btn li').removeClass('current');
            $('#picshow_img li').hide();
            $('#picshow_tx li').hide().eq(currentEQ).show();
            $('#select_btn li').eq(currentEQ).addClass('current');
            $('#picshow_img li').eq(currentEQ).show();
            autoScrollFUN = setTimeout(autoScroll, delay);
        }
        $('#picshow').hover(function(){
            clearTimeout(autoScrollFUN);
        }, function(){
            autoScrollFUN = setTimeout(autoScroll, delay);
        });
        $('#select_btn li').hover(function(){
            var picEQ=$('#select_btn li').index($(this));
            if (picEQ==currentEQ) return false;
            currentEQ = picEQ;
            $('#select_btn li').removeClass('current');
            $('#picshow_img li').hide();
            $('#picshow_tx li').hide().eq(currentEQ).show();
            $('#select_btn li').eq(currentEQ).addClass('current');
            $('#picshow_img li').eq(currentEQ).show();
            return false;
        });
    };

});

//xm-list yztel
    $("#listform").submit(function(){

        {            
            var username=$("#user").val(); 
            var tel=$("#tel").val();             
            var cn=/^[\u0391-\uFFE5]+$/; 
            var mobile = /^1[3|5|8]\d{9}$/ , phone = /^0\d{2,3}-?\d{7,8}$/;
                if(username=="" || username=="请输入姓名" || (username != "" && !cn.test(username))) 
                { 
                    alert("请输入您的中文姓名"); 
                    $("#user").focus(); 
                    return false; 
                } 
                if(username.length>5){
                    alert("请控制在5个字以内！");
                    $("#user").focus(); 
                    return false; 
                }          
                if (tel=="" || tel=="请输入手机号" || (tel != "" && !mobile.test(tel) && !phone.test(tel))) { 
                    alert("请输入正确的电话"); 
                    $("#tel").focus(); 
                    return false; 
                } 
        }
    });

    