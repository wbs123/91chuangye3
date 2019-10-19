/**
 * 显示弹层
 * @param type 1：确定删除 2：服务尚未开通 3：操作成功 4：操作失败 5：回复
 * @param id
 * @param url
 * @param content
 */
function tips(type,id,url,content)
{
    var obj = $('.tc');

    if (type === 1)
    {
        obj.hide();
        $('.cover').show();
        obj.eq(0).show();
        obj.eq(0).find('.btn_item').eq(0).data('id',id);
        var old = obj.eq(0).find('.btn_item').eq(0).data('url');
        if (url !== '')
        {
            obj.eq(0).find('.btn_item').eq(0).data('url',url);
        }else{
            obj.eq(0).find('.btn_item').eq(0).data('url',old);
        }

        if (content !== '')
        {
            obj.eq(0).find('p').html(content);
        }else{
            obj.eq(0).find('p').html('确定删除吗？');
        }
    }else if (type === 2){
        obj.hide();
        $('.cover').show();
        obj.eq(1).show();
    }else if (type === 3){
        obj.hide();
        $('.cover').show();
        obj.eq(2).show();
    }else if (type === 4){
        obj.hide();
        $('.cover').show();
        obj.eq(3).show();
        if (content !== '')
        {
            obj.eq(3).find('p').html('<img src="http://image1.jmw.com.cn/comp_v1/img/makemiss.png"/>' + content);
        }else{
            obj.eq(3).find('p').html('<img src="http://image1.jmw.com.cn/comp_v1/img/makemiss.png"/>操作失败');
        }
    }else if (type === 5){
        obj.hide();
        $('.cover').show();
        obj.eq(4).show();

        var arr = id.split('|');

        obj.eq(4).find('.btn_item').eq(0).data('id',arr[0]);
        obj.eq(4).find('.btn_item').eq(0).data('user',arr[1]);
    }else if (type === 6) {
        obj.hide();
        $('.cover').show();
        obj.eq(5).show();
    }
}

/**
 * 时间范围
 */
if ($('#dd').length > 0)
{
    $('#dd').calendar({
        trigger: '#dt',
        zIndex: 999,
        format: 'yyyy-mm-dd'
    });
}
if ($('#dd1').length > 0)
{
    $('#dd1').calendar({
        trigger: '#dt1',
        zIndex: 999,
        format: 'yyyy-mm-dd'
    });
}

//手机号下拉框
$('.telephoneSelect').click(function (event) {

    event.stopPropagation();

    $(this).siblings('.select_phone').toggle();
});

$('.select_phone p').click(function(){
    var txt = $(this).text(),
        id = $(this).data('id');
    $(this).parent('.select_phone').siblings('.telephoneSelect').data('id',id).html(txt);
    $(this).parent('.select_phone').hide();
});

/**
 * 回复状态选择
 */
$(".reseave .item").each(function() {
    $(this).click(function() {
        var text = $(this).text(),id = $(this).data('id');
        $(this).parent().siblings('span').data('id',$(this).data('id')).text(text);
    });
});

/**
 * 下拉显示每页显示条数
 */
$(".every_page_num").click(function(event){

    event.stopPropagation();

    $(".ps_data_num").toggle();
});

/**
 *每页显示条数选择
 */
$(".every_page_num .item").each(function() {
    $(this).click(function() {
        var tex = $(this).text();
        $(this).parent().siblings().text(tex);
        window.location.href = $(this).data('url');
    })
});

/**
 * 复选框选择
 */
$(".message_list .checkBox").each(function () {
    $(this).click(function () {
        if ($(this).hasClass('ischeck'))
        {
            $(this).removeClass('ischeck');
        }else{
            $(this).addClass('ischeck');
        }
    });
});

/**
 * 批量选择
 */
$('.page_num .checkBox').click(function () {
    if ($(this).hasClass('ischeck'))
    {
        $('.message_list .checkBox').removeClass('ischeck');

        $(this).removeClass('ischeck');
    }else{
        $('.message_list .checkBox').each(function () {
            if (!$(this).hasClass('ischeck'))
            {
                $(this).addClass('ischeck');
            }
        });

        $(this).addClass('ischeck');
    }
});

/**
 * 标签提示
 */
$(".tanhao").each(function(){
    $(this).hover(function(){
        $(this).children('.xz_tishi').show();
        $(this).children('.tishi_txt').show();
    },function(){
        $(this).children('.xz_tishi').hide();
        $(this).children('.tishi_txt').hide();
    });
});

/**
 * 取消按钮
 * @param obj
 */
function cancel_button(obj)
{
    $('.tc').hide();
    $('.cover').hide();
    $(obj).parents('.tc').find('.btn_item').eq(0).data('id',0);
    $('.three_btn').each(function () {
        var obj = $(this).find('.b_t_n:last');
        if (obj.hasClass('chose3'))
        {
            obj.removeClass('chose3');
        }
        else if (obj.hasClass('chose2'))
        {
            obj.removeClass('chose2');
        }
    });
}

/**
 * 关闭弹层
 * @param type
 */
function close_button(type)
{
    if (parseInt(type) === 1)
    {
        window.location.reload();
    }else{
        $('.cover').hide();
        $('.tc').hide();
    }
}

/**
 *批量回复/恢复按钮特效
 */
$(".btn_page_hf").hover(function(){
    $(this).css("background","#4999d2");
    $(this).css("color","#fff");
    $(".is_hf").css("display","none");
    $(".hf").css("display","block");
},function(){
    $(this).css("background","#fff");
    $(this).css("color","#4999d2");
    $(".is_hf").css("display","block");
    $(".hf").css("display","none");
});

/**
 * 验证表单
 */
function check_form()
{
    var is_reply = $('.reseave').siblings('span').data('id'),
        type = $('.telephoneSelect').data('id');

    $('input[name="type"]').val(type);
    $('input[name="is_reply"]').val(is_reply);

    return true;
}

/**
 * 删除回复
 * @param obj
 */
function delete_reply(obj)
{
    var id = $(obj).data('reply'),
        user_id = $(obj).parents('.item_message').data('user'),
        message_id = $(obj).parents('.item_message').data('id'),
        url = $(obj).data('url');
    $.ajax({
        url: url,
        type: 'post',
        data: {reply_id: id,user_id: user_id,message_id: message_id},
        success: function (result) {
            if (result === 'false')
            {
                window.location.reload();
            }else {
                if (parseInt(result) === 1) {
                    tips(3, 0, '', '');
                } else {
                    tips(4, 0, '', '操作失败');
                }
            }
        }
    });
}

$(document).click(function(){
    $(".xiala_select, .select_chl_list, .ps_data_num, .neirong_xiala, .select_phone").hide();
});