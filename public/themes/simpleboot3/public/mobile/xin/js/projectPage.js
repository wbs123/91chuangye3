$(function () {

    function classificationAddH1() {
        var html = $(".classification .tab ul li").eq(0).find('span').html()
        $(".classification .tab ul li").eq(0).find('span').html('<h1>' + html + '</h1>')
    }
    function footernavRemoveH1() {
        var html2 = $('.footer ul li').eq(1).find('h1').html()
        $('.footer ul li').eq(1).html(html2)
    }
    function listRemoveH2() {
        $('.projectPage .contentList ul li').each(function (index, item) {
            if ($(item).find('h2')) {
                var html = $(item).find('h2').html()
                $(item).find('.title').html(html)
            }
        })
    }
    var classbolleanli1 = $('.classification .tab ul li').eq(0).hasClass('active')
    var classbolleanli2 = $('.classification .tab ul li').eq(1).hasClass('active')
    var classbolleanli3 = $('.classification .tab ul li').eq(2).hasClass('active')
    if (classbolleanli1 && !classbolleanli2 && !classbolleanli3) {
        footernavRemoveH1()
        classificationAddH1()
    } else if ((classbolleanli2 || classbolleanli3)) {
        listRemoveH2()
        footernavRemoveH1()
    }



    // 判断初始化全部分类左侧加载
    function initLeftAjax() {
        var boolean = false
        $('.classification .content .contentTab .swiper-container .swiper-slide').each(function (index, item) {
            if ($(item).hasClass('active')) {
                boolean = true
            }
        })
        if (!boolean) {
            $('.classification .content .contentTab .swiper-container .swiper-slide').eq(0).addClass('active')
        }
    }
    initLeftAjax()

    // 项目库列表计算高度
    // setTimeout(function(){
    //     $('.projectPage .contentList ul li').each(function (index, item) {
    //         if (index == 0) {
    //             var liLength = $('.projectPage .contentList ul li').length
    //             var liHeight = $(item).outerHeight(true)
    //             console.log(liHeight)
    //             var pongeHeight = liLength * liHeight
    //             $('.projectPage .contentList ul').height(liHeight * 10)
    //             $('.projectPage .contentList .more-btn').click(function () {
    //                 $('.projectPage .contentList ul').height(pongeHeight)
    //                 $(this).hide()
    //             })
    //         }
    //     })
    // },20)

    $("img.lazy").lazyload();

    function pagelistShow() {
        $('.classification .btn').show()
        $('.mc').stop().fadeIn()
        $('.mc').addClass('active')
        $('.header').css('z-index', 4)
        $('.classification').addClass('active')
        $('body').css({
            "overflow-x": "hidden",
            "overflow-y": "hidden"
        });
    }
    function pagelistHide() {
        $('.ponglist').stop().slideUp('normal', function () {
            $('.mc').stop().fadeOut()
            $('.mc').removeClass('active')
            $('.header').css('z-index', 3)
            // $('.classification').removeClass('active')
            $('body').css({
                "overflow-x": "auto",
                "overflow-y": "auto"
            });
            $('.classification .btn').hide()
            $('.classification .ponglist>div').hide()
            // $('.classification .tab ul li').removeClass('active')
            // $('.classification .content .right ul li').removeClass('active')
        })
    }

    function init() {
        $('.classification .content .contentTab .swiper-container .swiper-slide').eq(0).addClass('active').siblings().removeClass('active')
    }
    // init()

    // 删除页面的所有class为active的列表
    function removeLiActive() {
        // $('.classification .investment ul li, .classification .region ul li,.classification .content .right ul li').removeClass('active')
    }

    // 点击分类显示
    $('.classification .tab ul li').click(function () {
        var index = $(this).index()
        // $(this).addClass('active').siblings().removeClass('active')
        pagelistShow()
        $('.ponglist').stop().slideDown()
        if (index == 0) {
            if ($('.classification .content').is(':hidden')) {
                $('.classification .content').show().siblings().hide()
                var indexOP = 0
                $('.classification .content .contentTab .swiper-container .swiper-slide').each(function (index, item) {
                    if ($(item).hasClass('active')) {
                        indexOP = index
                    }
                })
                if (BTscrollArr[indexOP]) {
                    BTscrollArr[indexOP].refresh()
                }
                swiperLeftTab.update()
                removeLiActive()
            }
        } else if (index == 1) {
            if ($('.classification .investment').is(':hidden')) {
                $('.classification .investment').show().siblings().hide()
                investmentBSroll.refresh()
                removeLiActive()
            }
        } else if (index == 2) {
            if ($('.classification .region').is(':hidden')) {
                $('.classification .region').show().siblings().hide()
                regionBSroll.refresh()
                removeLiActive()
            }
        }
    })

    // // 点击确认按钮
    // $('.classification .btn .enter').click(function () {
    //     $('.classification .investment ul li, .classification .region ul li, .classification .content .right ul li').each(function (index, item) {
    //         if ($(item).hasClass('active')) {
    //             location.href = $(item).find('a').attr('href')
    //         }
    //     })
    // })

    // // 点击取消按钮隐藏
    // $('.classification .btn .esc').click(function () {
    //     pagelistHide()
    // })

    // 初始化下拉分类btscroll初始化
    var arr = []
    if ($('.right .block').length) {
        $('.right .block').each(function (index, item) {
            var scroll = new BScroll($(item).find('.wrapper').get(0), {
                scrollbar: {
                    fade: true
                },
                click: true,
            })
            arr.push(scroll)
        })
    }
    window.BTscrollArr = arr


    // 初始化投资金额,加盟地区的上下移动
    if ($('.investment').length) {
        window.investmentBSroll = new BScroll('.investment', {
            scrollbar: {
                fade: true
            },
            click: true,
        })
    }

    if ($('.region').length) {
        window.regionBSroll = new BScroll('.region', {
            scrollbar: {
                fade: true
            },
            click: true,
        })
    }










    $('.classification .content .right ul li a').click(function () {
        pagelistHide()
    })
    $('.classification .investment ul li a').click(function () {
        pagelistHide()
    })
    $('.classification .region ul li a').click(function () {
        pagelistHide()
    })


    // 点击全部分类给当前增加对号样式
    $('.classification .content .right ul li').click(function () {
        // $('.classification .content .right ul li').removeClass('active')
        // $(this).addClass('active')

    })
    // 点击投资金额给当前增加样式
    $('.classification .investment ul li').click(function () {
        $(this).addClass('active').siblings().removeClass('active')
    })
    // 点击加盟地区给当前增加样式
    $('.classification .region ul li').click(function () {
        $(this).addClass('active').siblings().removeClass('active')
    })


    // 点击左侧tab切换class
    $('.classification .content .contentTab .swiper-container .swiper-slide').click(function () {
        $(this).addClass('active').siblings().removeClass('active')
        var index = $(this).index()
        $('.classification .content .right .block').eq(index).stop().fadeIn().siblings().stop().fadeOut()
        if (window.BTscrollArr[index]) {
            window.BTscrollArr[index].refresh()
        }
    })

    // 分类左侧tab选项
    var swiper = new Swiper('.classification .contentTab .swiper-container', {
        direction: 'vertical',
        freeMode: true,
        roundLengths: true,
        slidesPerView: 'auto'
    });
    setTimeout(function () {
        swiper.update()
    }, 500)
    window.swiperLeftTab = swiper

    // 最新资讯tab选项卡切换
    var swiper4 = new Swiper('.news .swiper-container', {
        on: {
            slideChangeTransitionStart: function () {
                $('.news .commonTabTages ul li').eq(this.activeIndex).click()
            }
        },
        lazy: {
            preloaderClass: 'yxy-lazy-preloader',
        }
    });
    // 最新咨询tab切换
    $('.news .commonTabTages ul li').click(function () {
        $(this).addClass('active').siblings().removeClass('active')
        var index = $(this).index()
        swiper4.slideTo(index)
    })

    // 点击蒙层收起
    $('.mc').click(function () {
        pagelistHide()
    })

})