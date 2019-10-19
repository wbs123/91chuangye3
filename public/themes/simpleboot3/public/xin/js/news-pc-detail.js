$(function () {
    // 导航点击收与缩
    var navFindUl = $('.h-nav .side-nav').find('.nav_1')
    $('.h-nav .side-nav').click(function () {
        if (navFindUl.is(':hidden')) {
            navFindUl.stop().slideDown()
        } else {
            navFindUl.stop().slideUp()
        }
    })
})