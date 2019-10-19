/**
 * 按钮操作
 */
$(".b_t_n").each(function(index){
    $(this).click(function(){
        if ((index + 1) % 2 === 1)
        {
            if($(this).hasClass('chose1'))
            {
                $(this).removeClass("chose1");
            }else{
                $(this).addClass("chose1");
            }

            $(this).parent('.three_btn').siblings(".infor_user").slideToggle();
        }
        if ((index + 1) % 2 === 0)
        {
            if($(this).hasClass('chose2'))
            {
                $(this).removeClass("chose2");
            }else{
                $(this).addClass("chose2");

                tips(1,$(this).parents('.item_message').data('id'),$(this).data('url'),'','');
            }
        }
    });
});

$('.industry .item').each(function () {
    $(this).click(function () {
        var id = $(this).data('id'),
            text = $(this).text();
        $(this).parent().siblings('span').data('id',id).text(text);
    });
});

function confirm_button(obj,type)
{
    var id = $(obj).data('id'),
        url = $(obj).data('url');

    $.ajax({
        url: url,
        type: 'post',
        data: {id: id},
        success: function (result) {
            if (result === 'false')
            {
                window.location.reload();
            }else {
                result = parseInt(result);
                if (result === 1) {
                    tips(3, 0, '', '');
                } else if (result === -1) {
                    tips(2, 0, '', '');
                } else {
                    tips(4, 0, '', '操作失败');
                }
            }
        }
    });
}

/**
 * 确认表单提交
 * @returns {boolean}
 */
function check_form()
{
    var industry = $('.industry').siblings('span').data('id');

    $('input[name="industry"]').val(industry);

    return true;
}

/**
 * 导出留言
 */
function export_form(url)
{
    window.open(url,'_blank');
}