/**
 * 下拉显示每页显示条数
 */
$(".every_page_num").click(function(event){

    event.stopPropagation();

    $(".ps_data_num").toggle();
});

/**
 * 每页显示条数选择
 */
$(".every_page_num .item").each(function() {
    $(this).click(function() {
        var tex = $(this).text();
        $(this).parent().siblings().text(tex);
        window.location.href = $(this).data('url');
    })
});

/**
 * 计算字符
 * @param obj
 */
function count_chart(obj)
{
    var content = $(obj).val(),
        length = 300 - content.length;

    $(obj).parent().siblings('.submit_hf').find('span').html('还能输入'+ length +'个字符');
}

$(document).click(function(){
    $(".ps_data_num").hide();
});