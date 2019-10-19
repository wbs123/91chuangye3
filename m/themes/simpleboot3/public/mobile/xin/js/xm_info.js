
$(function () {

    // 点击立即咨询弹出框
    $('.zixun').on('click', function () {
        layer.close(layer.index);
        window.lyl = layer.open({
            type: 1,
            title: '立即咨询',
            area: '90%',
            shadeClose: true,
            content: $('.item_zx .cont')
        });
        return false;
    });

    // 通话
    $('#btel').on('click', function () {
        layer.open({
            type: 1,
            title: '通话',
            area: '90%',
            shadeClose: true,
            skin: 'webtellayer',
            content: $('#webtelbox')
        });
        return false;
    });

    // 下载资料
    $('#bvzlico').on('click', function (e) {
        e.preventDefault();
        layer.open({
            type: 1,
            area: '94%',
            title: false,
            closeBtn: 0,
            shadeClose: true,
			skin: 'popupsbox',
            content: $('#popups-msg'),
        });
        return false;
    });

    // 默认加载招商海报(改)
    if($('.item_jminfo .tabs-tit li').length <= 4){
        $('.item_jminfo .haibao-cont').hide()
        $('.item_jminfo .tabs-cont').show()
        $('.item_jminfo .tabs-tit li').eq(0).addClass('on').siblings().removeClass('on')
    }else{
        $('.item_jminfo .haibao-cont').show()
        $('.item_jminfo .tabs-cont').hide()
        $('.item_jminfo .tabs-tit li').eq(0).addClass('on').siblings().removeClass('on')
    }

    //图片懒加载
    $("img.lazy").lazyload({
        effect: "fadeIn",
        threshold: 200,
        skip_invisible: false
    });

    setTimeout(function(){
        var navlistTopl = $('.itme_xminfo').offset().top+$('.itme_xminfo').height()
        $(window).scroll(function(){
            var scrollTop = $(this).scrollTop()
            if(scrollTop > navlistTopl){
                $('.item_jminfo .tabs-cont').css('margin-top',$('.item_jminfo .tabs-tit').outerHeight(true))
               $('.item_jminfo .tabs-tit').addClass('active')
            }else{
               $('.item_jminfo .tabs-tit').removeClass('active')
               $('.item_jminfo .tabs-cont').css('margin-top',0)
            }
        })
    },20)
    // $('.item_jminfo .tabs-tit li').click(function(){
    //     var index = $(this).index()
    //     $(this).addClass('on').siblings().removeClass('on')
    //     if(index == 1){
    //         var one = $('#js_join_1')
    //         $(window).scrollTop(one.offset().top - one.outerHeight(true)-50)
    //     }else if(index == 2){
    //         var one = $('#js_join_2')
    //         $(window).scrollTop(one.offset().top - one.outerHeight(true)-50)
    //     }else if(index == 3){
    //         var one = $('#js_join_3')
    //         $(window).scrollTop(one.offset().top - one.outerHeight(true)-50)
    //     }else if(index == 4){
    //         var one = $('#js_join_4')
    //         $(window).scrollTop(one.offset().top - one.outerHeight(true)-50)
    //     }else if(index == 0){
    //         var one = $('.item_jminfo .haibao-cont')
    //         $(window).scrollTop(one.offset().top-50)
    //     }
    //     if(index != 0){
    //         $('.item_jminfo .haibao-cont').hide()
    //         $('.item_jminfo .tabs-cont').show()
    //         return false;
    //     }else{
    //         $('.item_jminfo .haibao-cont').show()
    //         $('.item_jminfo .tabs-cont').hide()
    //         return false
    //     }
    // })
    

    // 轮播图
    var bannerXm = new Swiper('.item-xmbanner .swiper-container', {
        autoplay: true,
        pagination: {
            el: '.item-xmbanner .swiper-pagination',
        },
        lazy: {
            loadPrevNext: true,
        },
    });
    setTimeout(function(){
        bannerXm.update()
    },200)

    $(".item_zx .cont .agree").click(function () {
        if ($(this).hasClass("on")) {
            $(this).removeClass("on");
        } else {
            $(this).addClass("on");
        }
    })

    setSize();
    $(window).bind("resize", function () {
        setSize();
    })
});


function setSize() {
    var htmlW = $(window).width();
    var htmlH = $(window).height();
    $('.item-xmbanner').height($(".item-xmbanner").width() * 0.533);
}

