/**
 * 展开回复
 */
$('.ziDong_replay .sp3').each(function () {
    $(this).click(function () {
        var obj = $(this).parents('li');
        obj.siblings('li').children('.replayContent').slideUp();
        obj.find('.replayContent').slideToggle();
    });
});

/**
 * 修改回复
 * @param obj
 */
function update_reply(obj)
{
    var ask_id = $(obj).parents('li').data('id'),
        content = $(obj).parent().siblings('.replayCon').find('textarea').val(),
        reply_id = $(obj).data('id');
    $.ajax({
        async:false,
        type:'get',
        url:'http://rating.jmw.com.cn/AddProjectAskReply.php',
        dataType:'jsonp',
        jsonp:'callback',
        data:{content:content,id:reply_id,ask_id:ask_id},
        success:function(result){
            if (result === 'false')
            {
                window.location.reload();
            }else {
                result = parseInt(result);
                if (result === 1) {
                    tips(1, '', '', '');
                } else if (result === -1) {
                    tips(2, '', '', '');
                } else if (result === -2) {
                    tips(2, '', '', '');
                } else if (result === -3) {
                    window.location.href = "/login.php";
                } else {
                    tips(2, '', '', '');
                }
            }
        }
    });
}