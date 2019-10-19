//表单JS
//全为空格
var pspace = eval(/^\s+$/);
//验证用户名正则
var puName = /\s+/;
var puName1 =/^[\u4e00-\u9fa5\da-zA-Z]{1,20}$/;
//密码验证正则
var puPwd = /^\d+[a-zA-Z][a-zA-Z\d]*|[a-zA-Z]+\d[a-zA-Z\d]*$/;

//联系人姓名正则
var prealName = /^([A-Za-z\u4e00-\u9fa5]+\s?)*[A-Za-z\u4e00-\u9fa5]{1,5}$/;

//手机正则
var pmobile = /^1[\d]{10}$/;

//品牌验证正则
var brand_zh = /^[\-\da-zA-z\u4e00-\u9fa5]{1,16}$/;

//验证长度 中文算2位 字母算1位
function checkLength(str,maxLength)
{
    var len = 0;
    for (var i = 0; i < str.length; i++) {
        var a = str.charAt(i);
        if (a.match(/[^\x00-\xff]/ig) != null) {
            len += 2;
        }
        else {
            len += 1;
        }
    }

    if (len > maxLength)
    {
        return false;
    }

    return true;
}

function showError(msg,type)
{
    removeError(type);

    $('.btn_nav').before('<p class="wrongPass clear" style="margin-bottom:10px;">'+msg+'</p>');

    if(type == 1)
    {
        $('#wizard').css('height','690px');
    }else{
        $('#wizard').css('height','690px');
    }
}
function removeError(type)
{
    $('.wrongPass').remove();
    if(type == 1)
    {
        $('#wizard').css('height','690px');
    }else{
        $('#wizard').css('height','690px');
    }
}
//公司名称
function checkCompanyName()
{
    var company_name = $.trim($('#company_name').val()),
        result;

    $('#company_name').val(company_name);

    if (company_name == '')
    {
        showError('请填写公司名称',1);
        result = false;
    }
    else
    {
        removeError(1);
        result = true;
    }

    if (!result)
    {
        $('#company_name').parent().css('border','1px solid #f45242');
    }else{
        $('#company_name').parent().css({'border':'1px solid #e3e4ee','border-bottom':'none'});
    }

    return result;
}
//品牌名称
function checkBrandName()
{
    var brand_name = $.trim($('#brand_name').val()),
        result;

    $('#brand_name').val(brand_name);

    if (brand_name == '' || brand_name.match(pspace))
    {
        showError('请填写品牌名称111',1);
        result = false;
    }
    else if (!checkLength(brand_name,16))
    {
        showError('品牌名称最多16位，字母、数字占1位，汉字占2位',1);
        result = false;
    }
    else if (!brand_name.match(brand_zh))
    {
        showError('品牌名称格式为：中文、英文、数字',1);
        result = false;
    }
    else
    {
        $.ajax({
            url:'/user/pinpai/',
            async :false,
            type:'post',
            data:"action=brand_zh&brand_name="+brand_name,
            success:function(data){
                if(data == 0)
                {
                    showError('此品牌名称已经被使用',1);
                    result = false;
                }
                else
                {
                    removeError(1);
                    result = true;
                }
            }
        });
    }

    if (!result)
    {
        $('#brand_name').parent().css('border','1px solid #f45242');
    }else{
        $('#brand_name').parent().css({'border':'1px solid #e3e4ee','border-bottom':'none'});
    }

    return result;
}

//行业
function checkIndustry(obj)
{
    var industry = $.trim(obj.val()),
        result;

    if (parseInt(industry) == 0 || industry == '' || industry.match(pspace))
    {
        showError('请选择行业',1);
        result = false;
    }else{
        removeError(1);
        result = true;
    }

    if (!result)
    {
        obj.parents('.selectIndustry').css({'border':'1px solid #f45242'});
    }else{
        obj.parents('.selectIndustry').css({'border':'1px solid #e3e4ee'});
    }

    return result;
}

//手机号
function checkTelephone()
{
    var telephone = $.trim($('#telephone').val()),
        result;

    $('#telephone').val(telephone);

    if (telephone == '')
    {
        showError('请填写手机号',1);
        result = false;
    }
    else if(!telephone.match(pmobile))
    {
        showError('手机号码不正确',1);
        result = false;
    }
    else
    {
        removeError(1);
        result = true;
    }

    if (!result)
    {
        $('#telephone').parent().css('border','1px solid #f45242');
    }else{
        $('#telephone').parent().css({'border':'1px solid #e3e4ee','border-bottom':'none'});
    }

    return result;
}

//联系人
function checkCombiner()
{
    var combiner = $.trim($('#combiner').val()),
        result;
    $('#combiner').val(combiner);
    if (combiner == '' || combiner.match(pspace))
    {
        showError('联系人不能为空',1);
        result = false;
    }
    else if (!combiner.match(prealName))
    {
        showError('联系人格式为：中文、英文',1);
        result = false;
    }
    else
    {
        removeError(1);
        result = true;
    }

    if (!result)
    {
        $('#combiner').parents('.brandNameBtm').css('border','1px solid #f45242');
    }else{
        $('#combiner').parents('.brandNameBtm').css({'border':'1px solid #e3e4ee'});
    }

    return result;
}

