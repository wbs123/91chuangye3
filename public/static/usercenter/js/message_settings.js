$(function () {
   $('.dangQian').click(function () {
       $('.show_info').hide();
       $('.change_info').show();
   });

   //验证手机号
   $.validator.addMethod("mobile",function(value,element,params){
        var mobile = /^(86)?0?1\d{10}$/;
        return this.optional(element) || mobile.test(value);
    },"必须为有效手机号");

   $('#settings_from').validate({
       focusInvalid: false,
       errorElement: "em",
       errorPlacement: function ( error, element ) {
           error.insertAfter(element.parent().siblings('.ipt_errorRed').find('img'));
       },
       highlight: function( element, errorClass ) {
           $(element).css('border','1px solid #ec4c16');
           $(element).siblings('.checkMark').removeClass("checkMark_dui").addClass('checkMark_cuo');
           $(element).parent().siblings('.ipt_errorRed').show();
           $(element).parent().siblings('.ipt_error:first').hide();
       },
       unhighlight: function( element, errorClass, validClass ) {
           $(element).css('border','1px solid #b2b7c4');
           $(element).siblings('.checkMark').removeClass("checkMark_cuo").addClass('checkMark_dui');
           $(element).parent().siblings('.ipt_error:first').hide();
           $(element).parent().siblings('.ipt_errorRed').hide();
       },
       onfocusin: function( element ) {
           $(element).css('border','1px solid #4999d2');
           $(element).siblings('.checkMark').removeClass("checkMark_cuo").removeClass('checkMark_dui');
           $(element).parent().siblings('.ipt_error:first').show();
           $(element).parent().siblings('.ipt_errorRed').hide();
       },
       onfocusout: function(element){
           $(element).css('border','1px solid #b2b7c4');
           $(element).parent().siblings('.ipt_error:first').hide();
           $(element).valid();
       },
       rules: {
           telephone: {
               required: true,
               mobile: true
           },
           code: {
               required: true,
               remote: {
                   url: host + 'v1/message_settings/check_code.php',
                   type: 'post',
                   data: {
                       code: function () {
                           return $('#code').val();
                       },
                       telephone: function () {
                           return $('#telephone').val();
                       }
                   }
               }
           }
       },
       messages: {
           telephone: {
               required: '手机号为必填项',
               mobile: '手机号格式错误'
           },
           code: {
               required: '验证码为必填项',
               remote: '验证码错误'
           }
       },
       submitHandler: function (form) {
           $(form).ajaxSubmit({
               success:function (result) {
                   if (result === 'false')
                   {
                       window.location.reload();
                   }else {
                       result = parseInt(result);
                       if (result === 1) {
                           tips(1, '', '', '');
                       } else {
                           tips(2, '', '', '');
                       }
                   }
               }
           });
       }
   });
});

/**
 * 发送短信
 */
var countdown = 60;
function sendMsg()
{
    var telephone = $('#telephone').val(),reg = /^(86)?0?1\d{10}$/;
    if (reg.test(telephone))
    {
        $.ajax({
            url: host + 'v1/password/send_code.php?telephone='+telephone,
            success: function (result) {
                if (result === 'false')
                {
                    window.location.reload();
                }else {
                    result = parseInt(result);
                    if (result !== -1) {
                        count_down();
                    }
                }
            }
        });
    }
}

/**
 * 倒计时
 * @returns {boolean}
 */
function count_down()
{
    if(countdown === 0)
    {
        $(".freeGet").attr("disabled",false).val("重新获取");
        countdown = 60;
        return false;
    }
    else{
        $(".freeGet").attr("disabled",true).val(countdown+"秒");
        countdown--;
    }

    setTimeout(function(){
        count_down();
    },1000);
}