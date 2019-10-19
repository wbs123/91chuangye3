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
 * 取消按钮
 * @param obj
 */
function cancel_button(obj)
{
    $('.tc').hide();
    $('.cover').hide();
    $(obj).parents('.tc').find('.btn_item').eq(0).data('id',0);
    $('.b_t_n').each(function (index) {
        $(this).removeClass('chose1');
    });
}

/**
 * 显示弹层
 * @param type 1：操作成功 2：操作失败 3：确认删除层 4：图片提示层
 * @param id
 * @param url
 * @param content
 */
function tips(type,id,url,content)
{
    var obj = $('.tc');
    if (type === 1)
    {
        obj.hide().eq(0).show();
        $('.cover').show();
    }else if (type === 2){
        obj.hide().eq(1).show();
        $('.cover').show();
    }else if (type === 3){
        obj.hide().eq(2).show();
        $('.cover').show();
        obj.eq(2).find('.btn_item').eq(0).data('id',id);
        var old = obj.eq(2).find('.btn_item').eq(0).data('url');
        if (url !== '')
        {
            obj.eq(2).find('.btn_item').eq(0).data('url',url);
        }else{
            obj.eq(2).find('.btn_item').eq(0).data('url',old);
        }
    }else if (type === 4){
        obj.eq(3).find('p').html('<img src="http://image1.jmw.com.cn/comp_v1/img/makemiss.png"/>' + content);
        obj.hide().eq(3).show();
        $('.cover').show();
    }else if (type === 5){
		obj.hide().eq(4).show();
        $('.cover').show();
	}
}