//图形验证码
// function checkImgCode()
// {
//     var imgCode2    = $.trim($("#code").val());
//     if(imgCode2.length<4){
//         layer.msg('图片验证码格式错误!',{time:1000});
//         return false;
//     }else{
//         $.ajax({
//             url:'/madmin/imgcode',
//             type:'post',
//             async :false,
//             data:{imgcode:imgCode2},
//             success:function (rs) {
//                 console.log(rs);
//                 if(rs == 'false')
//                 {
//                     showError('图形验证码错误',1);
//                     result = false;
//                 }
//                 else
//                 {
//                     removeError(1);
//                     result = true;
//                 }
//             }
//         });
//     }
//
//     if (!result)
//     {
//         $('#code').parents('.brandNameTop').css('border','1px solid #f45242');
//     }else{
//         $('#code').parents('.brandNameTop').css({'border':'1px solid #e3e4ee','border-bottom':'none'});
//     }
//
//     return result;
// }

//短信验证码
function checkSmsCode()
{
    var telephone = $.trim($('#telephone').val()),
        check_code = $.trim($('#check_code').val()),
        result;

    $('#check_code').val(check_code);

    if (check_code == '' || check_code.match(pspace))
    {
        showError('短信验证码不能为空',1);
        result = false;
    }
    else
    {
        $.ajax({
            url:'/user/regmsg/',
            async:false,
            type:'post',
            data:{mobel_num:telephone,tel_code:check_code},
            success:function(data){
                if(data == -1)
                {
                    showError('短信验证码错误',1);
                    result = false;
                }
                else
                {
                    removeError(1);
                    result = true;
                }
            }
        });
    }

    if (!result)
    {
        $('#check_code').parents('.brandNameBtm').css('border','1px solid #f45242');
    }else{
        $('#check_code').parents('.brandNameBtm').css({'border':'1px solid #e3e4ee'});
    }

    return result;
}

//账号
function checkUsername()
{
    var username = $.trim($('#username').val()),
        result;

    $('#username').val(username);

    if(username == '' || username.match(pspace))
    {
        showError('账户名称不能为空',2);
        result = false;
    }
    else if (!username.match(puName1))
    {
        showError('账户名称格式为：中文、英文、数字',2);
        result = false;
    }
    else if(username.match(puName))
    {
        showError('账户名称在4-20个位',2);
        result = false;
    }
    else
    {
        $.ajax({
            url:'/user/Accountnumber/',
            type:'post',
            async:false,
            data:"action=uName&username="+username,
            success:function(data){
                if(data == 0)
                {
                    showError('该账户名称已经被注册',2);
                    result = false;
                }
                else
                {
                    removeError(2);
                    result = true;
                }
            }
        });
    }

    if (!result)
    {
        $('#username').parent().css('border','1px solid #f45242');
    }else{
        $('#username').parent().css({'border':'1px solid #e3e4ee'});
    }

    return result;
}

//密码
function checkPassword()
{
    var password = $.trim($('#password').val()),
        username = $.trim($('#username').val()),
        result;

    $('#password').val(password);

    if (password == '' || password.match(pspace))
    {
        showError('密码不能为空',2);
        result = false;
    }
    else if (!password.match(puPwd))
    {
        showError('密码格式为：6-20位，英文、数字组合',2);
        result = false;
    }
    else if (password == username)
    {
        showError('账号名称和密码不能一致',2);
        result = false;
    }
    else
    {
        removeError(2);
        result = true;
    }

    if (!result)
    {
        $('#password').parent().css('border','1px solid #f45242');
    }else{
        $('#password').parent().css({'border':'1px solid #e3e4ee','border-bottom':'none'});
    }

    return result;
}

//确认验证码
function checkConfirmPassword()
{
    var password = $.trim($('#password').val()),
        check_password = $.trim($('#check_password').val()),
        result;

    $('#check_password').val(check_password);

    if(check_password != password)
    {
        showError('两次密码不一致',2);
        result = false;
    }else{
        removeError(2);
        result = true;
    }

    if (!result)
    {
        $('#check_password').parent().css('border','1px solid #f45242');
    }else{
        $('#check_password').parent().css({'border':'1px solid #e3e4ee'});
    }

    return result;
}

//步骤
$("#wizard").scrollable({
    onSeek: function(event,i){
        if (i == 0)
        {
            removeError(1);
        }else{
            $('.brandNameBtm .brandNameTop').css({'border':'1px solid #e3e4ee'});
            removeError(2);
        }
        $("#status li").removeClass("active").eq(i).addClass("active");
        $("#wizard .title").toggle();
        $("#status").toggleClass('selected');
    },
    onBeforeSeek:function(event,i){
        if(i == 1)
        {
            //品牌名称
            if (!checkBrandName()) return false;
            //公司名称
            if (!checkCompanyName()) return false;

            //行业
            if (!checkIndustry($('#industry_id'))) return false;

            if (!checkIndustry($('#industry_child_id'))) return false;

            //手机
            if (!checkTelephone()) return false;

            //联系人
            if (!checkCombiner()) return false;

            //图形验证码
            // if (!checkImgCode()) return false;

            //短信验证码
            if (!checkSmsCode()) return false;
        }
    }
});

