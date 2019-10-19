/**
 *批量删除按钮特效
 */
$(".btn_page_del").hover(function(){
	$(this).css("background","#4999d2");
	$(this).css("color","#fff");
	$(".is_del").css("display","none");
	$(".del").css("display","block");
},function(){
	$(this).css("background","#fff");
	$(this).css("color","#4999d2");
	$(".is_del").css("display","block");
	$(".del").css("display","none");
});

/**
 * 按钮操作
 */
$(".b_t_n").each(function(index){
	$(this).click(function(){
        var service = $(this).data('service');
		if((index + 1) % 3 === 1)
		{
			if($(this).hasClass('chose1'))
			{
                $(this).removeClass("chose1");

                $(this).parent('.three_btn').siblings(".infor_user").slideUp();
			}else{
                if (parseInt(service) === 0)
                {
                    tips(2,0,'','');
                }else{
                    $(this).addClass("chose1");

                    $(this).parent('.three_btn').siblings(".infor_user").slideDown();
                }

                read($(this));
			}
		}
		if((index + 1) % 3 === 2)
		{
			if($(this).hasClass('chose2'))
			{
                $(this).removeClass("chose2");

                $(this).parent('.three_btn').siblings(".write_text").slideUp();

                $('.neirong_xiala').hide();

			}else{
                if (parseInt(service) === 0)
                {
                    tips(2,0,'','');
                }else{
                    $(this).addClass("chose2");

                    $(this).parent('.three_btn').siblings(".write_text").slideDown();
                }
			}
		}
		if((index + 1) % 3 === 0){
			if($(this).hasClass('chose3'))
			{
                $(this).removeClass("chose3");
			}else{
                if (parseInt(service) === 0)
                {
                    tips(2,0,'','');
                }else{

                    $(this).addClass("chose3");

                    tips(1,$(this).parents('.item_message').data('id'),$(this).data('url'),"删除留言会影响到您的综合排名，<br>请慎重操作！");
                }
			}
		}
	});
});

/**
 * 显示回复框
 */
$(".write_ly input").click(function(event){

    event.stopPropagation();

	$(".neirong_xiala").toggle();
});

/**
 *默认回复内容下拉列表选择
 */
$(".neirong_xiala .xiala_item").each(function(){
	$(this).click(function(){
		var content= $(this).text();
		$(this).parent().siblings(":text").val(content);
        $(this).parent().hide();
        length = 300 - content.length;
        $(this).parents('.write_ly').siblings('.tishi_numchart1').find('span').html('还能输入'+ length +'个字符');
	})
});

/**
 * 修改查看留言的为已读
 */
function read(obj)
{
    var service = obj.data('service'),
        id = obj.parents('.item_message').data('id'),
        url = obj.data('url'),
        user_id = obj.parents('.item_message').data('user');

    if (parseInt(service) === 1)
    {
        if (id !== '0')
        {
            $.ajax({
                url: url,
                type: 'post',
                data: {message_id: id, user_id: user_id}
            });
        }
    }else{
        tips(2,0,'','');
    }
}

/**
 * 确定按钮
 * @param obj
 * @param type 1: 删除 2：批量回复
 */
function confirm_button(obj,type)
{
    var data = '',
        id = $(obj).data('id'),
        url = $(obj).data('url');
    if (id !== '0')
    {
        if (type === 1)
        {
            data = {message_id: id};
        } else {
            var content = $(obj).parent().siblings('textarea').val(),
                user_id = $(obj).data('user');
            data = {message_id: id, message_user_id: user_id, content: content};
        }
    }

    if (data !== '')
    {
        $(obj).parents('.tc').hide();
        $('.cover').hide();

        $.ajax({
            url: url,
            type: 'post',
            data: data,
            success: function (result) {
                if (result === 'false')
                {
                    window.location.reload();
                }else {
                    result = parseInt(result);
                    if (type === 1) {
                        if (result === 1) {
                            tips(3, 0, '', '');
                        } else {
                            tips(4, 0, '', '操作失败');
                        }
                    } else {
                        if (result > 0) {
                            tips(3, 0, '', '');
                        } else if (result === -3) {
                            tips(4, 0, '', '已回复或已删除');
                        } else {
                            tips(4, 0, '', '操作失败');
                        }
                    }
                }
            }
        });
    }
}

/**
 * 计算输入框内容
 * @param obj
 */
function count_chart(obj)
{
    var content = $(obj).val(),
        length = 300 - content.length;

    $(obj).parent().siblings('.tishi_numchart1').find('span').html('还能输入'+ length +'个字符');
}

/**
 * 添加回复
 * @param obj
 */
function add_reply(obj)
{
    var id = $(obj).parents('.item_message').data('id'),
        user_id = $(obj).parents('.item_message').data('user'),
        content = $(obj).parent().siblings('.write_ly').find(':text').val(),
        target_id = $(obj).parents('.item_message').data('tag'),
        url = $(obj).data('url'),
        service = $(obj).data('service');
    if (parseInt(service) === 1)
    {
        if (content !== '')
        {
            $.ajax({
                url: url,
                type: 'post',
                data: {message_id: id, message_user_id: user_id, content: content,targetId: target_id},
                success: function (result) {
                    if (result !== 'no')
                    {
                        tips(3,0,'','');
                    }else{
                        tips(4,0,'','操作失败');
                    }
                }
            });
        }else{
            tips(4,0,'','请填写回复内容');
        }
    }else{
        tips(2,0,'','');
    }
}

/**
 * 批量回复
 * @param service
 */
function reply_all(service)
{
    if (service === '1')
    {
        var ids = '',user_ids = '';

        $('.message_list .checkBox').each(function () {
            if ($(this).hasClass('ischeck'))
            {
                ids += $(this).parents('.item_message').data('id') + ',';
                user_ids += $(this).parents('.item_message').data('user') + ',';
            }
        });

        ids = ids.substring(0,ids.length - 1);
        user_ids = user_ids.substring(0,user_ids.length - 1);

        if (ids === '')
        {
            tips(6,0,'','');
        } else {
            tips(5,ids+'|'+user_ids,'','');
        }
    } else {
        tips(2,0,'','');
    }
}

/**
 * 批量删除
 * @param service
 * @param url
 */
function delete_all(service,url)
{
    if (service === '1')
    {
        var ids = '';

        $('.message_list .checkBox').each(function () {
            if ($(this).hasClass('ischeck'))
            {
                ids += $(this).parents('.item_message').data('id') + ',';
            }
        });

        ids = ids.substring(0,ids.length - 1);

        if (ids === '')
        {
            tips(6,0,'','');
        }else{
            tips(1,ids,url,"删除留言会影响到您的综合排名，<br>请慎重操作！");
        }
    } else {
        tips(2,0,'','');
    }
}

/**
 * 导出留言
 */
function export_form(url,service)
{
    if (service !== '')
    {
        window.open(url,'_blank');
    }else{
        tips(2,0,'','');
    }
}