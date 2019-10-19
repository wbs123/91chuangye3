$(function () {

    function classDgSwiperSlideFun(){
        if($('.section-one .one-nav .classification .swiper-slide').length%10 != 0){
            $('.section-one .one-nav .classification .swiper-wrapper').append('<div class="swiper-slide"></div>')
            classDgSwiperSlideFun()
        }else{
            return true
        }
    }
    classDgSwiperSlideFun()

    // 行业分类轮播
    // var swiper7 = new Swiper('.section-one .one-nav .classification .swiper-container', {
    //     slidesPerColumn: 5,
    //     slidesPerView: 2,
    //     slidesPerGroup: 2,
    //     autoplay: {
    //         delay: 3000,
    //         disableOnInteraction: false
    //     }
    // })
    // $('.section-one .one-nav .classification .swiper-container').mouseover(function(){
    //     swiper7.autoplay.stop()
    // }).mouseout(function(){
    //     swiper7.autoplay.start()
    // })

    // 头部点击隐藏
    $('.h-nav .meau_but').click(function(){
        if($(this).siblings('.nav_1').is(':hidden')){
            $(this).siblings('.nav_1').show()
        }else{
            $(this).siblings('.nav_1').hide()
        }
    })

    // 图片懒加载
    $("img.lazy").lazyload({
        threshold: 200,
        skip_invisible: false
    });

    // 头部banner轮播图
    var swiper = new Swiper('.section-one .one-banner .swiper-container', {
        pagination: {
            el: '.section-one .one-banner .swiper-pagination',

            clickable: true
        },
    });

    // 三个模块一排的tab选项卡
    $('.three-block .style-block').each(function (index, item) {
        var swiperContainer = $(item).find('.swiper-container')
        var swiperChildren = new Swiper(swiperContainer, {
            on: {
                slideChangeTransitionStart: function () {
                    $(item).find('.tab ul li').eq(this.activeIndex).addClass('active').siblings().removeClass('active')
                }
            }
        })
        $(item).find('.tab ul li').mouseover(function () {
            var index = $(this).index()
            swiperChildren.slideTo(index)
            $(this).addClass('active').siblings().removeClass('active')
        })
        $(item).find('.tab ul li').eq(0).mouseover()
    })

    // 热门tab选项卡
    var swiper2 = new Swiper('.hotTabBlock .swiper-container', {
        on: {
            slideChangeTransitionStart: function () {
                $('.hotTabBlock .tab ul li').eq(this.activeIndex).addClass('active').siblings().removeClass('active')
            }
        }
    })
    $('.hotTabBlock .tab ul li').mouseover(function () {
        var index = $(this).index()
        swiper2.slideTo(index)
        $(this).addClass('active').siblings().removeClass('active')
    })
    $('.hotTabBlock .tab ul li').eq(0).mouseover()

    // 左侧滚动条
    var sectiononeLength = ($('.section-one .one-nav .classification ul .swiper-li').length/2)*28
    if(sectiononeLength > 321){
        $('.section-one .one-nav .classification').css({
            'overflow-y':'scroll'
        })
        $('.section-one .one-nav .classification .swiper-content .imglist').hide()
    }

})