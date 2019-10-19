$(function(){

    function rmeoveClass(){
        // $('.classification .content .right ul li').removeClass('active')
        // $('.classification .investment ul li').removeClass('active')
        // $('.classification .region ul li').removeClass('active')
    }

    $('.classification .content .right ul li a').click(function(){
        $('.classification .content .right ul li a span').removeClass('active')
        $(this).find('span').addClass('active')
        var title = $(this).find('span').html()
        rmeoveClass()
        $(this).parents('li').addClass('active')
        $('.classification .tab ul li').eq(0).find('span').html(title)
        $('.classification .tab ul li').eq(0).find('span').addClass('active')
        return false;
    })

    $('.classification .investment ul li a').click(function(){
        $('.classification .investment ul li a').removeClass('active')
        $(this).addClass('active')
        var title = $(this).html()
        rmeoveClass()
        $(this).parents('li').addClass('active')
        $('.classification .tab ul li').eq(1).find('span').html(title)
        $('.classification .tab ul li').eq(1).find('span').addClass('active')
        return false;
    })

    $('.classification .region ul li a').click(function(){
        $('.classification .investment ul li a').removeClass('active')
        $(this).addClass('active')
        var title = $(this).html()
        rmeoveClass()
        $(this).parents('li').addClass('active')
        $('.classification .tab ul li').eq(2).find('span').html(title)
        $('.classification .tab ul li').eq(2).find('span').addClass('active')
        return false;
    })

    $('.classification .content .right ul li').click(function(){
        console.log(123)
        $(this).addClass('active')
    })  
    
    
})