$(function(){

    // 瞄点滚动到指定位置
    (function () {
        // 获取offsetTop
        function getOffsetTop(val) {
            return parseInt(val.offset().top);
        }
        // 获取高度
        function getDomHeight(val) {
            return parseInt(val.outerHeight(true));
        }
        // offsetTop与height相加
        function getSummation(val) {
            var pint = parseInt(getOffsetTop(val) + getDomHeight(val));
            return reduceHeight(pint);
        }
        // 减去定位指定DOM的高度
        function reduceHeight(val) {
            // 减去固定导航高度
            // val = val - getDomHeight($('.cata-log .list'));
            // 减去固定头部高度
            // val = val - getDomHeight($('.item_jminfo .tabs-tit.active'));
            val = val - 52 /* 52为头部固定高度 */
            return val;
        }
        // 给指定dom添加active
        function addNavActive(number) {
            if($('.item_jminfo .tabs-tit li').length<=4){
                number = number-1
            }
            return $('.item_jminfo .tabs-tit li').eq(number).addClass('on').siblings().removeClass('on');
        }
        // 获取页面高度区间
        function getSection() {
            // 获取总高度
            var domponNumber = 1;
            return function (val, number) {
                var arr = [];
                if (number == 1) {
                    domponNumber = getSummation(val);
                    // console.log(domponNumber)
                    var oneHeight = getOffsetTop(val);
                    arr = [oneHeight-100, domponNumber];
                    return arr;
                } else if (number == undefined) {
                    arr = [domponNumber];
                    domponNumber = getSummation(val);
                    arr.push(domponNumber);
                    return arr;
                }
            };
        }
        

        setTimeout(function(){

            // var closure = getSection();
            // var gsArray = closure($("#js_join_1"), 1);
            // var elArray = closure($("#js_join_2"));
            // var scArray = closure($("#js_join_3"));
            // var xsArray = closure($("#js_join_4"));
            // console.log(gsArray, elArray, scArray);
            $(window).scroll(function () {
                if($('.item_jminfo').is(':hidden')){
                    return false
                }
                var closure = getSection();
            var gsArray = closure($("#js_join_1"), 1);
            var elArray = closure($("#js_join_2"));
            var scArray = closure($("#js_join_3"));
            var xsArray = closure($("#js_join_4"));
                var scrollTop = $(this).scrollTop() + 10;
                if (scrollTop > gsArray[0] && scrollTop <= gsArray[1]) {
                    addNavActive(1);
                } else if (scrollTop > elArray[0] && scrollTop <= elArray[1]) {
                    // console.log(elArray,scrollTop)
                    addNavActive(2);
                } else if (scrollTop > scArray[0] && scrollTop <= scArray[1]) {
                    addNavActive(3);
                }else if(scrollTop > xsArray[0] && scrollTop <= xsArray[1]){
                    addNavActive(4);
                }
            });
    
        },20)

        $('.item_jminfo .tabs-tit li').click(function () {
            var index = $(this).index();

            if($('.item_jminfo .tabs-tit li').length <= 4){
                index = index+1
            }

            if(index != 0){
                $('.item_jminfo .haibao-cont').hide()
                $('.item_jminfo .tabs-cont').show()
            }else{

                location.href = $('.item_jminfo .tabs-tit li').eq(0).find('a').attr('href')

                $('.item_jminfo .haibao-cont').show()
                $('.item_jminfo .tabs-cont').hide()
                $(this).addClass('on').siblings().removeClass('on')
                
                $(window).scrollTop($(window).scrollTop()+1)
            }

            var topnumber = 0;
            if (index == 1) {

                var IMPORT_href = $('.item_jminfo .tabs-tit li').eq(1).find('a').attr('href')
                if(IMPORT_href == '#'){
                    topnumber = getOffsetTop($("#js_join_1"));
                }else{
                    location.href = IMPORT_href
                }

            } else if (index == 2) {
                topnumber = getOffsetTop($('#js_join_2'));
            } else if (index == 3) {
                topnumber = getOffsetTop($('#js_join_3'));
            } else if (index == 4) {
                topnumber = getOffsetTop($('#js_join_4'));
            }
            topnumber = topnumber - 52 - 20; /* 52为固定头部高度, 20为留个间距,可随意调整 */
            $("html,body").stop().animate({ scrollTop: topnumber+1 }, 500);
            
            return false

        });

        if($('.haibao-cont').length <=0){
            $('.item_jminfo .tabs-tit li').eq(1).click()
        }

    })();

})