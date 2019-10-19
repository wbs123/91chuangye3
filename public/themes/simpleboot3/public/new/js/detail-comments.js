$(function(){

    // 评论右侧回复点击出现回复框
    $('.commentsItem').find('.rightItem a').click(function(){
        var commentInput = $(this).parents('.commentsItem').find('.commentTxt .commentInput')
        if(commentInput.is(':hidden')){
            commentInput.show()
        }else{
            commentInput.hide()
        }
    })

    // 点击回复如果没输入内容,提示. 反之跳转登录
    $('.commentList .commentarea .submitBar a').click(function(){
        if($('.commentarea').find('textarea').val().trim() == ''){
            layer.msg('请输入您想了解的内容!');
            return false;
        }
    })

    // 点击回复如果没输入内容,提示. 反之跳转登录
    $('.commentsItem').find('.commentInput .comBtn').click(function(){
        if($(this).siblings('.comTxt').val().trim() == ''){
            layer.msg('请输入您想了解的内容!');
            return false;
        }
    })

})