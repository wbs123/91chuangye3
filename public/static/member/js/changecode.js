//刷新验证码
function changeCode(obj)
{
	
    obj.attr("src","/identifying_code.php?rand="+Math.random());
}