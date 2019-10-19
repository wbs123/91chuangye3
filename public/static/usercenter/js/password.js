$(function () {
    //自定义验证--密码
    $.validator.addMethod("num_letter",function(value,element,params){
        var mobile = /^\d+[a-zA-Z][a-zA-Z\d]*|[a-zA-Z]+\d[a-zA-Z\d]*$/;
        return this.optional(element) || mobile.test(value);
    },"必须为字母与数字的组合");

    $('#reset_form').validate({
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
            oldpassword: {
                required: true,
                remote: {
                    url: host + 'v1/password/check_old_password.php',
                    type: 'post',
                    data: {
                        password: function () {
                            return $('#oldpassword').val();
                        }
                    }
                }
            },
            newpassword: {
                required: true,
                minlength: 6,
                maxlength: 20,
                num_letter: true
            },
            newpwd: {
                required: true,
                equalTo: '#newpassword'
            }
        },
        messages: {
            oldpassword: {
                required: '原密码为必填项',
                remote: '原密码错误'
            },
            newpassword: {
                required: '新密码为必填项',
                minlength: '新密码至少6位',
                maxlength: '新密码最多20位'
            },
            newpwd: {
                required: '确认密码为必填项',
                equalTo: '新密码两次输入不一致'
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