/**
 * 展开回复
 */
$('.question_sec .sp3').each(function () {
    $(this).click(function () {
        var obj = $(this).parents('li');
        obj.siblings('li').children('.replayContent').slideUp();
        obj.find('.replayContent').slideToggle();
    });
});

function add_reply(obj)
{
    var id = $(obj).parents('li').data('id'),
        content = $(obj).parent().siblings('.replayCon').find('textarea').val();

    $.ajax({
        type:'get',
        url:'http://rating.jmw.com.cn/AddProjectAskReply.php',
        dataType:'jsonp',
        jsonp:'callback',
        data:{content: content, ask_id: id},
        success:function(result){
            if (result === 'false')
            {
                window.location.reload();
            }else{
                result = parseInt(result);
                if(result === 1)
                {
                    tips(1,'','','');
                }else if(result === -1){
                    tips(2,'','','');
                }else if(result === -2){
                    tips(2,'','','');
                }else if(result === -3){
                    tips(2,'','','');
                }
            }
        }
    });
}