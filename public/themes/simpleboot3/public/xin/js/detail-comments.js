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
        layer.open({
			type: 1,
			area: '900px',
			title: false,
			closeBtn: 1,
			shade: 0.5,
			shadeClose: true,
			content: $('#xm-mesg'),
			end: function () {
				$("input[inputEl='input']").val(''); //清空输入框内容
			}
		});
    })

    // 点击回复如果没输入内容,提示. 反之跳转登录
    $('.commentsItem').find('.commentInput .comBtn').click(function(){
		layer.open({
			type: 1,
			area: '900px',
			title: false,
			closeBtn: 1,
			shade: 0.5,
			shadeClose: true,
			content: $('#xm-mesg'),
			end: function () {
				$("input[inputEl='input']").val(''); //清空输入框内容
			}
		});
		/*
        if($(this).siblings('.comTxt').val().trim() == ''){
            
        }
		*/
    })

})