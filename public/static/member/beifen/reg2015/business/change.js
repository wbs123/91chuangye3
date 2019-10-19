//元素获取焦点
function changeElementStyle(id,inputClass,spanClass,spanHtml)
{
    //修改input输入框class
    changeInputClass(id,inputClass);
    //修改span提示层class
    changeSpanClass(id,spanClass);
    //修改span提示层html
    changeHtml(id,spanHtml);
}
//修改input输入框class
function changeInputClass(id,className)
{
    $("#"+id).attr('class',className);
}
//修改span提示层class
function changeSpanClass(id,className)
{
    //$("#"+id).next('.tipsBox').attr('class','tipsBox '+className);
    $("#"+id).parent().find('.tipsBox').attr('class','tipsBox '+className);
}
//修改span提示层html
function changeHtml(id,html)
{
	$("#"+id).parent().find(".tipsBox").html(html);
    // $("#"+id).next('.tipsBox').html(html);
}