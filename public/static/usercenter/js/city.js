$(".cs_city").each(function(index) {
    $(this).click(function(event) {

        event.stopPropagation();

        if ($(this).hasClass('state'))
        {
            var type = $(this).siblings('.replaySelect').data('id');

            if (parseInt(type) === 1)
            {
                $(this).children('.xiala_select').eq(0).toggle();
                $(this).children('.xiala_select').eq(1).hide();
            }else if(parseInt(type) === 2){
                $(this).children('.xiala_select').eq(0).hide();
                $(this).children('.xiala_select').eq(1).toggle();
            }
        }else{
            $(this).children(".xiala_select").toggle();
        }

        $(this).siblings().children(".xiala_select").hide();
    });
});

/**
 * 省市区三级联动
 * @param obj
 * @param type
 */
function getCity(obj,type)
{
    var pid = $(obj).data('id');
    if (pid !== '')
    {
        $.ajax({
            url: 'http://comp.jmw.com.cn/v1/message_list/getCity.php',
            type: 'post',
            data: {parent_id: pid, type: type},
            success: function (result) {
                if (type === 0)
                {
                    $(obj).parent().siblings('span').text($(obj).text()).data('id',pid);
                    $('.city').html(result).siblings('span').text('不限制').data('id','');
                    $('.county').html('<div class="item" data-id="">不限制</div>').siblings('span').text('不限制').data('id','');
                    var item = $('#province'),
                        position = item.data('type');

                    if (parseInt(position) === 0)
                    {
                        item.val(pid);
                    }else{
                        item.val($(obj).text());
                    }
                }else if (type === 1){
                    $(obj).parent().siblings('span').text($(obj).text()).data('id',pid);
                    $('.county').html(result).siblings('span').text('不限制').data('id','');
                    var item = $('#city'),
                        position = item.data('type');

                    if (parseInt(position) === 0)
                    {
                        item.val(pid);
                    }else{
                        item.val($(obj).text());
                    }
                }else if (type === 2){
                    $(obj).parent().siblings('span').text($(obj).text()).data('id',pid);
                    var item = $('#county'),
                        position = item.data('type');

                    if (parseInt(position) === 0)
                    {
                        item.val(pid);
                    }else{
                        item.val($(obj).text());
                        item.next().find('.earr_tishi').hide();
                        item.next().find('.ys_tishi').show();
                    }
                }
            }
        });
    }else{
        $(obj).parent().siblings('span').text('不限制').data('id','');
        if (type === 0)
        {
            $('#province').val('');
            $('#city').val('');
            $('#county').val('');
            $('.city').html('<div class="item" data-id="">不限制</div>').siblings('span').data('id','').html('不限制');
            $('.county').html('<div class="item" data-id="">不限制</div>').siblings('span').data('id','').html('不限制');
        }else if (type === 1){
            $('#city').val('');
            $('#county').val('');
            $('.county').html('<div class="item" data-id="">不限制</div>').siblings('span').data('id','').html('不限制');
        }else{
            $('#county').val('');
        }

        var tips = $('.cs_city:last');
        tips.siblings('.tishi_img').find('.earr_tishi').show();
        tips.siblings('.tishi_img').find('.ys_tishi').hide();
    }
}