//提交表单
$("#sub").click(function(){
    //账户名称
    if (!checkUsername()) return false;

    //密码
    if (!checkPassword()) return false;

    //确认密码
    if (!checkConfirmPassword()) return false;

    $('form').submit();

    $(this).val('提交中');
});

//input边框变蓝色
$('.brandNameTop input:text,.brandNameTop input:password').focus(function(){
    $(this).parents('.brandNameTop').css('border','1px solid #a1aed4');
});
$('.brandNameBtm input:text,.brandNameBtm input:password').focus(function(){
    $(this).parents('.brandNameBtm').css('border','1px solid #a1aed4');
});

//选择品牌所属行业
function showGrade(tag)
{
    var industry_id = $.trim($('#industry_id').val()),
        industry_child_id = $.trim($('#industry_child_id').val());

    if(tag == 1){
        $('#industry_ul1').show();
        $('#industry_ul2').hide();
        $('.mapIcon').css('border','1px solid #a1aed4');
        $('.rightArrowIcon').css({'border':'none','border-left':'1px solid #e3e4ee'});
    }else if(tag == 2){
        if(parseInt(industry_id) != 0)
        {
            $('#industry_ul1').hide();
            $('#industry_ul2').show();
            $('.mapIcon').css('border','none');
            $('.rightArrowIcon').css('border','1px solid #a1aed4');
        }
    }

    e = window.event || arguments.callee.caller.arguments[0];
    if (e.stopPropagation) {
        e.stopPropagation();
    } else {
        e.cancelBubble = true;
    }

    $(document).click(function () {
        $("#industry_ul1,#industry_ul2").hide();
        $('.mapIcon,.rightArrowIcon').css('border','none');
        $('.mapIcon').css('border-right','1px solid #e3e4ee');
    });
}

//一级行业选中
function selectParent(obj)
{
    var old_content = obj.html(),
        tag=obj.attr('tag');
    $('#areaCont1').text(old_content);
    $('#industry_id').val(tag);

    e = window.event || arguments.callee.caller.arguments[0] ;
    if (e.stopPropagation) {
        e.stopPropagation();
    } else {
        e.cancelBubble = true;
    }

    $('#industry_ul1').hide();
    $('.mapIcon').css('border','none');

    removeError(1);

    if(tag != 0)
    {
        $.ajax({
            url:'/user/Ajaxcate/',
            type:'post',
            data:{sid:tag},
            dataType:'json',
            success:function (html) {
                $('.selectIndustry_ul2').html(html.html);
            }
        });
    }
}

//二级行业选中
function selectChild(obj)
{
    var old_content = obj.html(),
        tag=obj.attr('tag');
    $('#areaCont2').text(old_content);
    $('#industry_child_id').val(tag);

    e = window.event || arguments.callee.caller.arguments[0] ;
    if (e.stopPropagation) {
        e.stopPropagation();
    } else {
        e.cancelBubble = true;
    }

    removeError(1);

    $('#industry_ul2').hide();
    $('.rightArrowIcon').css({'border':'none','border-left':'1px solid #e3e4ee'});
}

//发送短信验证码
function sms()
{
    var telephone = $.trim($("#telephone").val());
    var imgCode2    = $.trim($("#code").val());
    if(imgCode2.length<4){
        layer.msg('图片验证码格式错误!',{time:1000});
        return false;
    }

    if (!checkTelephone()) return false;

    //发送短信
    var curCount = 60;
    $("#checkBtn").attr("disabled", "true");
    $("#checkBtn").val(curCount + "秒后重新获取");
    $("#checkBtn").attr("class", "huoQu color2");

    //timer处理函数
    function SetRemainTime()
    {
        if (curCount == 0)
        {
            window.clearInterval(InterValObj);//停止计时器
            $(".getYzm").removeAttr("disabled");//启用按钮
            $(".getYzm").val("重新获取验证码");
            $(".getYzm").css("color", "#fa5c4c");
            code = ""; //清除验证码。如果不清除，过时间后，输入收到的验证码依然有效
        }
        else
        {
            curCount--;
            $(".getYzm").attr("disabled", "false");
            $(".getYzm").val(curCount + "秒后重新获取");
            $(".getYzm").css("color", "#b6b6b6");
        }
    }

    InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次

    $.ajax({
        type:'post',
        url:'/user/sendSmsCode/',
        dataType:'json',
        // jsonp:'callback',
        data:{imgCode:imgCode2,telephone:telephone},
        success:function(html)
        {
            if(html.code == 200 ){
                layer.msg(html.data,{time:1000});
                $('#check_code').val(html.data);
            }else{
                layer.msg(html.msg,{time:1000});
            }
        }
    });
}
