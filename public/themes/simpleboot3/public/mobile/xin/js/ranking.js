$(function(){
    $("img.lazy").lazyload({threshold:400});
})

$(function () {

    // 大分类
    var liwidth = $('.big-classification ul li').outerHeight(true)
    var lilength = $('.big-classification ul li').length

    // var originally = (lilength / 5) * liwidth
    var originally = Math.ceil((lilength / 5)) * liwidth

    var init = liwidth * 2

    $('.big-classification ul').height(init)
    $('.big-classification .commonlistMoreNew .btn-xc').click(function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active')
            $('.big-classification ul').height(init)
        } else {
            $(this).addClass('active')
            $('.big-classification ul').height(originally)
        }
    })

})

$(function () {

    // 其他的更多
    $('.multiple').each(function (index, item) {
        var li = $(this).find('ul li')
        var lilength = li.length
        var btn = $(this).find('.btn-xc')
        if(lilength > 3){
            var ul = $(this).find('ul')
            var liwidth = li.outerHeight(true)
            var originally = liwidth*lilength
            var init = liwidth * 3
            $(this).find('ul').height(init)
            btn.click(function(){
                if ($(this).hasClass('active')) {
                    $(this).removeClass('active')
                    ul.height(init)
                } else {
                    $(this).addClass('active')
                    ul.height(originally)
                }
            })
        }else{
            btn.parents('.commonlistMoreNew').hide()
        }
    })

})